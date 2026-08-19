<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_model extends CI_Model
{
    private $table = 'loans';
    private $table_transactions = 'transactions';
    

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all loans
     */
    public function get_all($active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table)->result();
    }

    /**
     * Get single loan by ID
     */
    public function get($id)
    {
        $this->db->where('loan_id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('loan_id', $id)
            ->get($this->table_transactions)
            ->result();
    }

    /**
 * Insert a new loan and optional forward balance transaction
 */
public function insert($data)
{
    $this->db->trans_start();

    // Prepare Loan data
    $loan_data = [
        'name'        => $this->security->xss_clean($data['name']),
        'balance'     => isset($data['balance']) ? (float) $data['balance'] : 0,
        'mobile'      => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
        'address'     => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
        'is_active'   => isset($data['is_active']) ? (int) $data['is_active'] : 1,
        'created_by'  => $data['created_by'],
        'created_at'  => date('Y-m-d H:i:s')
    ];

    $this->db->insert($this->table, $loan_data);
    $loan_id = $this->db->insert_id();

    // ✅ Insert forward balance transaction if balance is not zero
    $balance = (float) $loan_data['balance'];
    if ($balance != 0) {
        $transaction = [
            'loan_id'           => $loan_id,
            'transaction_type'  => 'loan',
            'type'              => ($balance > 0) ? 'Expense' : 'Income',
            'birr'              => abs($balance),
            'amount'            => null,
            'rate'              => null,
            'currency'          => null,
            'debit'             => ($balance > 0) ? abs($balance) : 0,
            'credit'            => ($balance < 0) ? abs($balance) : 0,
            'staff_id'          => null,
            'hawala_id'         => null,
            'description'       => 'BALANCE FORWARD',
            'notes'             => 'BALANCE FORWARD',
            'destination'       => 'BALANCE FORWARD',
            'bank_id'           => null,
            'date'              => !empty($data['created_date']) ? $data['created_date'] : date('Y-m-d'),
            'created_by'        => $data['created_by'],
            'created_at'        => date('Y-m-d H:i:s'),
            'is_active'         => 1,
        ];

        $this->db->insert($this->table_transactions, $transaction);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        throw new Exception('Failed to create loan');
    }

    return $loan_id;
}



    /**
     * Update loan details
     */
    public function update($id, $data)
    {
        $this->db->trans_start();

        $loan_data = [
            'name' => $this->security->xss_clean($data['name']),
            'balance' => isset($data['balance']) ? $this->security->xss_clean($data['balance']) : 0,
            'mobile' => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
            'address' => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
            'updated_by' => $data['updated_by'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if 'is_active' is provided in the form data and update it.
        if (isset($data['is_active'])) {
            $loan_data['is_active'] = $data['is_active'];  // true/false or 1/0
        }

        $this->db->where('loan_id', $id);
        $this->db->update($this->table, $loan_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to update loan');
        }

        return true;
    }

    /**
     * Soft delete loan (update is_active to 0)
     */
    public function softDelete($id, $data)
    {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false; // Return false if the ID is invalid
        }

        $this->db->trans_start();
        
        $update_data = [
            'is_active' => 0,
            'updated_by' => $data['updated_by'],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('loan_id', $id);
        $result = $this->db->update($this->table, $update_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_message('error', 'Soft delete failed for loan ID: ' . $id);
            return false;
        }

        return $result;
    }

    /**
     * Hard delete loan
     */
    public function hardDelete($id)
    {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false; // Return false if the ID is invalid
        }

        $this->db->trans_start();

        $this->db->where('loan_id', $id);
        $result = $this->db->delete($this->table);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_message('error', 'Hard delete failed for loan ID: ' . $id);
            return false;
        }

        return $result;
    }

    /**
     * Check if loan exists
     */
    public function exists($id)
    {
        $this->db->where('loan_id', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
