<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_bank extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Staff_bank_model');
    }

    /**
     * Display staff bank accounts list
     */
    public function index()
    {
        if (!has_permission('staff_bank', 'view_staff_bank')) {
            alert_error('Access Denied! You don\'t have permission to view staff bank accounts');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Staff Bank Accounts',
            'accounts' => $this->Staff_bank_model->get_all(),
            'permissions' => [
                'add' => has_permission('staff_bank', 'add_staff_bank'),
                'edit' => has_permission('staff_bank', 'edit_staff_bank'),
                'delete' => has_permission('staff_bank', 'delete_staff_bank')
            ],
        ];

        $this->template->render('admin/staff_bank/list', $data);
    }

    public function view($staff_id)
    {
        if (!has_permission('staff_bank', 'view_staff_bank')) {
            alert_error('Access Denied!');
            redirect('dashboard');
        }
    
        $this->load->model('Staff_model'); // Load your staff model
        $staff = $this->Staff_model->get($staff_id);
        
        if (!$staff) {
            show_404();
        }
    
        $data = [
            'title' => $staff->name . "'s Bank Accounts",
            'staff' => $staff,
            'accounts' => $this->Staff_bank_model->get_by_staff($staff_id),
            'total_balance' => $this->Staff_bank_model->get_total_balance($staff_id),
            'permissions' => [
                'add' => has_permission('staff_bank', 'add_staff_bank'),
                'edit' => has_permission('staff_bank', 'edit_staff_bank'),
                'delete' => has_permission('staff_bank', 'delete_staff_bank')
            ],
        ];
    
        $this->template->render('admin/staff_bank/view', $data);
    }

    /**
     * Create a new staff bank account
     */
    public function create()
    {
        if (!has_permission('staff_bank', 'add_staff_bank')) {
            alert_error('Access Denied! You don\'t have permission to add staff bank accounts');
            redirect('admin/staff_bank');
        }

        $this->form_validation->set_rules('staff_id', 'Staff', 'trim|required|numeric');
        $this->form_validation->set_rules('name', 'Bank Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('number', 'Account Number', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('balance', 'Balance', 'trim|required|numeric');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['staff_id', 'name', 'number', 'balance'], TRUE);
                $post_data['created_by'] = $this->ion_auth->get_user_id();

                $this->Staff_bank_model->insert($post_data);
                alert_success('Bank account added successfully.');
                redirect('admin/staff/view_staff/'. $this->input->post('staff_id', TRUE));
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An error occurred while adding bank account.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
        $staff_members = $this->Staff_bank_model->staff_members();
        $data = [
            'staff_members' => $staff_members,
            'title' => 'Add New Bank Account',
            'account' => null,
        ];

        $this->template->render('admin/staff_bank/form', $data);
    }

    /**
     * Edit an existing staff bank account
     */
    public function edit($id = null)
    {
        if (!has_permission('staff_bank', 'edit_staff_bank')) {
            alert_error('Access Denied! You don\'t have permission to edit staff bank accounts');
            redirect('admin/staff_bank');
        }

        if (!$id || !is_numeric($id)) {
            show_404();
        }

        $account = $this->Staff_bank_model->get($id);
        if (!$account) {
            alert_error('Error! Bank account not found');
            redirect('admin/staff_bank');
        }

        $this->form_validation->set_rules('staff_id', 'Staff', 'trim|required|numeric');
        $this->form_validation->set_rules('name', 'Bank Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('number', 'Account Number', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('balance', 'Balance', 'trim|required|numeric');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['staff_id', 'name', 'number', 'balance'], TRUE);
                $post_data['updated_by'] = $this->ion_auth->get_user_id();

                $this->Staff_bank_model->update($id, $post_data);
                alert_success('Bank account updated successfully.');
                redirect('admin/staff_bank');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An internal error occurred. Please try again.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
        $staff_members = $this->Staff_bank_model->staff_members();
        $data = [
            'title' => 'Edit Bank Account',
            'account' => $account,
            'staff_members' => $staff_members,
        ];

        $this->template->render('admin/staff_bank/form', $data);
    }

    /**
     * Delete a staff bank account via AJAX
     */
    public function delete($id)
    {
        $this->output->set_content_type('application/json');

        try {
            if (!has_permission('staff_bank', 'delete_staff_bank')) {
                throw new Exception('You do not have permission to perform this action');
            }

            $result = $this->Staff_bank_model->hardDelete($id);

            if (!$result) {
                throw new Exception('Database operation failed');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Bank account deleted successfully',
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
     * Load the staff bank form modal
     */
    public function modal_form_open()
    {
        $data = [
            'title' => 'Add New Bank Account',
            'account' => null
        ];
        $this->load->view('admin/staff_bank/form', $data);
    }
}