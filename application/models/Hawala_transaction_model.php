<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_transaction_model extends CI_Model
{
    private $table = 'transactions';
    private $hawala_table = 'hawalas';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db
            ->where('transaction_type', 'hawala')
            ->order_by('date', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->where('transaction_type', 'hawala')
            ->get($this->table)
            ->row();
    }

    public function get_all_hawalas($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get($this->hawala_table)->result();
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $amount = abs($data['amount']);
        $debit  = ($data['type'] === 'Expense') ? $amount : 0;
        $credit = ($data['type'] === 'Income') ? $amount : 0;

        $transaction_data = [
            'notes'            => $this->security->xss_clean($data['notes']),
            'destination'            => $this->security->xss_clean($data['destination']),
            'amount'           => $amount,
            'debit'            => $debit,
            'credit'           => $credit,
            'transaction_type' => 'hawala',
            'type'             => $data['type'], 
            'hawala_id'        => $data['hawala_id'],
            'date'             => $data['date'],
            'created_by'       => $data['created_by'],
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->db->insert($this->table, $transaction_data);

        // balance update 
        $amount_change = ($data['type'] === 'Income') ? -$amount : $amount;
        $this->db->set('balance', "balance + {$amount_change}", FALSE)
                 ->where('hawala_id', $data['hawala_id'])
                 ->update($this->hawala_table);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to insert hawala transaction');
        }

        return true;
    }

    public function update($id, $data)
    {
        $this->db->trans_start();

        $old = $this->get_by_id($id);
        if (!$old || $old->transaction_type !== 'hawala') {
            throw new Exception('Transaction not found or invalid type');
        }

        $locked_type = $old->type;
        $locked_hawala_id = $old->hawala_id;

        $amount = abs($data['amount']);
        $debit  = ($locked_type === 'Expense') ? $amount : 0;
        $credit = ($locked_type === 'Income') ? $amount : 0;

        $amount_changed = round((float)$old->amount, 2) !== round($amount, 2);

        if ($amount_changed) {
            // Reverse old balance
            $reverse_amount = ($locked_type === 'Income') ? -$old->amount : $old->amount;
            $this->db->set('balance', "balance + {$reverse_amount}", FALSE)
                     ->where('hawala_id', $locked_hawala_id)
                     ->update($this->hawala_table);
        }

        $update_data = [
            'notes'       => $this->security->xss_clean($data['notes']),
            'destination'       => $this->security->xss_clean($data['destination']),
            'date'        => $data['date'],
            'updated_by'  => $data['updated_by'],
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        if ($amount_changed) {
            $update_data['amount'] = $amount;
            $update_data['debit']  = $debit;
            $update_data['credit'] = $credit;
        }

        $this->db->where('id', $id)->update($this->table, $update_data);

        if ($amount_changed) {
            // Apply new balance
            $adjust_amount = ($locked_type === 'Income') ? -$amount : $amount;
            $this->db->set('balance', "balance + {$adjust_amount}", FALSE)
                     ->where('hawala_id', $locked_hawala_id)
                     ->update($this->hawala_table);
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to update hawala transaction');
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

        // Reverse balance:
        $reverse_amount = ($locked_type === 'Income') ? $transaction->amount : -$transaction->amount;
        $this->db->set('balance', "balance + {$reverse_amount}", FALSE)
                 ->where('hawala_id', $locked_hawala_id)
                 ->update($this->hawala_table);

        $this->db->where('id', $id)->delete($this->table);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to delete hawala transaction');
        }

        return true;
    }
}
