<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Loan_transaction_model');
    }

    public function index()
    {
        if (!has_permission('loan_transaction', 'view_loan_transaction')) {
            alert_error('Access Denied! You don\'t have permission to view transactions');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Loan Transactions',
            'transactions' => $this->Loan_transaction_model->get_all(),
        ];

        $this->template->render('admin/loan_transaction/list', $data);
    }

    public function create($type = null)
    {
        if (!has_permission('loan_transaction', 'add_loan_transaction')) {
            alert_error('Access Denied! You don\'t have permission to add transactions');
            redirect('admin/loan_transaction');
        }
        // Normalize type
        $type = ucfirst(strtolower($type));
        if (!in_array($type, ['Income', 'Expense'])) {
            $type = null; // fallback
        }
        if ($this->input->method() === 'post') {
            $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));

        $this->form_validation->set_rules('staff_id', 'Staff', 'required|numeric');
        $this->form_validation->set_rules('loan_id', 'Loan Customer', 'required|numeric');
        $this->form_validation->set_rules('bank_id', 'Bank Account', 'required|integer');
        $this->form_validation->set_rules('birr', 'Birr', 'required|numeric');
        $this->form_validation->set_rules('type', 'Type', 'required|in_list[Income,Expense]');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
               
                $post_data = $this->input->post([
                    'staff_id', 'bank_id', 'loan_id', 'birr', 'type', 'notes', 'date'
                ], TRUE);
                $post_data['transaction_type'] = 'loan';
                $post_data['created_by'] = $this->ion_auth->get_user_id();
                $this->Loan_transaction_model->insert($post_data);
                alert_success('Transaction added successfully.');
                redirect('admin/loan_transaction');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('Error occurred while saving transaction.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
    }
        $data = [
            'title' => 'Add Loan Transaction',
            'staffs' => $this->Loan_transaction_model->get_all_staff(),
            'loans' => $this->Loan_transaction_model->get_all_loans(),
            'forced_type' => $type, // pass to view
        ];

        $this->template->render('admin/loan_transaction/form', $data);
    }

   public function edit($id)
{
    // Permission check
    if (!has_permission('loan_transaction', 'edit_loan_transaction')) {
        alert_error("Access Denied! You don't have permission to edit transactions");
        return redirect('admin/loan_transaction');
    }

    // Fetch transaction
    $transaction = $this->Loan_transaction_model->get_by_id($id);
    if (!$transaction) {
        alert_error('Transaction not found');
        return redirect('admin/loan_transaction');
    }

    if ($this->input->method() === 'post') {
        $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));

        // Only validate editable fields
        $this->form_validation->set_rules('birr', 'Birr', 'required|numeric');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
                // Only retrieve editable fields
                $post_data = $this->input->post(['birr', 'notes', 'date'], TRUE);

                // Force locked values from DB
                $post_data['staff_id'] = $transaction->staff_id;
                $post_data['loan_id'] = $transaction->loan_id;
                $post_data['bank_id'] = $transaction->bank_id;
                $post_data['type'] = $transaction->type;
                $post_data['transaction_type'] = $transaction->transaction_type;
                $post_data['updated_by'] = $this->ion_auth->get_user_id();

                $this->Loan_transaction_model->update($id, $post_data);
                alert_success('Transaction updated successfully.');
                return redirect('admin/loan_transaction');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('An error occurred while updating the transaction.');
            }
        } else {
            alert_error(validation_errors('<div>', '</div>'));
        }
    }

    // Prepare view data
    $data = [
        'title' => 'Edit Loan Transaction',
        'transaction' => $transaction,
        'staffs' => $this->Loan_transaction_model->get_all_staff(),
        'loans' => $this->Loan_transaction_model->get_all_loans()
    ];

    $this->template->render('admin/loan_transaction/form', $data);
}



    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('Invalid transaction ID.');
        }
        $this->output->set_content_type('application/json');

        try {
            if (!has_permission('loan_transaction', 'delete_loan_transaction')) {
                throw new Exception('Access denied.');
            }

            $result = $this->Loan_transaction_model->delete($id);
            if (!$result) {
                throw new Exception('Failed to delete transaction.');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Transaction deleted successfully',
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
