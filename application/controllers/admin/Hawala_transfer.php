<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_transfer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

        $this->load->model('Hawala_transfer_model');
        $this->load->helper(['form']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!has_permission('view_hawala_transaction')) {
            alert_error('Access Denied!');
            redirect('dashboard');
        }

        $data = [
            'title'     => 'Hawala Transfers',
            'transfers' => $this->Hawala_transfer_model->get_all(),
        ];
        $this->template->render('admin/hawala_transfer/list', $data);
    }

    public function create()
    {
        if (!has_permission('add_hawala_transaction')) {
            alert_error('Access Denied!');
            redirect('admin/hawala_transfer');
        }

        if ($this->input->method() === 'post') {
            // Clean and prepare amounts
            $_POST['amount_from'] = str_replace(',', '', $this->input->post('amount_from', TRUE));
            $_POST['amount_to'] = str_replace(',', '', $this->input->post('amount_to', TRUE));

            $this->form_validation->set_rules('from_id', 'From Hawala', 'required|numeric');
            $this->form_validation->set_rules('to_id', 'To Hawala', 'required|numeric|callback_check_different_ids');
            $this->form_validation->set_rules('amount_from', 'From Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('amount_to', 'To Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');

            if ($this->form_validation->run() === TRUE) {
                try {
                    $post_data = $this->input->post(null, true);
                    $post_data['created_by'] = $this->ion_auth->get_user_id();

                    $this->Hawala_transfer_model->insert($post_data);
                    alert_success('Transfer created successfully.');
                    redirect('admin/hawala_transfer');
                } catch (Exception $e) {
                    alert_error($e->getMessage());
                }
            }
        }

        $data = [
            'title'   => 'Add Hawala Transfer',
            'hawalas' => $this->Hawala_transfer_model->get_all_hawalas(),
        ];
        $this->template->render('admin/hawala_transfer/form', $data);
    }

    public function edit($id = null)
    {
        if (!has_permission('edit_hawala_transaction')) {
            alert_error('Access Denied!');
            redirect('admin/hawala_transfer');
        }

        $transfer = $this->Hawala_transfer_model->get_by_id($id);
        if (!$transfer) {
            alert_error('Transfer not found');
            redirect('admin/hawala_transfer');
        }

        if ($this->input->method() === 'post') {
            // Clean and prepare amounts
            $_POST['amount_from'] = str_replace(',', '', $this->input->post('amount_from', TRUE));
            $_POST['amount_to'] = str_replace(',', '', $this->input->post('amount_to', TRUE));

            $this->form_validation->set_rules('amount_from', 'From Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('amount_to', 'To Amount', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('notes', 'Notes', 'max_length[255]');

            if ($this->form_validation->run() === TRUE) {
                try {
                    $post_data = $this->input->post(['amount_from', 'amount_to', 'date', 'notes', 'rate'], true);
                    $post_data['updated_by'] = $this->ion_auth->get_user_id();

                    $this->Hawala_transfer_model->update($transfer->transfer_group, $post_data);
                    alert_success('Transfer updated successfully.');
                    redirect('admin/hawala_transfer');
                } catch (Exception $e) {
                    alert_error($e->getMessage());
                }
            }
        }

        $data = [
            'title' => 'Edit Hawala Transfer',
            'transfer' => $transfer,
            'hawalas' => $this->Hawala_transfer_model->get_all_hawalas(),
            'lock_fields' => true
        ];
        $this->template->render('admin/hawala_transfer/form', $data);
    }

    public function check_different_ids($to_id)
    {
        $from_id = $this->input->post('from_id');
        if ($from_id == $to_id) {
            $this->form_validation->set_message('check_different_ids', 'From and To Hawala cannot be the same.');
            return false;
        }
        return true;
    }

    public function delete($id = null)
    {
        $this->output->set_content_type('application/json');

        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception('Invalid transfer ID.');
            }

            if (!has_permission('delete_hawala_transaction')) {
                throw new Exception('Access denied.');
            }

            $result = $this->Hawala_transfer_model->delete($id);

            if (!$result) {
                throw new Exception('Failed to delete transfer.');
            }

            echo json_encode([
                'success' => true,
                'message' => 'Transfer deleted successfully.',
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