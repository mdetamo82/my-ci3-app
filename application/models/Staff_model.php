<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model
{
    private $table = 'staff';
    private $table_bank = 'staff_bank';
    private $table_transactions = 'transactions';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all staff members
     */
    public function get_all($active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->table)->result();
    }

    /**
     * Get single staff member by ID
     */
    public function get($id)
    {
        $this->db->where('staff_id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('staff_id', $id)
            ->get($this->table_transactions)
            ->result();
    }
    /**
     * Create new staff member
     */
    public function insert($data)
    {
        $this->db->trans_start();

        $staff_data = [
            'name' => $this->security->xss_clean($data['name']),
            'mobile' => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
            'address' => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
            'department' => isset($data['department']) ? $this->security->xss_clean($data['department']) : null,
            'is_active' => 1, // assuming new staff is active by default
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $staff_data);
        $staff_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to create staff');
        }

        return $staff_id;
    }

    /**
     * Create new staff member
     */
    public function insert_bank($data)
    {
        $this->db->trans_start();

        $staff_data = [
            'name' => $this->security->xss_clean($data['name']),
            'staff_id' => $this->security->xss_clean($data['staff_id']),
            'number' => isset($data['number']) ? $this->security->xss_clean($data['number']) : null,
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table_bank, $staff_data);
        $staff_id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to create staff');
        }

        return $staff_id;
    }

    /**
     * Update staff member
     */
    public function update($id, $data)
    {
        $this->db->trans_start();

        $staff_data = [
            'name' => $this->security->xss_clean($data['name']),
            'mobile' => isset($data['mobile']) ? $this->security->xss_clean($data['mobile']) : null,
            'address' => isset($data['address']) ? $this->security->xss_clean($data['address']) : null,
            'department' => isset($data['department']) ? $this->security->xss_clean($data['department']) : null,
            'updated_by' => $data['updated_by'],
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
         // Check if 'is_active' is provided in the form data and update it.
         if (isset($data['is_active'])) {
        $staff_data['is_active'] = $data['is_active'];  // true/false or 1/0
         }

        $this->db->where('staff_id', $id);
        $this->db->update($this->table, $staff_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            throw new Exception('Failed to update staff');
        }

        return true;
    }

 
       /**
     * Soft delete staff member (update is_active to 0)
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
           
           $this->db->where('staff_id', $id);
           $result = $this->db->update($this->table, $update_data);
           
           $this->db->trans_complete();
           
           if ($this->db->trans_status() === FALSE) {
               log_message('error', 'Soft delete failed for staff ID: '.$id);
               return false;
           }
           
           return $result;
       }
       
       
       /**
     * Hard delete staff member 
     */
       public function hardDelete($id)
       {
        if (!is_numeric($id) || !$this->exists($id)) {
            return false; // Return false if the ID is invalid
        }
           $this->db->trans_start();
           
           $this->db->where('staff_id', $id);
           $result = $this->db->delete($this->table);
           
           $this->db->trans_complete();
           
           if ($this->db->trans_status() === FALSE) {
               log_message('error', 'Hard delete failed for staff ID: '.$id);
               return false;
           }
           
           return $result;
       }

        /**
         * Check if staff exists
         */
        public function exists($id)
        {
            $this->db->where('staff_id', $id);
            return $this->db->get($this->table)->num_rows() > 0;
        }

            /**
         * Get grand total balance across all staff banks
         */
        public function get_all_staff_total_balance()
        {
            $this->db->select('SUM(balance) as total');
            $this->db->from($this->table_bank);
            $this->db->where('balance IS NOT NULL', null, false);
            return (float) $this->db->get()->row()->total;
        }


        /**
         * Get total balance for a specific staff member
         * @param int $staff_id
         */
        public function get_staff_total_balance($staff_id)
        {
            $this->db->select('SUM(balance) as total');
            $this->db->from($this->table_bank);
            $this->db->where('staff_id', $staff_id);
            $this->db->where('balance IS NOT NULL', null, false);
            return (float) $this->db->get()->row()->total;
        }

        public function get_all_staff_balances_as_map()
        {
            $this->db->select('staff_id, SUM(balance) as balance');
            $this->db->from($this->table_bank);
            $this->db->group_by('staff_id');
            $query = $this->db->get();

            $result = [];
            foreach ($query->result() as $row) {
                $result[$row->staff_id] = (float) $row->balance;
            }

            return $result;
        }


    
}
