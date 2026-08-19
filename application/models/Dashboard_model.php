<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    private $staff_table = 'staff';
    private $bank_table = 'staff_bank';
    private $transaction_table = 'transactions';

    public function get_total_staff()
    {
        return $this->db->count_all_results($this->staff_table);
    }

    public function get_total_banks()
    {
        return $this->db->count_all_results($this->bank_table);
    }

    public function get_total_balance()
    {
        $result = $this->db->select_sum('balance')->get($this->bank_table)->row();
        return $result ? $result->balance : 0.00;
    }

    public function get_recent_transactions($limit = 5)
    {
        return $this->db
            ->select('t.*, s.name as staff_name, b.name as bank_name')
            ->from($this->transaction_table . ' t')
            ->join('staff s', 's.staff_id = t.staff_id', 'left')
            ->join('staff_bank b', 'b.id = t.bank_id', 'left')
            ->order_by('t.date', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_negative_balances()
    {
        return $this->db
            ->select('s.name as staff_name, b.name as bank_name, b.balance')
            ->from($this->bank_table . ' b')
            ->join('staff s', 's.staff_id = b.staff_id')
            ->where('b.balance <', 0)
            ->order_by('b.balance', 'ASC')
            ->get()
            ->result();
    }

    public function get_daily_summary()
    {
        return $this->db
            ->select("DATE(date) as date, 
                      SUM(CASE WHEN type = 'Income' THEN birr ELSE 0 END) as total_income,
                      SUM(CASE WHEN type = 'Expense' THEN birr ELSE 0 END) as total_expense")
            ->from('transactions')
         
            ->group_by('DATE(date)')
            ->order_by('DATE(date)', 'DESC')
            ->limit(7)
            ->get()
            ->result();
    }

    public function get_weekly_summary()
    {
        return $this->db
            ->select("YEARWEEK(date, 1) as week,
                      SUM(CASE WHEN type = 'Income' THEN birr ELSE 0 END) as total_income,
                      SUM(CASE WHEN type = 'Expense' THEN birr ELSE 0 END) as total_expense")
            ->from('transactions')
          
            ->group_by('YEARWEEK(date, 1)')
            ->order_by('week', 'DESC')
            ->limit(4)
            ->get()
            ->result();
    }

    public function get_monthly_summary()
    {
        return $this->db
            ->select("DATE_FORMAT(date, '%Y-%m') as month,
                      SUM(CASE WHEN type = 'Income' THEN birr ELSE 0 END) as total_income,
                      SUM(CASE WHEN type = 'Expense' THEN birr ELSE 0 END) as total_expense")
            ->from('transactions')
         
            ->group_by("DATE_FORMAT(date, '%Y-%m')")
            ->order_by('month', 'DESC')
            ->limit(6)
            ->get()
            ->result();
    }

    public function get_today_balance_summary()
    {
        $today = date('Y-m-d');

        $this->db->select([
            "SUM(CASE WHEN type = 'Income' THEN birr ELSE 0 END) as total_income",
            "SUM(CASE WHEN type = 'Expense' THEN birr ELSE 0 END) as total_expense"
        ]);
      
        $this->db->where('DATE(date)', $today);

        return $this->db->get('transactions')->row();
    }

    public function get_bank_summary()
    {
        return $this->db
            ->select('staff_bank.name as bank_name, SUM(staff_bank.balance) as total_balance')
            ->join('staff', 'staff.staff_id = staff_bank.staff_id', 'left')
            ->group_by('staff_bank.name')
            ->get('staff_bank')
            ->result();
    }

    public function get_staff_bank_details()
    {
        return $this->db
            ->select('staff_bank.name as bank_name, staff.name as staff_name, staff_bank.balance')
            ->join('staff', 'staff.staff_id = staff_bank.staff_id', 'left')
            ->group_by('staff_bank.name, staff.name')
            ->get('staff_bank')
            ->result();
    }

    public function get_currency_summary()
    { 
    return $this->db
        ->select('currency, COUNT(*) as count')
        ->from('hawalas')
        ->where('is_active', 1)
        ->group_by('currency')
        ->get()
        ->result();
    }   

public function get_hawalas_by_currency($currency)
{
    return $this->db
        ->select('name, mark, balance')
        ->from('hawalas')
        ->where(['currency' => $currency, 'is_active' => 1])
        ->get()
        ->result();
}

public function get_all_hawalas()
{
    return $this->db
        ->select('name, mark, balance, currency')
        ->from('hawalas')
        ->where('is_active', 1)
        ->get()
        ->result();
}

    /**
     * Get monthly summary with comparison to previous month
     */
    public function get_monthly_summary_comparison($limit = 6)
    {
        $results = $this->db
            ->select("DATE_FORMAT(date, '%Y-%m') as month,
                    SUM(CASE WHEN type = 'Income' THEN birr ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'Expense' THEN birr ELSE 0 END) as total_expense")
            ->from('transactions')
            ->group_by("DATE_FORMAT(date, '%Y-%m')")
            ->order_by('month', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
            
        // Add percentage change calculations
        if(count($results) > 1) {
            for($i = 0; $i < count($results) - 1; $i++) {
                $current = $results[$i];
                $previous = $results[$i+1];
                
                $current->income_change = $previous->total_income != 0 
                    ? (($current->total_income - $previous->total_income) / $previous->total_income) * 100
                    : 0;
                    
                $current->expense_change = $previous->total_expense != 0 
                    ? (($current->total_expense - $previous->total_expense) / $previous->total_expense) * 100
                    : 0;
                    
                $current->net_change = ($previous->total_income - $previous->total_expense) != 0
                    ? (($current->total_income - $current->total_expense) - ($previous->total_income - $previous->total_expense)) / 
                      ($previous->total_income - $previous->total_expense) * 100
                    : 0;
            }
        }
        
        return $results;
    }

    /**
     * Get top staff by bank balance
     */
    public function get_top_staff_by_balance($limit = 5)
    {
        return $this->db
            ->select('staff.name as staff_name, staff_bank.balance, 
                     RANK() OVER (ORDER BY staff_bank.balance DESC) as rank')
            ->from('staff_bank')
            ->join('staff', 'staff.staff_id = staff_bank.staff_id')
            ->order_by('staff_bank.balance', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Get recent activities for timeline
     */
    public function get_recent_activities($limit = 5)
    {
        return $this->db
            ->select('transactions.*, staff.name as staff_name, staff_bank.name as bank_name,
                     TIMESTAMPDIFF(HOUR, transactions.date, NOW()) as hours_ago,
                     CASE 
                        WHEN TIMESTAMPDIFF(HOUR, transactions.date, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, transactions.date, NOW()), " hours ago")
                        ELSE CONCAT(TIMESTAMPDIFF(DAY, transactions.date, NOW()), " days ago")
                     END as time_ago')
            ->from('transactions')
            ->join('staff', 'staff.staff_id = transactions.staff_id', 'left')
            ->join('staff_bank', 'staff_bank.id = transactions.bank_id', 'left')
            ->order_by('transactions.date', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Get transaction heatmap data
     */
    public function get_transaction_heatmap_data($days = 30)
    {
        $start_date = date('Y-m-d', strtotime("-$days days"));
        
        $results = $this->db
            ->select("DATE(date) as day, 
                     HOUR(date) as hour,
                     COUNT(*) as count")
            ->from('transactions')
            ->where('date >=', $start_date)
            ->group_by('DAY(date), HOUR(date)')
            ->get()
            ->result();
            
        // Format for heatmap
        $heatmap_data = [];
        foreach($results as $row) {
            $heatmap_data[] = [
                'x' => date('D', strtotime($row->day)), // Day of week
                'y' => $row->hour . ':00', // Hour
                'v' => $row->count // Transaction count
            ];
        }
        
        return $heatmap_data;
    }

    /**
     * Count accounts per bank
     */
    public function count_bank_accounts($bank_name)
    {
        return $this->db
            ->where('name', $bank_name)
            ->from('staff_bank')
            ->count_all_results();
    }

    /**
     * Get staff with negative balances
     */
    public function get_negative_balance_staff()
    {
        return $this->db
            ->select('staff.name as staff_name, staff_bank.name as bank_name, staff_bank.balance')
            ->from('staff_bank')
            ->join('staff', 'staff.staff_id = staff_bank.staff_id')
            ->where('staff_bank.balance <', 0)
            ->order_by('staff_bank.balance', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Get largest recent transactions
     */
    public function get_largest_transactions($limit = 5)
    {
        return $this->db
            ->select('transactions.*, staff.name as staff_name, staff_bank.name as bank_name')
            ->from('transactions')
            ->join('staff', 'staff.staff_id = transactions.staff_id', 'left')
            ->join('staff_bank', 'staff_bank.id = transactions.bank_id', 'left')
            ->order_by('birr', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Get bank balance distribution
     */
    public function get_bank_distribution()
    {
        return $this->db
            ->select('name as bank_name, SUM(balance) as total_balance, COUNT(*) as account_count')
            ->from('staff_bank')
            ->group_by('name')
            ->order_by('total_balance', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get average balance per staff
     */
    public function get_average_balance_per_staff()
    {
        $result = $this->db
            ->select('AVG(balance) as avg_balance, COUNT(DISTINCT staff_id) as staff_count')
            ->from('staff_bank')
            ->get()
            ->row();
            
        return $result ? $result->avg_balance : 0;
    }
}

