<?php defined('BASEPATH') OR exit('No direct script access allowed');

// application/controllers/Error404.php
class Error404 extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->output->set_status_header('404');
        $data['title'] = '04 Page Not Found';
        $this->template->render('errors/html/error_404', $data);
    }
}