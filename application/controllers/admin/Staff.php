<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Staff_model');
        $this->load->model('Staff_transaction_model');
    }

    /**
     * Display staff list
     */
    public function index()
{
    if (!has_permission('view_staff')) {
        alert_error('Access Denied! You don\'t have permission to view staff');
        redirect('dashboard');
    }

    // Step 1: Get all staff
    $staffs = $this->Staff_model->get_all();

    // Step 2: Get balance per staff as a map: [staff_id => balance]
    $balances = $this->Staff_model->get_all_staff_balances_as_map();

    // Step 3: Attach balance to each staff member
    foreach ($staffs as &$s) {
        $s->balance = isset($balances[$s->staff_id]) ? $balances[$s->staff_id] : 0.00;
    }

    // Step 4: Prepare data
    $data = [
        'title' => 'Staff Management',
        'staff' => $staffs,
        'grand_total' => $this->Staff_model->get_all_staff_total_balance(),
        'permissions' => [
            'add' => has_permission('staff', 'add_staff'),
            'edit' => has_permission('staff', 'edit_staff'),
            'delete' => has_permission('staff', 'delete_staff')
        ],
    ];

    // Step 5: Render
    $this->template->render('admin/staff/list', $data);
}

     /**
     * view staff
     */
    public function view_staff($id= null)
    {
        if (!$this->ion_auth->logged_in() || !has_permission('view_staff')) {
            alert_error('Access Denied! You don’t have permission to view staff');
            redirect('dashboard');
        }
        if (!$id || !is_numeric($id)) {
            alert_error('Invalid staff ID');
            redirect('admin/staff'); // Redirect to the staff listing page
        }
       
        $data['title'] = 'View Staff';
        $data['staff'] = $this->Staff_model->get($id);
        $data['trans'] = $this->Staff_model->get_by_id($id);
        $data['staff_total'] = $this->Staff_model->get_staff_total_balance($id);
        $data['trans'] = $this->Staff_transaction_model->get_by_staff_id($id);

        if (!$data['staff']) {
            alert_error('Error! Staff not found');
            redirect('admin/staff');
        }

   
    $this->template->render('admin/staff/view_staff', $data);
   
    }

    /**
     * Create a new staff member
     */
    public function create()
    {
        if (!has_permission('add_staff')) {
            alert_error('Access Denied! You don\'t have permission to add staff');
            redirect('admin/staff');
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('department', 'Department', 'trim|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['name', 'mobile', 'address', 'department'], TRUE);
                $post_data['is_active'] = ($this->input->post('is_active') === '1') ? 1 : 0;
                $post_data['created_by'] = $this->ion_auth->get_user_id();

                $this->Staff_model->insert($post_data);
                alert_success('Staff added successfully.');
                redirect('admin/staff');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An error occurred while adding staff.');
            }
        } elseif ($this->input->method() === 'post') {
            // Show validation errors on POST
            alert_error(validation_errors('<div>', '</div>'));
        }

        $data = [
            'title' => 'Add New Staff',
            'staff' => null,
        ];

        // Load  form view
        $this->template->render('admin/staff/form', $data);
    }

    public function create_bank()
    {
        if (!has_permission('add_staff_bank')) {
            alert_error('Access Denied! You don\'t have permission to add staff bank accounts');
            redirect('admin/staff_bank');
        }

        $this->form_validation->set_rules('staff_id', 'Staff', 'trim|required|numeric');
        $this->form_validation->set_rules('name', 'Bank Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('number', 'Account Number', 'trim|required|max_length[100]');
        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['staff_id', 'name', 'number'], TRUE);
                $post_data['created_by'] = $this->ion_auth->get_user_id();

                $this->Staff_model->insert_bank($post_data);
                alert_success('Bank account added successfully.');
                redirect('admin/staff/view_staff/'. $this->input->post('staff_id', TRUE));
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An error occurred while adding bank account.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
       
        $data = [
          
            'title' => 'Add New Bank Account',
            'account' => null,
        ];

        $this->template->render('admin/staff_bank/form', $data);
    }

    /**
     * Edit an existing staff member
     */
    public function edit($id = null)
    {
        if (!has_permission('edit_staff')) {
            alert_error('Access Denied! You don\'t have permission to edit staff');
            redirect('admin/staff');
        }

        if (!$id || !is_numeric($id)) {
            alert_error('Invalid staff ID');
            redirect('admin/staff'); // Redirect to the staff listing page
        }

        $staff = $this->Staff_model->get($id);
        if (!$staff) {
            alert_error('Error! Staff not found');
            redirect('admin/staff');
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('department', 'Department', 'trim|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['name', 'mobile', 'address', 'department'], TRUE);
                $post_data['is_active'] = ($this->input->post('is_active') === '1') ? 1 : 0;
                $post_data['updated_by'] = $this->ion_auth->get_user_id();

                $this->Staff_model->update($id, $post_data);
                alert_success('Staff updated successfully.');
                redirect('admin/staff');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An internal error occurred. Please try again.');
            }
        } elseif ($this->input->method() === 'post') {
            // Validation failed
            alert_error(validation_errors('<div>', '</div>'));
        }

        $data = [
            'title' => 'Edit Staff',
            'staff' => $staff,
        ];

        // Load  form view
        $this->template->render('admin/staff/form', $data);
    }

     /**
     * Delete a staff member via AJAX
     */
    public function delete($id)
{
    $this->output->set_content_type('application/json'); // Set content type first

    try {
        if (!has_permission('delete_staff')) {
            throw new Exception('You do not have permission to perform this action');
        }

        $isSoftDelete = $this->input->post('soft_delete') == 1;
        
        if ($isSoftDelete) {
            $post_data['updated_by'] = $this->ion_auth->get_user_id();
            $result = $this->Staff_model->softDelete($id, $post_data);
            $message = 'Staff member deactivated successfully';
        } else {
            $result = $this->Staff_model->hardDelete($id);
            $message = 'Staff member permanently deleted';
        }

        if (!$result) {
            throw new Exception('Database operation failed');
        }

        echo json_encode([
            'success' => true,  // Changed from 'success' to boolean true
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
     * Load the staff form modal from add bank button
     */
    public function modal_form_open()
    {
        $staff_id = $this->input->get('id', TRUE); // Securely get the staff ID
    
        if (!$staff_id) {
            alert_error('Invalid staff ID');
            redirect('admin/staff'); // Redirect to the staff listing page // Prevent access without an ID
        }
    
        $data['staff_id'] = $staff_id; // Pass ID to the view
        $this->load->view('admin/staff/bank_form', $data); // Only load modal content
    }

    // Get banks for specific staff (for AJAX)
public function get_staff_banks() {
    $staff_id = $this->input->get('staff_id');
    
    $this->db->select('name as bank_name');
    $this->db->where('staff_id', $staff_id);
    $this->db->where('name IS NOT NULL');
    $this->db->where('TRIM(name) !=', '');
    $banks = $this->db->get('staff_bank')->result();
    
    echo json_encode($banks);
}

    public function get_banks_by_staff($staff_id)
    {
        // Optional: Auth check
        if (!$this->ion_auth->logged_in()) {
            return show_error('Unauthorized', 401);
        }
    
        $this->db->where('staff_id', $staff_id);
        $banks = $this->db->get('staff_bank')->result();
    
        // Map and format the response
        $formatted = array_map(function($bank) {
            return [
                'id' => $bank->id, // or bank_id if your column name is different
                'name' => $bank->name, // or use 'bank' if that’s the column name
                'number' => $bank->number, // adapt to your column name
                'balance' => $bank->balance
            ];
        }, $banks);
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($formatted));
    }
    
}
