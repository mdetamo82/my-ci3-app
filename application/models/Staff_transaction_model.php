<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_transaction_model extends CI_Model
{
    private $table = 'transactions';
    private $staff_table = 'staff';
    private $staff_bank_table = 'staff_bank';
    private $hawala_table = 'hawalas';
    private $loan_table = 'loans';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all staff transactions
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('t.*, b.name as bank_name')
            ->from($this->table . ' t')
            ->join($this->staff_bank_table . ' b', 'b.id = t.bank_id', 'left')
            ->order_by('t.date', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get transactions by staff ID
     * @param int $staff_id
     * @return array
     */
    public function get_by_staff_id($staff_id)
    {
        if (!is_numeric($staff_id)) return [];

        return $this->db
            ->select('t.*, b.name as bank_name')
            ->from($this->table . ' t')
            ->join($this->staff_bank_table . ' b', 'b.id = t.bank_id', 'left')
            ->where('t.staff_id', (int)$staff_id)
            ->order_by('t.date', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get detailed transaction info for printing/view
     * @param int $id
     * @return object|null
     */
    public function get_detailed_by_id($id)
    {
        if (!is_numeric($id)) return null;

        return $this->db
            ->select('t.*, b.name as bank_name, b.number as bank_number, b.balance as bank_balance')
            ->from($this->table . ' t')
            ->join($this->staff_bank_table . ' b', 'b.id = t.bank_id', 'left')
            ->where('t.id', (int)$id)
            ->get()
            ->row();
    }

    /**
     * Get transaction with related info (staff, hawala, loan)
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id)
    {
        if (!is_numeric($id)) return null;

        return $this->db
            ->select('
                t.*, 
                b.name as bank_name, 
                b.number as bank_number, 
                b.balance as bank_balance, 
                s.name as staff_name, 
                s.mobile as staff_mobile, 
                s.address as staff_address,
                h.name as hawala_name,
                h.mobile as hawala_mobile,
                l.name as loan_name
            ')
            ->from($this->table . ' t')
            ->join($this->staff_bank_table . ' b', 'b.id = t.bank_id', 'left')
            ->join($this->staff_table . ' s', 's.staff_id = t.staff_id', 'left')
            ->join($this->hawala_table . ' h', 'h.hawala_id = t.hawala_id', 'left')
            ->join($this->loan_table . ' l', 'l.loan_id = t.loan_id', 'left')
            ->where('t.id', (int)$id)
            ->get()
            ->row();
    }


     
     /**
     * Get all staff members
     */
    public function get_all_staff($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->staff_table)->result();
    }

     /**
     * Get all staff banks
     */
    public function get_all_banks()
    {
      
        return $this->db->get($this->staff_bank_table)->result();
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $transaction_data = [
            'notes' => $this->security->xss_clean($data['notes']),
            'birr' => $data['birr'],
            'transaction_type' => 'staff',
            'type' => $data['type'],
            'staff_id' => $data['staff_id'],
            'bank_id' => isset($data['bank_id']) ? $data['bank_id'] : null,
            'date' => $data['date'],
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $transaction_data);

        // Update staff bank balance 
        if (!empty($data['bank_id'])) {
            $amount = ($data['type'] === 'Income') ? $data['birr'] : -$data['birr'];
            $this->db->set('balance', "balance + {$amount}", FALSE);
            $this->db->where('id', $data['bank_id']);
            $this->db->update($this->staff_bank_table);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Failed to insert staff transaction');
        }

        return true;
    }

    public function update($id, $data)
{
    $this->db->trans_start();

    $old = $this->db->get_where($this->table, ['id' => $id])->row();
    if (!$old) {
        throw new Exception('Transaction not found');
    }

    // Reverse previous bank balance if applicable
    if ($old->bank_id) {
        $reverse = ($old->type === 'Income') ? -$old->birr : $old->birr;
        $this->db->set('balance', "balance + {$reverse}", FALSE)
                 ->where('id', $old->bank_id)
                 ->update($this->staff_bank_table);
    }

    // Lock critical fields (never trust user input)
    $locked_type = $old->type;
    $locked_staff_id = $old->staff_id;
    $locked_bank_id = $old->bank_id;

    // Sanitize and prepare updated data
    $birr = abs($data['birr']);
    $update_data = [
        'notes'       => $this->security->xss_clean($data['notes']),
        'birr'        => $birr,
        'date'        => $data['date'],
        'updated_by'  => $data['updated_by'],
        'updated_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->where('id', $id)->update($this->table, $update_data);

    // Apply new bank balance if applicable
    if (!empty($locked_bank_id)) {
        $adjust = ($locked_type === 'Income') ? $birr : -$birr;
        $this->db->set('balance', "balance + {$adjust}", FALSE)
                 ->where('id', $locked_bank_id)
                 ->update($this->staff_bank_table);
    }

    $this->db->trans_complete();

    if (!$this->db->trans_status()) {
        throw new Exception('Failed to update staff transaction');
    }

    return true;
}

    public function delete($id)
    {
        $this->db->trans_start();

        $transaction = $this->db->get_where($this->table, ['id' => $id])->row();
        if (!$transaction) {
            return false;
        }

        // Reverse bank balance if applicable
        if ($transaction->bank_id) {
            $amount = ($transaction->type === 'Income') ? -$transaction->birr : $transaction->birr;
            $this->db->set('balance', "balance + {$amount}", FALSE);
            $this->db->where('id', $transaction->bank_id);
            $this->db->update($this->staff_bank_table);
        }

        $this->db->where('id', $id);
        $this->db->delete($this->table);

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE;
    }
}
