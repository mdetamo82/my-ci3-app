<?php
class Loan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Loan_report_model');
    }
    
    public function index() {
        // Get filter values from POST or set defaults
        $filters = [
            'loan_id' => $this->input->post('loan_id') ?? '',
            'date_from' => $this->input->post('date_from') ?? date('Y-m-d'),
            'date_to' => $this->input->post('date_to') ?? date('Y-m-d')
        ];
        
        // Get data for dropdowns
        $data['loan_list'] = $this->Loan_report_model->get_all_loans();
        
        // Get transactions based on filters
        $transactions = $this->Loan_report_model->get_transactions($filters);
        
        // Calculate totals
        $totals = $this->Loan_report_model->calculate_totals($transactions);
        
        // Calculate running balance for each transaction
        $transactions = $this->Loan_report_model->calculate_running_balance($transactions);
        
        // Prepare data for view
        $data['transactions'] = $transactions;
        $data['total_income'] = $totals['income'];
        $data['total_expense'] = $totals['expense'];
        $data['net_total'] = $totals['net'];
        $data['selected_loan'] = $filters['loan_id'];
        $data['date_from'] = $filters['date_from'];
        $data['date_to'] = $filters['date_to'];
        
        $this->template->render('admin/reports/loan_reports', $data);
    }
    
    public function export($format) {
        // Get the same filters as the index method
        $filters = [
            'loan_id' => $this->input->post('loan_id') ?? '',
            'date_from' => $this->input->post('date_from') ?? date('Y-m-01'),
            'date_to' => $this->input->post('date_to') ?? date('Y-m-d')
        ];
        
        $transactions = $this->Loan_report_model->get_transactions($filters);
        $transactions = $this->Loan_report_model->calculate_running_balance($transactions);
        
        switch($format) {
            case 'excel':
                $this->export_excel($transactions);
                break;
            case 'pdf':
                $this->export_pdf($transactions);
                break;
            default:
                redirect('loan_transaction');
        }
    }
    
    private function export_excel($transactions) {
        // Excel export logic here
        // This would generate and force download an Excel file
    }
    
    private function export_pdf($transactions) {
        // PDF export logic here
        // This would generate and force download a PDF file
    }
}
?>