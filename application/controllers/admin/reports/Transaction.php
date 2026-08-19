<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        $this->load->model('Transaction_model');
        $this->load->helper(['form', 'url']);
    }

    public function index()
    {
        $filters = [
            'from' => $this->input->get('from'),
            'to' => $this->input->get('to'),
            'type' => $this->input->get('type'),
            'transaction_type' => $this->input->get('transaction_type'),
            'bank_id' => $this->input->get('bank_id'),
            'staff_id' => $this->input->get('staff_id'),
        ];

        $data = [
            'title' => 'Transaction Report',
            'filters' => $filters,
            'summary' => $this->Transaction_model->get_summary($filters),
            'transactions' => $this->Transaction_model->get_filtered($filters),
            'banks' => $this->Transaction_model->get_all_banks(),
            'staff' => $this->Transaction_model->get_all_staff(),
        ];

        $this->template->render('admin/reports/transactions', $data);
    }
}
