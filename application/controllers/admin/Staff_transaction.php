<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Staff_transaction_model');
        $this->load->model('Hawala_staff_transaction_model');
        $this->load->model('Loan_transaction_model');
    }

    public function index()
    {
        if (!has_permission('view_staff_transaction')) {
            alert_error('Access Denied! You don\'t have permission to view transactions');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Staff Transactions',
            'transactions' => $this->Staff_transaction_model->get_all(),
        ];

        $this->template->render('admin/staff_transaction/list', $data);
    }

    public function view($id)
     {
    if (!has_permission('view_staff_transaction')) {
        alert_error('Access Denied!');
        redirect('admin/staff_transaction');
    }

    $transaction = $this->Staff_transaction_model->get_by_id($id);
    if (!$transaction) {
        alert_error('Transaction not found');
        redirect('admin/staff_transaction');
    }

    // Optionally get staff name
    if ($transaction->staff_id) {
        $staff = $this->db->get_where('staff', ['staff_id' => $transaction->staff_id])->row();
        $transaction->staff_name = $staff ? $staff->name : 'Unknown';
    }

    $this->template->render('admin/staff_transaction/view_transaction', [
        'title' => 'View Transaction',
        'transaction' => $transaction,
    ]);
    }

    public function income()
    {
        if (!has_permission('add_staff_transaction')) {
            alert_error('Access Denied! You don\'t have permission to add transactions');
            redirect('admin/staff_transaction');
        }

        $staffs = $this->Staff_transaction_model->get_all_staff();
        $banks = $this->Staff_transaction_model->get_all_banks();
        $data = [
            'title' => 'Add Staff Transaction',
            'staffs' => $staffs,
            'banks' => $banks,
        ];

        $this->template->render('admin/staff_transaction/staff_income', $data);
    }

    public function create($type = null)
    {
        if (!has_permission('add_staff_transaction')) {
            alert_error('Access Denied! You don\'t have permission to add transactions');
            redirect('admin/staff_transaction');
        }

            // Normalize type
        $type = ucfirst(strtolower($type));
        if (!in_array($type, ['Income', 'Expense'])) {
            $type = null; // fallback
        }
       // Check for POST and set validation rules
       if ($this->input->method() === 'post') {
        $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));

        $this->form_validation->set_rules('staff_id', 'Staff', 'required|numeric');
        $this->form_validation->set_rules('birr', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('type', 'Type', 'required|in_list[Income,Expense]');
        $this->form_validation->set_rules('bank_id', 'Bank Account', 'required|numeric');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post([
                    'staff_id', 'birr', 'type', 'bank_id', 'notes', 'date'
                ], TRUE);
                $post_data['transaction_type'] = 'staff';
                $post_data['created_by'] = $this->ion_auth->get_user_id();

                $this->Staff_transaction_model->insert($post_data);
                alert_success('Transaction added successfully.');
                redirect('admin/staff_transaction');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('Error occurred while saving transaction.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
       }
        $staffs = $this->Staff_transaction_model->get_all_staff();
        $banks = $this->Staff_transaction_model->get_all_banks();
        $data = [
            'title' => 'Add Staff Transaction',
            'staffs' => $staffs,
            'banks' => $banks,
            'forced_type' => $type, // pass to view
        ];

        $this->template->render('admin/staff_transaction/form', $data);
    }


    public function edit_route($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            alert_error('Invalid transaction ID. ' . $id);
            redirect('admin/staff');
        }

        $transaction = $this->db->where('id', $id)->get('transactions')->row();

        if (!$transaction) {
            alert_error('Unknown transaction type: ' . $transaction->transaction_type);
            redirect('admin/staff');
        }

        switch ($transaction->transaction_type) {
            case 'staff':
                redirect('admin/staff_transaction/edit/' . $id);
                break;
            case 'hawala_staff':
                redirect('admin/hawala_staff_transaction/edit/' . $id);
                break;
            case 'loan':
                redirect('admin/loan_transaction/edit/' . $id);
                break;
            default:
            alert_error('Unknown transaction type: ' . $transaction->transaction_type);
            redirect('admin/staff_transaction');
        }
    }
    
    public function edit($id)
    {
        if (!has_permission('edit_staff_transaction')) {
            alert_error('Access Denied! You don\'t have permission to edit transactions');
            redirect('admin/staff_transaction');
        }
    
        $transaction = $this->Staff_transaction_model->get_by_id($id);
        if (!$transaction) {
            alert_error('Transaction not found');
            redirect('admin/staff_transaction');
        }
    
        if ($this->input->method() === 'post') {
            $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));
    
            // Only validate editable fields
            $this->form_validation->set_rules('birr', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
            $this->form_validation->set_rules('date', 'Date', 'required');
    
            if ($this->form_validation->run() === TRUE) {
                try {
                    // Only get editable fields
                    $post_data = $this->input->post(['birr', 'notes', 'date'], TRUE);
    
                    // Force original locked values
                    $post_data['staff_id'] = $transaction->staff_id;
                    $post_data['bank_id'] = $transaction->bank_id;
                    $post_data['type'] = $transaction->type;
                    $post_data['transaction_type'] = $transaction->transaction_type;
                    $post_data['updated_by'] = $this->ion_auth->get_user_id();
    
                    $this->Staff_transaction_model->update($id, $post_data);
                    alert_success('Transaction updated successfully.');
                    redirect('admin/staff_transaction');
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                    alert_error('Error occurred while updating transaction.');
                }
            } else {
                alert_error(validation_errors('<div>', '</div>'));
            }
        }
    
        $staffs = $this->Staff_transaction_model->get_all_staff();
        $banks = $this->Staff_transaction_model->get_all_banks();
    
        $data = [
            'title' => 'Edit Staff Transaction',
            'transaction' => $transaction,
            'staffs' => $staffs,
            'banks' => $banks,
            'lock_fields' => true
        ];
    
        $this->template->render('admin/staff_transaction/form', $data);
    }
    

    public function delete($id)
{
    $this->output->set_content_type('application/json');

    try {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('Invalid transaction ID.');
        }

        // Check permission for deleting transactions (adjust permission checks as needed)
        if (!has_permission('delete_staff_transaction')) {
            throw new Exception('Access denied.');
        }

        $transaction = $this->db->where('id', $id)->get('transactions')->row();

        if (!$transaction) {
            throw new Exception('Transaction not found.');
        }

        $result = false;
        switch ($transaction->transaction_type) {
            case 'staff':
                if (!has_permission('delete_staff_transaction')) {
                    throw new Exception('Access denied.');
                }
                $result = $this->Staff_transaction_model->delete($id);
                break;
            case 'hawala_staff':
                if (!has_permission('delete_hawala_transaction')) {
                    throw new Exception('Access denied.');
                }
                $result = $this->Hawala_staff_transaction_model->delete($id);
                break;
            case 'loan':
                if (!has_permission('delete_loan_transaction')) {
                    throw new Exception('Access denied.');
                }
                $result = $this->Loan_transaction_model->delete($id);
                break;
            default:
            throw new Exception('Unknown transaction type: ' . $transaction->transaction_type);
    }

        if (!$result) {
            throw new Exception('Failed to delete transaction.');
        }

        echo json_encode([
            'success' => true,
            'message' => 'Transaction deleted successfully.',
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

}
