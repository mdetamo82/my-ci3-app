<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_transaction_model extends CI_Model
{
    private $table = 'transactions';
    private $loan_table = 'loans';
    private $staff_table = 'staff';
    private $staff_bank_table = 'staff_bank';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db->where('transaction_type', 'loan')
                ->order_by('date', 'DESC')
                ->get($this->table)
                ->result();

    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('transaction_type', 'loan')
            ->get($this->table)
            ->row();
    }

    public function get_all_staff($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->staff_table)->result();
    }

    public function get_all_loans($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->loan_table)->result();
    }
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
            'transaction_type' => 'loan',
            'type' => $data['type'],
            'staff_id' => $data['staff_id'],
            'loan_id' => $data['loan_id'],
            'bank_id' => $data['bank_id'],
            'date' => $data['date'],
            'created_by' => $data['created_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $transaction_data);

        // Update loan balance
        $amount_change = ($data['type'] === 'Income') ? -$data['birr'] : $data['birr'];
        $this->db->set('balance', "balance + {$amount_change}", FALSE);
        $this->db->where('loan_id', $data['loan_id']);
        $this->db->update($this->loan_table);

        // Update staff bank balance (in birr) if bank_id is set
        if (!empty($data['bank_id'])) {
            $birr_change = ($data['type'] === 'Income') ? $data['birr'] : -$data['birr'];
            $this->db->set('balance', "balance + {$birr_change}", FALSE);
            $this->db->where('id', $data['bank_id']);
            $this->db->update($this->staff_bank_table);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Failed to insert Loan transaction');
        }

        return true;
    }

    public function update($id, $data)
    {
        $this->db->trans_start();
    
        $old = $this->db->get_where($this->table, ['id' => $id, 'transaction_type' => 'loan'])->row();
        if (!$old) {
            throw new Exception('Transaction not found');
        }
    
        // Reverse previous Loan balance
        $reverse_amount = ($old->type === 'Income') ? $old->birr : -$old->birr;
        $this->db->set('balance', "balance + {$reverse_amount}", FALSE)
                 ->where('loan_id', $old->loan_id)
                 ->update($this->loan_table);
    
        // Reverse previous Staff Bank balance
        if ($old->bank_id) {
            $reverse_birr = ($old->type === 'Income') ? -$old->birr : $old->birr;
            $this->db->set('balance', "balance + {$reverse_birr}", FALSE)
                     ->where('id', $old->bank_id)
                     ->update($this->staff_bank_table);
        }
    
        // LOCKED FIELDS (Don't trust input for these)
        $locked_type = $old->type;
        $locked_loan_id = $old->loan_id;
        $locked_staff_id = $old->staff_id;
        $locked_bank_id = $old->bank_id;
    
        // NEW editable values
        $birr = abs($data['birr']);
    
        // Prepare sanitized update data
        $update_data = [
            'notes'       => $this->security->xss_clean($data['notes']),
            'birr'        => $birr,
            'date'        => $data['date'],
            'updated_by'  => $data['updated_by'],
            'updated_at'  => date('Y-m-d H:i:s')
        ];
    
        $this->db->where('id', $id)->update($this->table, $update_data);
    
        // Apply new Loan balance
        $adjust_amount = ($locked_type === 'Income') ? -$birr : $birr;
        $this->db->set('balance', "balance + {$adjust_amount}", FALSE)
                 ->where('loan_id', $locked_loan_id)
                 ->update($this->loan_table);
    
        // Apply new Staff Bank balance
        if (!empty($locked_bank_id)) {
            $adjust_birr = ($locked_type === 'Income') ? $birr : -$birr;
            $this->db->set('balance', "balance + {$adjust_birr}", FALSE)
                     ->where('id', $locked_bank_id)
                     ->update($this->staff_bank_table);
        }
    
        $this->db->trans_complete();
    
        if (!$this->db->trans_status()) {
            throw new Exception('Failed to update loan transaction');
        }
    
        return true;
    }
    
    public function delete($id)
    {
        $this->db->trans_start();

        $transaction = $this->db->get_where($this->table, ['id' => $id, 'transaction_type' => 'loan'])->row();
        if (!$transaction) {
            return false;
        }

        // Reverse loam balance
        $amount = ($transaction->type === 'Income') ? $transaction->birr : -$transaction->birr;
        $this->db->set('balance', "balance + {$amount}", FALSE);
        $this->db->where('loan_id', $transaction->loan_id);
        $this->db->update($this->loan_table);

        // Reverse staff bank balance if applicable
        if ($transaction->bank_id) {
            $birr = ($transaction->type === 'Income') ? -$transaction->birr : $transaction->birr;
            $this->db->set('balance', "balance + {$birr}", FALSE);
            $this->db->where('id', $transaction->bank_id);
            $this->db->update($this->staff_bank_table);
        }

        $this->db->where('id', $id);
        $this->db->delete($this->table);

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE;
    }
}
