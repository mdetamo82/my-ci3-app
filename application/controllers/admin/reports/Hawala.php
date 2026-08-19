<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Hawala_report_model');
    }

    public function index()
    {
        if (!has_permission('view_hawala_transaction')) {
            alert_error('Access Denied! You don\'t have permission to view transactions');
            redirect('dashboard');
        }

        $data = [];
        if ($this->input->method() === 'post') {
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');
            $hawala_id = $this->input->post('hawala_id');

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['hawala_id'] = $hawala_id;

            $data['hawala_details'] = $this->Hawala_report_model->get_hawala_details($hawala_id);
            $transactions = $this->Hawala_report_model->get_transactions($start_date, $end_date, $hawala_id);
            $previous_balance = $this->Hawala_report_model->get_previous_balance($start_date, $hawala_id);

            $running_balance = $previous_balance;
            $report_data = [];

            foreach ($transactions as $txn) {
                // Correct running balance update (staff perspective)
                $running_balance += ($txn->debit - $txn->credit);

                $report_data[] = [
                    'notes' => $txn->notes,
                    'date' => $txn->date,
                    'hawala_name' => $txn->hawala_name ?? $txn->mark,  // Adjust field name as per your model
                    'description' => $txn->description ?? '',
                    'birr' => $txn->birr,
                    'rate' => $txn->rate,
                    'credit' => $txn->credit,
                    'debit' => $txn->debit,
                    'currency' => $txn->currency,
                    'balance' => $running_balance
                ];
            }

            $data['report_data'] = $report_data;
        }

        $data['hawalas'] = $this->Hawala_report_model->get_all_hawalas();

        $this->template->render('admin/hawala_report_view', $data);
    }
}
