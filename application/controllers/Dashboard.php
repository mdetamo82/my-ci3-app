<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            // Core metrics
            'total_staff' => $this->Dashboard_model->get_total_staff(),
            'total_banks' => $this->Dashboard_model->get_total_banks(),
            'total_balance' => $this->Dashboard_model->get_total_balance(),
            
            // Transaction data
            'recent_transactions' => $this->Dashboard_model->get_recent_transactions(5),
            'largest_transactions' => $this->Dashboard_model->get_largest_transactions(5),
            'negative_balances' => $this->Dashboard_model->get_negative_balances(),
            
            // Time-based summaries
            'daily_summary' => $this->Dashboard_model->get_daily_summary(),
            'today_summary' => $this->Dashboard_model->get_today_balance_summary(),
            'weekly_summary' => $this->Dashboard_model->get_weekly_summary(),
            'monthly_summary' => $this->Dashboard_model->get_monthly_summary(),
            'monthly_comparison' => $this->Dashboard_model->get_monthly_summary_comparison(),
            
            // Bank data
            'bank_summary' => $this->Dashboard_model->get_bank_summary(),
            'bank_distribution' => $this->Dashboard_model->get_bank_distribution(),
            'staff_banks' => $this->Dashboard_model->get_staff_bank_details(),
            
            // Hawala/Currency data
            'currency_summary' => $this->Dashboard_model->get_currency_summary(),
            'hawalas_by_currency' => [], // will be handled via JS and AJAX if needed
            'all_hawalas' => $this->Dashboard_model->get_all_hawalas(),
            
            // Performance data
            'top_staff' => $this->Dashboard_model->get_top_staff_by_balance(5),
            'average_balance' => $this->Dashboard_model->get_average_balance_per_staff(),
            
            // Activity data
            'recent_activities' => $this->Dashboard_model->get_recent_activities(5),
            'transaction_heatmap' => $this->Dashboard_model->get_transaction_heatmap_data(),
        ];

        $this->template->render('dashboard', $data);
    }

    /**
     * AJAX endpoint for getting bank details
     */
    public function get_bank_details($bank_name)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $staff_banks = $this->Dashboard_model->get_staff_bank_details();
        $filtered = array_filter($staff_banks, function($item) use ($bank_name) {
            return $item->bank_name === $bank_name;
        });

        echo json_encode(array_values($filtered));
    }

    /**
     * AJAX endpoint for getting hawala by currency
     */
    public function get_hawalas($currency)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $hawalas = $this->Dashboard_model->get_hawalas_by_currency($currency);
        echo json_encode($hawalas);
    }
}