<?php
class Staff extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Staff_report_model');
    }
    
    public function index() {
        // Get filter values from POST or set defaults
        $filters = [
            'staff_id' => $this->input->post('staff_id') ?? '',
            'bank_name' => $this->input->post('bank_name') ?? '',
            'date_from' => $this->input->post('date_from') ?? date('Y-m-d'),
            'date_to' => $this->input->post('date_to') ?? date('Y-m-d')
        ];
        
        // Get data for dropdowns
        $data['staff_list'] = $this->Staff_report_model->get_all_staff();
        
        // Get banks based on selected staff (or all if no staff selected)
        if (!empty($filters['staff_id'])) {
            $data['bank_list'] = $this->Staff_report_model->get_staff_banks($filters['staff_id']);
        } else {
            $data['bank_list'] = $this->Staff_report_model->get_staff_banks();
        }
        
        // Get transactions based on filters
        $transactions = $this->Staff_report_model->get_transactions($filters);
        
        // Calculate totals
        $totals = $this->Staff_report_model->calculate_totals($transactions);
        
        // Prepare data for view
        $data['transactions'] = $transactions;
        $data['total_income'] = $totals['income'];
        $data['total_expense'] = $totals['expense'];
        $data['net_total'] = $totals['net'];
        $data['selected_staff'] = $filters['staff_id'];
        $data['selected_bank'] = $filters['bank_name'];
        $data['date_from'] = $filters['date_from'];
        $data['date_to'] = $filters['date_to'];
        
        $this->template->render('admin/reports/staff_reports', $data);
    }
    
    public function get_staff_banks() {
        $staff_id = $this->input->get('staff_id');
        $banks = $this->Staff_report_model->get_staff_banks($staff_id);
        echo json_encode($banks);
    }
    
    public function get_all_banks() {
        $banks = $this->Staff_report_model->get_staff_banks();
        echo json_encode($banks);
    }
    
    public function export($format) {
        // Get the same filters as the index method
        $filters = [
            'staff_id' => $this->input->post('staff_id') ?? '',
            'bank_name' => $this->input->post('bank_name') ?? '',
            'date_from' => $this->input->post('date_from') ?? date('Y-m-01'),
            'date_to' => $this->input->post('date_to') ?? date('Y-m-d')
        ];
        
        $transactions = $this->Staff_report_model->get_transactions($filters);
        
        switch($format) {
            case 'excel':
                $this->export_excel($transactions);
                break;
            case 'pdf':
                $this->export_pdf($transactions);
                break;
            default:
                redirect('staff_transaction');
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