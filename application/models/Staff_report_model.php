<?php
class Staff_report_model extends CI_Model {
    
    public function get_all_staff() {
        $this->db->select('staff_id, name');
        return $this->db->get('staff')->result();
    }
    
    public function get_staff_banks($staff_id = null) {
        if ($staff_id) {
            $this->db->select('name as bank_name');
            $this->db->where('staff_id', $staff_id);
        } else {
            $this->db->select('DISTINCT(name) as bank_name');
        }
        
        $this->db->where('name IS NOT NULL');
        $this->db->where('TRIM(name) !=', '');
        return $this->db->get('staff_bank')->result();
    }
    
    public function get_transactions($filters) {
        $this->db->select('t.*, s.name as staff_name, sb.name as bank_name');
        $this->db->from('transactions t');
        $this->db->where('bank_id IS NOT NULL');
        $this->db->where('bank_id !=', '');
        $this->db->join('staff s', 's.staff_id = t.staff_id', 'left');
        $this->db->join('staff_bank sb', 'sb.id = t.bank_id', 'left');

        if (!empty($filters['staff_id'])) {
            $this->db->where('t.staff_id', $filters['staff_id']);
        }

        if (!empty($filters['bank_name'])) {
            $this->db->where('sb.name', $filters['bank_name']);
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $this->db->where("t.date BETWEEN '{$filters['date_from']}' AND '{$filters['date_to']}'");
        }

        $this->db->order_by('t.date', 'DESC');
        return $this->db->get()->result();
    }
    
    public function calculate_totals($transactions) {
        $totals = [
            'income' => 0,
            'expense' => 0,
            'net' => 0
        ];
        
        foreach ($transactions as $t) {
            if ($t->type == 'Income') {
                $totals['income'] += $t->birr;
            } else {
                $totals['expense'] += $t->birr;
            }
        }
        
        $totals['net'] = $totals['income'] - $totals['expense'];
        return $totals;
    }
}
?>