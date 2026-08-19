<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!has_permission('backup', 'create_backup')) {
            show_error('Access denied: You can’t create backups.', 403);
        }
        $this->load->dbutil();
        $this->load->helper('download');
    }

    public function index() {
        // Create a backup of the entire database
        $prefs = array(
            'format'      => 'zip',
            'filename'    => 'my_backup.sql' // inside the zip file
        );

        $backup = $this->dbutil->backup($prefs);

        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
        force_download($db_name, $backup);
    }
}
