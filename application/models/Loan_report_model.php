<?php
class Loan_report_model extends CI_Model {
    
    public function get_all_loans() {
        $this->db->select('loan_id, name');
        return $this->db->get('loans')->result();
    }
    
    public function get_transactions($filters) {
        $this->db->select('t.*, l.name as loan_name');
        $this->db->from('transactions t');
        $this->db->join('loans l', 'l.loan_id = t.loan_id', 'left');

        if (!empty($filters['loan_id'])) {
            $this->db->where('t.loan_id', $filters['loan_id']);
        }

        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $this->db->where("t.date BETWEEN '{$filters['date_from']}' AND '{$filters['date_to']}'");
        }

        $this->db->where('t.transaction_type', 'loan'); // Only show loan transactions
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
    
    public function calculate_running_balance($transactions) {
        $running_balance = 0;
        $transactions_with_balance = [];
        
        foreach ($transactions as $t) {
            $running_balance += ($t->type == 'Income' ? $t->birr : -$t->birr);
            $t->balance = $running_balance;
            $transactions_with_balance[] = $t;
        }
        
        return $transactions_with_balance;
    }
}
?>