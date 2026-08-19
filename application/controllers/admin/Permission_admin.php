<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permission_model');
        $this->load->model('ion_auth_model');
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            show_error('You must be an admin to view this page.');
        }
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			show_error('You must be an administrator to view this page.');
		}
        $data['permissions'] = $this->Permission_model->get_all_permissions();
        $data['groups'] = $this->ion_auth_model->groups()->result();

        foreach ($data['groups'] as &$group) {
            $group->assigned_permissions = $this->Permission_model->get_permissions_by_group($group->id);
        }
        $this->template->render('admin/permission_management', $data);
    }

    public function save()
{
    // Set JSON response header
    $this->output->set_content_type('application/json');

    try {
        // Validate input
        $group_id = $this->input->post('group_id', true);
        $permission_ids = $this->input->post('permission_ids', true) ?? [];

        if (empty($group_id)) {
            throw new Exception('Group ID is required');
        }

        // Update permissions
        $this->Permission_model->update_group_permissions($group_id, $permission_ids);

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Permissions updated successfully',
            'data' => [
                'group_id' => $group_id,
                'permission_count' => count($permission_ids)
            ]
        ]);
    } catch (Exception $e) {
        // Log the error
        log_message('error', 'Permission save error: ' . $e->getMessage());
        
        // Return error response
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'error_code' => 400
        ]);
    }
}

public function syncs() {
    try {
        // Logic for syncing permissions

        // Simulating sync success
        $response = ['success' => true, 'message' => 'Permissions synchronized successfully.'];

    } catch (Exception $e) {
        // If there is an exception or error
        $response = ['success' => false, 'message' => 'An error occurred during sync.'];
    }
    echo json_encode($response);
    exit;
}


    public function sync()
    {
        $this->load->config('permissions');
        $config_perms = $this->config->item('permissions');
        $this->Permission_model->sync_from_config($config_perms);
        echo "Permissions synced successfully from config.";
    }
}
