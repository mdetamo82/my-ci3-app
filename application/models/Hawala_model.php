<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_model extends CI_Model
{
    private $table = 'hawalas';
    private $table_transactions = 'transactions';
    private $tbl_currencies = 'tbl_currencies';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all hawala records
     */
    public function get_all($active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table)->result();
    }

    /**
     * Get single hawala by ID
     */
    public function get($id)
    {
        $this->db->where('hawala_id', $id);
        return $this->db->get($this->table)->row();
    }

    /**
     * Get single hawala transaction by ID
     */

    public function get_by_id($id)
    {
        return $this->db
            ->where('hawala_id', $id)
            ->get($this->table_transactions)
            ->result();
    }


    /**
     * Get all hawala currencies records
     */
    public function get_all_currencies()
    {
        return $this->db->get($this->tbl_currencies)->result();
    }

    public function insert($data)
    {
        $this->db->trans_start();
    
        // Prepare Hawala data
        $hawala_data = [
            'name'        => $this->security->xss_clean($data['name']),
            'mark'        => $this->security->xss_clean($data['mark']),
            'balance'     => $data['balance'],
            'currency'    => $this->security->xss_clean($data['currency']),
            'mobile'      => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
            'address'     => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
            'is_active'   => $data['is_active'],
            'created_by'  => $data['created_by'],
            'created_at'  => date('Y-m-d H:i:s')
        ];
    
        $this->db->insert($this->table, $hawala_data);
        $hawala_id = $this->db->insert_id();
    
        // ✅ Insert forward balance transaction if balance is not zero
        $balance = isset($data['balance']) ? (float)$data['balance'] : 0;
        if ($balance != 0) {
            $transaction = [
                'hawala_id'         => $hawala_id,
                'transaction_type'  => 'hawala',
                'type'              => ($balance > 0) ? 'Expense' : 'Income',
                'birr'              => 0,
                'amount'            => abs($balance),
                'debit'             => ($balance > 0) ? abs($balance) : 0,
                'credit'            => ($balance < 0) ? abs($balance) : 0,
                'rate'              => 0,
                'currency'          => $data['currency'],
                'notes'              => 'BALANCE FORWARD',
                'description'       => 'BALANCE FORWARD',
                'destination'       => 'BALANCE FORWARD',
                'date'             => !empty($data['created_date']) ? $data['created_date'] : date('Y-m-d'), // ✅ use posted date
                'created_by'        => $data['created_by'],
                'created_at'        => date('Y-m-d H:i:s'),
                'is_active'         => 1,
            ];
    
            $this->db->insert($this->table_transactions, $transaction);
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Failed to create hawala');
        }
    
        return $hawala_id;
    }
    

    public function update($id, $data)
    {
        $this->db->trans_start();

        $hawala_data = [
            'name'        => $this->security->xss_clean($data['name']),
            'mark'        => $this->security->xss_clean($data['mark']),
            'currency'    => $this->security->xss_clean($data['currency']),
            'mobile'      => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
            'address'     => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
            'is_active'   => $data['is_active'],
            'updated_by'  => $data['updated_by'],
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $this->db->where('hawala_id', $id);
        $this->db->update($this->table, $hawala_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Failed to update hawala');
        }

        return TRUE;
    }

    public function is_mark_taken($mark, $exclude_id = null)
{
    $this->db->where('mark', $mark);
    if ($exclude_id) {
        $this->db->where('hawala_id !=', $exclude_id);
    }
    return $this->db->get($this->table)->num_rows() > 0;
}



    /**
     * Soft delete hawala (set is_active = 0)
     */
    public function softDelete($id, $data)
    {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false;
        }

        $this->db->trans_start();

        $update_data = [
            'is_active' => 0,
            'updated_by' => $data['updated_by'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('hawala_id', $id);
        $result = $this->db->update($this->table, $update_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_message('error', 'Soft delete failed for hawala ID: ' . $id);
            return false;
        }

        return $result;
    }

    /**
     * Hard delete hawala record
     */
    public function hardDelete($id)
    {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('hawala_id', $id);
        $result = $this->db->delete($this->table);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_message('error', 'Hard delete failed for hawala ID: ' . $id);
            return false;
        }

        return $result;
    }

    /**
     * Check if hawala exists
     */
    public function exists($id)
    {
        $this->db->where('hawala_id', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }

        public function get_currency_summary()
    {
        $this->db->select('currency, COUNT(*) as count');
        $this->db->where('is_active', 1);
        $this->db->group_by('currency');
        return $this->db->get('hawalas')->result();
    }

    public function get_hawalas_by_currency($currency)
    {
        $this->db->select('name, mark, balance');
        $this->db->from('hawalas');
        $this->db->where(['currency' => $currency, 'is_active' => 1]);
        return $this->db->get()->result();
    }

}
