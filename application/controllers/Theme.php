<?php

// application/controllers/Theme.php
class Theme extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    }
    
    public function toggle_dark_mode() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    
        $user_id = $this->ion_auth->get_user_id();
        $current_mode = $this->User_model->get_dark_mode($user_id);
        $new_mode = $current_mode ? 0 : 1; // Toggle
    
        $this->User_model->update_dark_mode($user_id, $new_mode);
    
        // Return JSON for AJAX or redirect
        if ($this->input->is_ajax_request()) {
            echo json_encode(['dark_mode' => $new_mode]);
        } else {
            redirect($_SERVER['HTTP_REFERER'] ?? 'dashboard');
        }
    }
}