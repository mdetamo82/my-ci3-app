<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_staff_transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Hawala_staff_transaction_model');
    }

    public function index()
    {
        if (!has_permission('hawala_transaction', 'view_hawala_transaction')) {
            alert_error('Access Denied! You don\'t have permission to view transactions');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Hawala Transactions',
            'transactions' => $this->Hawala_staff_transaction_model->get_all(),
        ];

        $this->template->render('admin/hawala_staff_transaction/list', $data);
    }

    public function create($type = null)
    {
        if (!has_permission('hawala_transaction', 'add_hawala_transaction')) {
            alert_error('Access Denied! You don\'t have permission to add transactions');
            redirect('admin/hawala_staff_transaction');
        }
        // Normalize type
        $type = ucfirst(strtolower($type));
        if (!in_array($type, ['Income', 'Expense'])) {
            $type = null; // fallback
        }
        // Check for POST and set validation rules
    if ($this->input->method() === 'post') {
        $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));
        $_POST['amount'] = str_replace(',', '', $this->input->post('amount', TRUE));

        $this->form_validation->set_rules('staff_id', 'Staff', 'required|numeric');
        $this->form_validation->set_rules('hawala_id', 'Hawala Customer', 'required|numeric');
        $this->form_validation->set_rules('bank_id', 'Bank Account', 'required|integer');
        $this->form_validation->set_rules('birr', 'Birr', 'required|numeric');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('rate', 'Rate', 'required|numeric');
        $this->form_validation->set_rules('type', 'Type', 'required|in_list[Income,Expense]');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
                    // Get hawala currency AFTER validation (safer)
                $hawala_id = $this->input->post('hawala_id', TRUE);
                $hawala_info = $this->db->where('hawala_id', $hawala_id)->get('hawalas')->row();

                if (!$hawala_info) {
                    alert_error('Invalid Hawala customer selected.');
                    redirect(current_url());
                }

                $post_data = $this->input->post([
                    'staff_id', 'bank_id', 'hawala_id', 'birr', 'amount', 'rate',  'type', 'notes', 'date'
                ], TRUE);
                $post_data['transaction_type'] = 'hawala';
                $post_data['created_by'] = $this->ion_auth->get_user_id();
                $post_data['currency'] = $hawala_info->currency; // Overwrite with DB value for consistency
               
                $this->Hawala_staff_transaction_model->insert($post_data);
                alert_success('Transaction added successfully.');
                redirect('admin/hawala_staff_transaction');
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
                alert_error('Error occurred while saving transaction.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }
    }
        $data = [
            'title' => 'Add Hawala Transaction',
            'staffs' => $this->Hawala_staff_transaction_model->get_all_staff(),
            'hawalas' => $this->Hawala_staff_transaction_model->get_all_hawalas(),
            'banks' => $this->Hawala_staff_transaction_model->get_all_banks(),
            'forced_type' => $type, // pass to view
        ];

        $this->template->render('admin/hawala_staff_transaction/form', $data);
    }

    public function edit($id)
    {
        if (!has_permission('hawala_transaction', 'edit_hawala_transaction')) {
            alert_error('Access Denied! You don\'t have permission to edit transactions');
            redirect('admin/hawala_staff_transaction');
        }
    
        $transaction = $this->Hawala_staff_transaction_model->get_by_id($id);
        if (!$transaction) {
            alert_error('Transaction not found');
            redirect('admin/hawala_staff_transaction');
        }
    
        if ($this->input->method() === 'post') {
            $_POST['birr'] = str_replace(',', '', $this->input->post('birr', TRUE));
            $_POST['amount'] = str_replace(',', '', $this->input->post('amount', TRUE));
    
            $this->form_validation->set_rules('birr', 'Birr', 'required|numeric');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('rate', 'Rate', 'required|numeric');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
            $this->form_validation->set_rules('date', 'Date', 'required');
    
            if ($this->form_validation->run() === TRUE) {
                try {
                    $post_data = $this->input->post([
                        'birr', 'amount', 'rate', 'notes', 'date'
                    ], TRUE);
    
                    // Use locked values from the original transaction
                    $post_data['staff_id'] = $transaction->staff_id;
                    $post_data['hawala_id'] = $transaction->hawala_id;
                    $post_data['bank_id'] = $transaction->bank_id;
                    $post_data['type'] = $transaction->type;
                    $post_data['transaction_type'] = $transaction->transaction_type;
                    $post_data['updated_by'] = $this->ion_auth->get_user_id();
    
                    // Get currency from locked hawala_id
                    $hawala_info = $this->db
                        ->where('hawala_id', $transaction->hawala_id)
                        ->get('hawalas')->row();
    
                    if (!$hawala_info) {
                        throw new Exception('Invalid Hawala selected.');
                    }
    
                    $post_data['currency'] = $hawala_info->currency;
    
                    $this->Hawala_staff_transaction_model->update($id, $post_data);
                    alert_success('Transaction updated successfully.');
                    redirect('admin/hawala_staff_transaction');
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                    alert_error('Error occurred while updating transaction.');
                }
            } else {
                alert_error(validation_errors('<div>', '</div>'));
            }
        }
    
        $data = [
            'title' => 'Edit Hawala Transaction',
            'transaction' => $transaction,
            'staffs' => $this->Hawala_staff_transaction_model->get_all_staff(),
            'hawalas' => $this->Hawala_staff_transaction_model->get_all_hawalas(),
            'lock_fields' => true
        ];
    
        $this->template->render('admin/hawala_staff_transaction/form', $data);
    }
    

    public function delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('Invalid transaction ID.');
        }
        $this->output->set_content_type('application/json');

        try {
            if (!has_permission('hawala_transaction', 'delete_hawala_transaction')) {
                throw new Exception('Access denied.');
            }

            $result = $this->Hawala_staff_transaction_model->delete($id);
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
