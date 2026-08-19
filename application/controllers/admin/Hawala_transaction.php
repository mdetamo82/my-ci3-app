<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Hawala_transaction_model');
        $this->load->model('Hawala_staff_transaction_model');
        $this->load->model('Hawala_transfer_model');
    }

    public function index()
    {
        if (!has_permission('hawala_transaction', 'view_hawala_transaction')) {
            alert_error('Access Denied!');
            redirect('dashboard');
        }

        $data = [
            'title' => 'Hawala Transactions',
            'transactions' => $this->Hawala_transaction_model->get_all(),
        ];
        $this->template->render('admin/hawala_transaction/list', $data);
    }

    public function create($type = null)
    {
        if (!has_permission('hawala_transaction', 'add_hawala_transaction')) {
            alert_error('Access Denied!');
            redirect('admin/hawala_transaction');
        }
         // Normalize type
         $type = ucfirst(strtolower($type));
         if (!in_array($type, ['Income', 'Expense'])) {
             $type = null; // fallback
         }
        // Check for POST and set validation rules
        if ($this->input->method() === 'post') {
        $_POST['amount'] = str_replace(',', '', $this->input->post('amount', TRUE));

        $this->form_validation->set_rules('hawala_id', 'Hawala Customer', 'required|numeric');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('type', 'Type', 'required|in_list[Income,Expense]');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('destination', 'Destination', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(null, true);
                $post_data['created_by'] = $this->ion_auth->get_user_id();

                $this->Hawala_transaction_model->insert($post_data);
                alert_success('Transaction added successfully');
                redirect('admin/hawala_transaction');
            } catch (Exception $e) {
                alert_error($e->getMessage());
            }
        }
    }
        $data = [
            'title' => 'Add Hawala Transaction',
            'hawalas' => $this->Hawala_transaction_model->get_all_hawalas(),
            'forced_type' => $type, // pass to view
        ];

        $this->template->render('admin/hawala_transaction/form', $data);
    }

    public function edit_route($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            alert_error('Invalid transaction ID. ' . $id);
            redirect('admin/hawala');
        }

        $transaction = $this->db->where('id', $id)->get('transactions')->row();

        if (!$transaction) {
            alert_error('Unknown transaction typeSS: ' . $transaction->transaction_type);
            redirect('admin/hawala');
        }

        switch ($transaction->transaction_type) {
            case 'hawala':
                redirect('admin/hawala_transaction/edit/' . $id);
                break;
                case 'hawala_staff':
                    redirect('admin/hawala_staff_transaction/edit/' . $id);
                    break;
                case 'hawala_transfer':
                    redirect('admin/hawala_transfer/edit/' . $id);
                    break;
            default:
            alert_error('Unknown transaction type: ' . $transaction->transaction_type);
            redirect('admin/hawala_transaction');
        }
    }

   public function edit($id)
{
    if (!has_permission('hawala_transaction', 'edit_hawala_transaction')) {
        alert_error('Access Denied!');
        redirect('admin/hawala_transaction');
    }

    $transaction = $this->Hawala_transaction_model->get_by_id($id);
    if (!$transaction) {
        alert_error('Transaction not found');
        redirect('admin/hawala_transaction');
    }

    if ($this->input->method() === 'post') {
        $_POST['amount'] = str_replace(',', '', $this->input->post('amount', TRUE));

        // Only validate editable fields
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');
        $this->form_validation->set_rules('destination', 'Destination', 'max_length[255]');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === TRUE) {
            try {
                // Only get editable fields
                $post_data = $this->input->post(['amount', 'notes', 'destination', 'date'], TRUE);

                // Force original locked values
                $post_data['type'] = $transaction->type;
                $post_data['hawala_id'] = $transaction->hawala_id;
                $post_data['updated_by'] = $this->ion_auth->get_user_id();

                $this->Hawala_transaction_model->update($id, $post_data);
                alert_success('Transaction updated successfully');
                redirect('admin/hawala_transaction');
            } catch (Exception $e) {
                alert_error($e->getMessage());
            }
        } else {
            alert_error(validation_errors('<div>', '</div>'));
        }
    }

    $data = [
        'title' => 'Edit Hawala Transaction',
        'transaction' => $transaction,
        'hawalas' => $this->Hawala_transaction_model->get_all_hawalas(), // for display only
        'lock_fields' => true
    ];

    $this->template->render('admin/hawala_transaction/form', $data);
}

public function delete($id)
{
    $this->output->set_content_type('application/json');

    try {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception('Invalid transaction ID.');
        }

        // Check permission for deleting transactions (adjust permission checks as needed)
        if (!has_permission('hawala_transaction', 'delete_hawala_transaction')) {
            throw new Exception('Access denied.');
        }

        $transaction = $this->db->where('id', $id)->get('transactions')->row();

        if (!$transaction) {
            throw new Exception('Transaction not found.');
        }

        $result = false;
        switch ($transaction->transaction_type) {
            case 'hawala':
                if (!has_permission('hawala_transaction', 'delete_hawala_transaction')) {
                    throw new Exception('Access denied.');
                }
                $result = $this->Hawala_transaction_model->delete($id);
                break;
                case 'hawala_transfer':
                    if (!has_permission('hawala_transaction', 'delete_hawala_transaction')) {
                        throw new Exception('Access denied.');
                    }
                    $result = $this->Hawala_transfer_model->delete($id);
                    break;
            case 'hawala_staff':
                if (!has_permission('staff_transaction', 'delete_staff_transaction')) {
                    throw new Exception('Access denied.');
                }
                $result = $this->Hawala_staff_transaction_model->delete($id);
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
