<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_transfer_model extends CI_Model
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
            ->select('t1.*, h1.mark as from_mark, h2.mark as to_mark')
            ->from('transactions t1')
            ->join('transactions t2', 't1.transfer_group = t2.transfer_group AND t1.id != t2.id')
            ->join('hawalas h1', 't1.hawala_id = h1.hawala_id')
            ->join('hawalas h2', 't2.hawala_id = h2.hawala_id')
            ->where('t1.transaction_type', 'hawala_transfer')
            ->where('t1.type', 'Expense')
            ->order_by('t1.date', 'DESC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        $transfer = $this->db->where('id', $id)
            ->where('transaction_type', 'hawala_transfer')
            ->get($this->table)
            ->row();

        if (!$transfer) {
            return false;
        }

        $group_txns = $this->db->where('transfer_group', $transfer->transfer_group)
            ->where('transaction_type', 'hawala_transfer')
            ->get($this->table)
            ->result();

        $from_txn = $group_txns[0]->type == 'Expense' ? $group_txns[0] : $group_txns[1];
        $to_txn   = $group_txns[0]->type == 'Income' ? $group_txns[0] : $group_txns[1];

        return (object)[
            'id'          => $from_txn->id,
            'transfer_group' => $from_txn->transfer_group,
            'from_id'     => $from_txn->hawala_id,
            'to_id'       => $to_txn->hawala_id,
            'amount_from' => $from_txn->amount,
            'amount_to'   => $to_txn->amount,
            'rate'        => $from_txn->rate,
            'notes'       => $from_txn->notes,
            'date'        => $from_txn->date,
        ];
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

        $from = $this->db->get_where($this->hawala_table, ['hawala_id' => (int)$data['from_id']])->row();
        $to   = $this->db->get_where($this->hawala_table, ['hawala_id' => (int)$data['to_id']])->row();

        if (!$from || !$to) {
            throw new Exception('Invalid Hawala accounts.');
        }

        $amount_from = (float) $data['amount_from'];
        $amount_to   = (float) $data['amount_to'];
        $notes       = $this->security->xss_clean($data['notes']);
        $rate        = $this->security->xss_clean($data['rate']);
        $date        = $data['date'];
        $created_by  = (int) $data['created_by'];
        $transfer_group = uniqid('tx_', true);

        // if ($from->balance < $amount_from) {
        //     throw new Exception('Insufficient balance in source Hawala.');
        // }

        // Update balances
        $this->db->set('balance', 'balance - ' . $amount_from, false)
            ->where('hawala_id', $from->hawala_id)
            ->update($this->hawala_table);

        $this->db->set('balance', 'balance + ' . $amount_to, false)
            ->where('hawala_id', $to->hawala_id)
            ->update($this->hawala_table);

        // Insert Expense
        $this->db->insert($this->table, [
            'transaction_type' => 'hawala_transfer',
            'amount'       => $amount_from,
            'debit'        => $amount_from,
            'credit'       => 0,
            'hawala_id'    => $from->hawala_id,
            'type'         => 'Expense',
            'date'         => $date,
            'notes'        => $notes,
            'rate'         => $rate,
            'transfer_group' => $transfer_group,
            'created_by'   => $created_by,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        // Insert Income
        $this->db->insert($this->table, [
            'transaction_type' => 'hawala_transfer',
            'amount'       => $amount_to,
            'debit'        => 0,
            'credit'       => $amount_to,
            'hawala_id'    => $to->hawala_id,
            'type'         => 'Income',
            'date'         => $date,
            'notes'        => $notes,
            'rate'         => $rate,
            'transfer_group' => $transfer_group,
            'created_by'   => $created_by,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to complete transfer.');
        }

        return true;
    }

    public function update($transfer_group, $data)
    {
        $old_txns = $this->db->where('transfer_group', $transfer_group)
            ->where('transaction_type', 'hawala_transfer')
            ->get($this->table)
            ->result();

        if (count($old_txns) != 2) {
            throw new Exception('Invalid transfer group.');
        }

        $from_old = $old_txns[0]->type === 'Expense' ? $old_txns[0] : $old_txns[1];
        $to_old   = $old_txns[0]->type === 'Income' ? $old_txns[0] : $old_txns[1];

        $amount_from = (float) $data['amount_from'];
        $amount_to   = (float) $data['amount_to'];
        $notes       = $this->security->xss_clean($data['notes']);
        $rate        = $this->security->xss_clean($data['rate']);
        $date        = $data['date'];
        $updated_by  = (int) $data['updated_by'];

        $this->db->trans_start();

        // Revert old balances
        $this->db->set('balance', 'balance + ' . $from_old->amount, false)
            ->where('hawala_id', $from_old->hawala_id)
            ->update($this->hawala_table);

        $this->db->set('balance', 'balance - ' . $to_old->amount, false)
            ->where('hawala_id', $to_old->hawala_id)
            ->update($this->hawala_table);

        // Apply new balances
        $this->db->set('balance', 'balance - ' . $amount_from, false)
            ->where('hawala_id', $from_old->hawala_id)
            ->update($this->hawala_table);

        $this->db->set('balance', 'balance + ' . $amount_to, false)
            ->where('hawala_id', $to_old->hawala_id)
            ->update($this->hawala_table);

        // Update Expense
        $this->db->where('id', $from_old->id)->update($this->table, [
            'amount'     => $amount_from,
            'debit'      => $amount_from,
            'credit'     => 0,
            'notes'      => $notes,
            'rate'       => $rate,
            'date'       => $date,
            'updated_by' => $updated_by,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Update Income
        $this->db->where('id', $to_old->id)->update($this->table, [
            'amount'     => $amount_to,
            'debit'      => 0,
            'credit'     => $amount_to,
            'notes'      => $notes,
            'rate'       => $rate,
            'date'       => $date,
            'updated_by' => $updated_by,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to update transfer.');
        }

        return true;
    }

    public function delete($id)
    {
        $this->db->trans_start();

        $transfer = $this->db->where('id', $id)
            ->where('transaction_type', 'hawala_transfer')
            ->get($this->table)
            ->row();

        if (!$transfer) {
            throw new Exception('Transfer not found.');
        }

        $transfers = $this->db->where('transfer_group', $transfer->transfer_group)
            ->where('transaction_type', 'hawala_transfer')
            ->get($this->table)
            ->result();

        foreach ($transfers as $t) {
            if ($t->type == 'Expense') {
                $this->db->set('balance', 'balance + ' . $t->amount, false)
                    ->where('hawala_id', $t->hawala_id)
                    ->update($this->hawala_table);
            } else {
                $this->db->set('balance', 'balance - ' . $t->amount, false)
                    ->where('hawala_id', $t->hawala_id)
                    ->update($this->hawala_table);
            }
        }

        $this->db->where('transfer_group', $transfer->transfer_group)
            ->where('transaction_type', 'hawala_transfer')
            ->delete($this->table);

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            throw new Exception('Failed to delete transfer.');
        }

        return true;
    }
}