<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model
{
    private $table = 'transactions';
    private $bank_table = 'staff_bank';
    private $staff_table = 'staff';

    public function get_filtered($filters = [])
    {
        $this->db->select("t.*, b.name AS bank_name, s.name AS staff_name")
                 ->from("{$this->table} t")
                 ->join("{$this->bank_table} b", "b.id = t.bank_id", "left")
                 ->join("{$this->staff_table} s", "s.staff_id = t.staff_id", "left");

        if (!empty($filters['from'])) {
            $this->db->where('t.date >=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $this->db->where('t.date <=', $filters['to']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('t.type', $filters['type']);
        }
        if (!empty($filters['transaction_type'])) {
            $this->db->where('t.transaction_type', $filters['transaction_type']);
        }
        if (!empty($filters['bank_id'])) {
            $this->db->where('t.bank_id', $filters['bank_id']);
        }
        if (!empty($filters['staff_id'])) {
            $this->db->where('t.staff_id', $filters['staff_id']);
        }

        $this->db->order_by('t.date', 'DESC');
        return $this->db->get()->result();
    }

    public function get_summary($filters = [])
    {
        $this->db->select("
            SUM(CASE WHEN t.type = 'Income' THEN t.birr ELSE 0 END) AS total_income,
            SUM(CASE WHEN t.type = 'Expense' THEN t.birr ELSE 0 END) AS total_expense
        ");
        $this->db->from("{$this->table} t");

        if (!empty($filters['from'])) {
            $this->db->where('t.date >=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $this->db->where('t.date <=', $filters['to']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('t.type', $filters['type']);
        }
        if (!empty($filters['transaction_type'])) {
            $this->db->where('t.transaction_type', $filters['transaction_type']);
        }
        if (!empty($filters['bank_id'])) {
            $this->db->where('t.bank_id', $filters['bank_id']);
        }
        if (!empty($filters['staff_id'])) {
            $this->db->where('t.staff_id', $filters['staff_id']);
        }

        return $this->db->get()->row();
    }

    public function get_all_banks()
    {
        return $this->db->order_by('name')->get($this->bank_table)->result();
    }

    public function get_all_staff()
    {
        return $this->db->order_by('name')->get($this->staff_table)->result();
    }
}
