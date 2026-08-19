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

        $this->load->model('Hawala_model');
    }

    public function index()
    {
        if (!has_permission('view_hawala')) {
            alert_error('Access Denied! You don’t have permission to view hawala');
            redirect('dashboard');
        }
         $data = [
            'title' => 'Hawala Management',
            'hawalas' => $this->Hawala_model->get_all(),
            'currency_summary' => $this->Hawala_model->get_currency_summary(),
            'permissions' => [
                'add' => has_permission('hawala', 'add_hawala'),
                'edit' => has_permission('hawala', 'edit_hawala'),
                'delete' => has_permission('hawala', 'delete_hawala')
            ],
        ];

        $this->template->render('admin/hawala/list', $data);
    }
    
    public function view_hawala($id)
    {
        if (!$this->ion_auth->logged_in() || !has_permission('view_hawala')) {
            alert_error('Access Denied! You don’t have permission to view hawala');
            redirect('dashboard');
        }
    $data['title'] = 'View Hawala';
    $data['trans'] = $this->Hawala_model->get_by_id($id);
    $data['hawala'] = $this->Hawala_model->get($id);
    if (!$data['hawala']) {
        show_404();
    }

   
    $this->template->render('admin/hawala/view_hawala', $data);
   
}

     public function create()
    {
        if (!has_permission('add_hawala')) {
            alert_error('Access Denied! You don’t have permission to add hawala');
            return redirect('admin/hawala');
        }

        // Validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('mark', 'Mark', 'trim|required|is_unique[hawalas.mark]', [
            'is_unique' => 'This Mark is already in use. Please choose another.'
        ]);
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');
        $this->form_validation->set_rules('balance', 'Opening Balance', 'trim|numeric');

        if ($this->form_validation->run() === TRUE) {
            try {
                $post_data = $this->input->post(['name', 'mark', 'mobile', 'address', 'currency', 'balance', 'created_date'], TRUE);
                $post_data['balance'] = (float) $post_data['balance'];
                $post_data['is_active'] = ($this->input->post('is_active') == '1') ? 1 : 0;
                $post_data['created_by'] = $this->ion_auth->get_user_id();
                $post_data['created_date'] = $post_data['created_date']; // for transaction date 
                $this->Hawala_model->insert($post_data);

                alert_success('Hawala added successfully.');
                return redirect('admin/hawala');
            } catch (Exception $e) {
                log_message('error', 'Hawala Create Error: ' . $e->getMessage());
                alert_error('An unexpected error occurred while adding the Hawala.');
            }
        } elseif ($this->input->method() === 'post') {
            alert_error(validation_errors('<div>', '</div>'));
        }

        $data = [
            'title'    => 'Add New Hawala',
            'hawala'   => null,
            'currency' => $this->Hawala_model->get_all_currencies()
        ];
        $this->template->render('admin/hawala/form', $data);
    }

      public function edit($id = null)
{
    // 1. Permission Check
    if (!has_permission('edit_hawala')) {
        alert_error('Access Denied! You don’t have permission to edit hawala');
        return redirect('admin/hawala');
    }

    // 2. Validate ID
    if (!$id || !is_numeric($id)) {
        show_404();
    }

    // 3. Fetch Existing Record
    $hawala = $this->Hawala_model->get($id);
    if (!$hawala) {
        alert_error('Hawala not found');
        return redirect('admin/hawala');
    }

    // 4. Validation Rules
    $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
    $this->form_validation->set_rules('mark', 'Mark', 'trim|required|callback_check_mark_unique[' . $id . ']');
    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|regex_match[/^[0-9]{10,15}$/]');
    $this->form_validation->set_rules('currency', 'Currency', 'trim|required|max_length[100]');
    $this->form_validation->set_rules('address', 'Address', 'trim');
    $this->form_validation->set_rules('is_active', 'Active Status', 'trim|in_list[0,1]');

    // 5. Process Form
    if ($this->form_validation->run() === TRUE) {
        try {
            $post_data = $this->input->post(['name', 'mark', 'mobile', 'address', 'currency'], TRUE);
            $post_data['is_active'] = ($this->input->post('is_active') == '1') ? 1 : 0;
            $post_data['updated_by'] = $this->ion_auth->get_user_id();

            $this->Hawala_model->update($id, $post_data);
            alert_success('Hawala updated successfully.');
            return redirect('admin/hawala');
        } catch (Exception $e) {
            log_message('error', 'Hawala Update Error: ' . $e->getMessage());
            alert_error('Failed to update Hawala. This Mark may already exist.');
        }
    } elseif ($this->input->method() === 'post') {
        // Validation failed
        alert_error(validation_errors('<div>', '</div>'));
    }
    $currency = $this->Hawala_model->get_all_currencies();
    // 6. Load Edit View
    $data = [
        'title' => 'Edit Hawala',
        'hawala' => $hawala,
        'currency' => $currency,

    ];

    $this->template->render('admin/hawala/form', $data);
}

public function check_mark_unique_ajax()
{
    $mark = $this->input->post('mark', TRUE);
    $id = $this->input->post('id');
    $exists = $this->Hawala_model->is_mark_taken($mark, $id);

    echo json_encode([
        'exists' => $exists,
       
    ]);
}


public function check_mark_unique($mark, $id)
{
    $exists = $this->Hawala_model->is_mark_taken($mark, $id);
    if ($exists) {
        $this->form_validation->set_message('check_mark_unique', 'This Mark is already in use. Please choose another.');
        return FALSE;
    }
    return TRUE;
}

    
    public function delete($id)
    {
        $this->output->set_content_type('application/json');

        try {
            if (!has_permission('delete_hawala')) {
                throw new Exception('You do not have permission to perform this action');
            }

            $isSoftDelete = $this->input->post('soft_delete') == 1;

            if ($isSoftDelete) {
                $post_data['updated_by'] = $this->ion_auth->get_user_id();
                $result = $this->Hawala_model->softDelete($id, $post_data);
                $message = 'Hawala deactivated successfully';
            } else {
                $result = $this->Hawala_model->hardDelete($id);
                $message = 'Hawala permanently deleted';
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

    public function modal_form_open()
    {
        $data = [
            'title' => 'Add New Hawala',
            'hawala' => null
        ];
        $this->load->view('admin/hawala/form', $data);
    }


    // AJAX to fetch hawala members by currency
    public function get_hawalas_by_currency($currency)
    {
        // Only proceed if it's a POST with valid CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->load->model('Hawala_model');
            $hawalas = $this->Hawala_model->get_hawalas_by_currency($currency);
            echo json_encode($hawalas);
        } else {
            show_error('Invalid request method', 405);
        }
    }
    

}
