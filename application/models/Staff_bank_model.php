<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_bank_model extends CI_Model
{
    private $table = 'staff_bank';
    private $table_staff = 'staff';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all staff bank accounts
     */
    public function get_all()
   {
    $this->db->select('staff_bank.*, staff.name as staff_name');
    $this->db->join('staff', 'staff.staff_id = staff_bank.staff_id', 'left');
    return $this->db->get($this->table)->result();
   }

    /**
     * Get all staff members
     */
    public function staff_members($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table_staff)->result();
    }

    /**
     * Get single bank account by ID
     */
    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    /**
     * Get accounts by staff ID
     */
    public function get_by_staff($staff_id)
    {
        $this->db->where('staff_id', $staff_id);
        return $this->db->get($this->table)->result();
    }

    public function get_total_balance($staff_id)
    {
        $this->db->select_sum('balance');
        $this->db->where('staff_id', $staff_id);
        $result = $this->db->get($this->table)->row();
        return $result->balance ? $result->balance : 0;
    }
    /**
     * Create a new bank account
     */
    public function insert($data)
    {
        $this->db->trans_start();

        $account_data = [
            'staff_id' => $this->security->xss_clean($data['staff_id']),
            'name' => $this->security->xss_clean($data['name']),
            'number' => $this->security->xss_clean($data['number']),
            'balance' => $this->security->xss_clean($data['balance']),
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $account_data);
        $account_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to create bank account');
        }

        return $account_id;
    }

    /**
     * Update bank account details
     */
    public function update($id, $data)
    {
        $this->db->trans_start();

        $account_data = [
            'staff_id' => $this->security->xss_clean($data['staff_id']),
            'name' => $this->security->xss_clean($data['name']),
            'number' => $this->security->xss_clean($data['number']),
            'balance' => $this->security->xss_clean($data['balance']),
            'updated_by' => $data['updated_by'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (isset($data['updated_by'])) {
            $account_data['updated_by'] = $data['updated_by'];
        }

        $this->db->where('id', $id);
        $this->db->update($this->table, $account_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to update bank account');
        }

        return true;
    }

    /**
     * Hard delete bank account
     */
    public function hardDelete($id)
    {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id', $id);
        $result = $this->db->delete($this->table);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_message('error', 'Delete failed for bank account ID: ' . $id);
            return false;
        }

        return $result;
    }

    /**
     * Check if bank account exists
     */
    public function exists($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}