<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template {
    private $CI;
    private $layout_parts = [
        'header' => true,
        'navbar' => true,
        'sidebar' => true,
        'footer' => true,
        'scripts' => true
    ];
    private $current_user = null;
    private $cache_time = 0;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('ion_auth');
    }

    public function disable($component) {
        if (array_key_exists($component, $this->layout_parts)) {
            $this->layout_parts[$component] = false;
        }
        return $this;
    }

    public function set_cache($minutes) {
        $this->cache_time = $minutes * 60;
        return $this;
    }

    public function render($view, $data = [], $return = false) {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data parameter must be an array');
        }
        
        if (!is_string($view) || empty($view)) {
            throw new InvalidArgumentException('View parameter must be a non-empty string');
        }

        if ($this->cache_time > 0) {
            $this->CI->output->cache($this->cache_time);
        }

        // Get current user if logged in
        $user = $this->get_current_user();
        if ($user) {
            $this->CI->load->model('User_model');
            $data['dark_mode'] = $this->CI->User_model->get_dark_mode($user->id);
            $data['current_user'] = $user;
        }

        if ($return) {
            ob_start();
            $this->render_views($view, $data);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        $this->render_views($view, $data);
    }

    private function render_views($view, $data) {
        if ($this->layout_parts['header']) {
            $this->CI->load->view('templates/header', $data);
        }
        if ($this->layout_parts['navbar']) {
            $this->CI->load->view('templates/navbar', $data);
        }
        if ($this->layout_parts['sidebar']) {
            $this->CI->load->view('templates/sidebar', $data);
        }
        
        $this->CI->load->view($view, $data);
        
        if ($this->layout_parts['footer']) {
            $this->CI->load->view('templates/footer', $data);
        }
        if ($this->layout_parts['scripts']) {
            $this->CI->load->view('templates/scripts', $data);
        }
    }

    private function get_current_user() {
        if ($this->current_user === null && $this->CI->ion_auth->logged_in()) {
            $this->current_user = $this->CI->ion_auth->user()->row();
        }
        return $this->current_user;
    }
}