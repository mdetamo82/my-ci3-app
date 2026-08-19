<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Loan_model');
        $this->load->model('Staff_model');
    }

    /**
     * Display loan list
     */
    public function index()
    {
        if (!has_permission('view_loan')) {
            alert_error('Access Denied! You don\'t have permission to view loans');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Loan Management',
            'loans' => $this->Loan_model->get_all(),
            'permissions' => [
                'add' => has_permission('loan', 'add_loan'),
                'edit' => has_permission('loan', 'edit_loan'),
                'delete' => has_permission('loan', 'delete_loan')
            ],
        ];

        $this->template->render('admin/loan/list', $data);
    }

     /**
     * view loan
     */
    public function view_loan($id= null)
    {
        if (!$this->ion_auth->logged_in() || !has_permission('view_loan')) {
            alert_error('Access Denied! You don’t have permission to view loan');
            redirect('dashboard');
        }
        if (!$id || !is_numeric($id)) {
            alert_error('Invalid loan ID');
            redirect('admin/loan'); // Redirect to the staff listing page
        }
        $trans = $this->Loan_model->get_by_id($id);
        $staff = $this->Staff_model->get($id);
        $loan = $this->Loan_model->get($id);
        if (!$loan) {
            alert_error('Error! Loan not found');
            redirect('admin/loan');
        }    
    $data['title'] = 'View Loan';
    $data['loan'] = $this->Loan_model->get($id);
    $data['staff'] = $this->Staff_model->get($id);
    $data['trans'] = $this->Loan_model->get_by_id($id);
    if (!$data['loan']) {
        alert_error('Error! Loan not found');
        redirect('admin/loan');
    }

   
    $this->template->render('admin/loan/view_loan', $data);
   
    }

    /**
     * Create a new loan
     */
    public function create()
    {
        if (!has_permission('add_loan')) {
            alert_error('Access Denied! You don\'t have permission to add loans');
            redirect('admin/loan');
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('balance', 'Balance', 'trim|required|numeric');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['name', 'mobile', 'address', 'balance'], TRUE);
                $post_data['is_active'] = ($this->input->post('is_active') === '1') ? 1 : 0;
                $post_data['created_by'] = $this->ion_auth->get_user_id();
                $post_data['created_at'] = $this->input->post('created_date', TRUE) ?? date('Y-m-d H:i:s');
                $this->Loan_model->insert($post_data);
                alert_success('Loan added successfully.');
                redirect('admin/loan');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An error occurred while adding loan.');
            }
        } elseif ($this->input->method() === 'post') {
            // Show validation errors on POST
            alert_error(validation_errors('<div>', '</div>'));
        }

        $data = [
            'title' => 'Add New Loan',
            'loan' => null,
        ];

        // Load  form view
        $this->template->render('admin/loan/form', $data);
    }

    /**
     * Edit an existing loan
     */
    public function edit($id = null)
    {
        if (!has_permission('edit_loan')) {
            alert_error('Access Denied! You don\'t have permission to edit loans');
            redirect('admin/loan');
        }

        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $loan = $this->Loan_model->get($id);
        if (!$loan) {
            alert_error('Error! Loan not found');
            redirect('admin/loan');
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('balance', 'Balance', 'trim|required|numeric');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['name', 'mobile', 'address', 'balance'], TRUE);
                $post_data['is_active'] = ($this->input->post('is_active') === '1') ? 1 : 0;
                $post_data['updated_by'] = $this->ion_auth->get_user_id();

                $this->Loan_model->update($id, $post_data);
                alert_success('Loan updated successfully.');
                redirect('admin/loan');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An internal error occurred. Please try again.');
            }
        } elseif ($this->input->method() === 'post') {
            // Validation failed
            alert_error(validation_errors('<div>', '</div>'));
        }

        $data = [
            'title' => 'Edit Loan',
            'loan' => $loan,
        ];

        // Load  form view
        $this->template->render('admin/loan/form', $data);
    }

    /**
     * Delete a loan member via AJAX
     */
    public function delete($id)
    {
        $this->output->set_content_type('application/json'); // Set content type first

        try {
            if (!has_permission('delete_loan')) {
                throw new Exception('You do not have permission to perform this action');
            }

            $isSoftDelete = $this->input->post('soft_delete') == 1;
            
            if ($isSoftDelete) {
                $post_data['updated_by'] = $this->ion_auth->get_user_id();
                $result = $this->Loan_model->softDelete($id, $post_data);
                $message = 'Loan deactivated successfully';
            } else {
                $result = $this->Loan_model->hardDelete($id);
                $message = 'Loan permanently deleted';
            }

            if (!$result) {
                throw new Exception('Database operation failed');
            }

            echo json_encode([
                'success' => true,
                'message' => $message,
                'requires_refresh' => $isSoftDelete,
                'csrf_token' => $this->security->get_csrf_hash()
            ]);

        } catch (Exception $e) {
            $this->output->set_status_header(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }

    /**
     * Load the loan form modal from add loan button
     */
    public function modal_form_open()
    {
        $data = [
            'title' => 'Add New Loan',
            'loan' => null
        ];
       // Load modal form view
        $this->load->view('admin/loan/form', $data);
    }
}
