<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_staff_transaction_model extends CI_Model
{
    private $table = 'transactions';
    private $hawala_table = 'hawalas';
    private $staff_table = 'staff';
    private $staff_bank_table = 'staff_bank';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db
            ->where('transaction_type', 'hawala_staff')
            ->order_by('date', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('transaction_type', 'hawala_staff')
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

    public function get_all_hawalas($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->hawala_table)->result();
    }

    public function get_all_banks()
    {
        return $this->db->get($this->staff_bank_table)->result();
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $amount = abs($data['amount']);
        $birr = abs($data['birr']);
        $debit = ($data['type'] === 'Expense') ? $amount : 0;
        $credit = ($data['type'] === 'Income') ? $amount : 0;

        $transaction_data = [
            'notes'            => $this->security->xss_clean($data['notes']),
            'birr'             => $birr,
            'amount'           => $amount,
            'debit'            => $debit,
            'credit'           => $credit,
            'rate'             => $data['rate'],
            'currency'         => $data['currency'],
            'transaction_type' => 'hawala_staff',
            'type'             => $data['type'],
            'staff_id'         => $data['staff_id'],
            'hawala_id'        => $data['hawala_id'],
            'bank_id'          => $data['bank_id'],
            'date'             => $data['date'],
            'created_by'       => $data['created_by'],
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $transaction_data);

        // ✅ Update Hawala balance (reverse staff view)
        $amount_change = ($data['type'] === 'Income') ? -$amount : $amount;
        $this->db->set('balance', "balance + {$amount_change}", FALSE)
                 ->where('hawala_id', $data['hawala_id'])
                 ->update($this->hawala_table);

        // ✅ Update Staff bank balance (staff view)
        if (!empty($data['bank_id'])) {
            $birr_change = ($data['type'] === 'Income') ? $birr : -$birr;
            $this->db->set('balance', "balance + {$birr_change}", FALSE)
                     ->where('id', $data['bank_id'])
                     ->update($this->staff_bank_table);
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to insert hawala_staff transaction');
        }

        return true;
    }

    public function update($id, $data)
    {
        $this->db->trans_start();

        $old = $this->get_by_id($id);
        if (!$old) {
            throw new Exception('Transaction not found');
        }

        $locked_type = $old->type;
        $locked_hawala_id = $old->hawala_id;
        $locked_bank_id = $old->bank_id;

        $amount = abs($data['amount']);
        $birr   = abs($data['birr']);
        $debit  = ($locked_type === 'Expense') ? $amount : 0;
        $credit = ($locked_type === 'Income') ? $amount : 0;

        $amount_changed = round((float)$old->amount, 2) !== round($amount, 2);
        $birr_changed   = round((float)$old->birr, 2) !== round($birr, 2);
        $should_update_balance = $amount_changed || $birr_changed;

        if ($should_update_balance) {
            // ✅ Reverse old Hawala balance (reverse of insert)
            $reverse_amount = ($locked_type === 'Income') ? $old->amount : -$old->amount;
            $this->db->set('balance', "balance + {$reverse_amount}", FALSE)
                     ->where('hawala_id', $locked_hawala_id)
                     ->update($this->hawala_table);

            // ✅ Reverse old Bank balance
            if (!empty($locked_bank_id)) {
                $reverse_birr = ($locked_type === 'Income') ? -$old->birr : $old->birr;
                $this->db->set('balance', "balance + {$reverse_birr}", FALSE)
                         ->where('id', $locked_bank_id)
                         ->update($this->staff_bank_table);
            }
        }

        // Update transaction data
        $update_data = [
            'notes'       => $this->security->xss_clean($data['notes']),
            'birr'        => $birr,
            'amount'      => $amount,
            'debit'       => $debit,
            'credit'      => $credit,
            'rate'        => $data['rate'],
            'date'        => $data['date'],
            'updated_by'  => $data['updated_by'],
            'updated_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id)->update($this->table, $update_data);

        if ($should_update_balance) {
            // ✅ Apply new Hawala balance
            $adjust_amount = ($locked_type === 'Income') ? -$amount : $amount;
            $this->db->set('balance', "balance + {$adjust_amount}", FALSE)
                     ->where('hawala_id', $locked_hawala_id)
                     ->update($this->hawala_table);

            // ✅ Apply new Bank balance
            if (!empty($locked_bank_id)) {
                $adjust_birr = ($locked_type === 'Income') ? $birr : -$birr;
                $this->db->set('balance', "balance + {$adjust_birr}", FALSE)
                         ->where('id', $locked_bank_id)
                         ->update($this->staff_bank_table);
            }
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to update hawala_staff transaction');
        }

        return true;
    }

    public function delete($id)
    {
        $this->db->trans_start();

        $transaction = $this->get_by_id($id);
        if (!$transaction) {
            throw new Exception('Transaction not found');
        }

        $locked_type = $transaction->type;
        $locked_hawala_id = $transaction->hawala_id;
        $locked_bank_id = $transaction->bank_id;

        // ✅ Reverse hawala balance
        $reverse_amount = ($locked_type === 'Income') ? $transaction->amount : -$transaction->amount;
        $this->db->set('balance', "balance + {$reverse_amount}", FALSE)
                 ->where('hawala_id', $locked_hawala_id)
                 ->update($this->hawala_table);

        // ✅ Reverse bank balance
        if (!empty($locked_bank_id)) {
            $reverse_birr = ($locked_type === 'Income') ? -$transaction->birr : $transaction->birr;
            $this->db->set('balance', "balance + {$reverse_birr}", FALSE)
                     ->where('id', $locked_bank_id)
                     ->update($this->staff_bank_table);
        }

        $this->db->where('id', $id)->delete($this->table);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to delete hawala_staff transaction');
        }

        return true;
    }
}
