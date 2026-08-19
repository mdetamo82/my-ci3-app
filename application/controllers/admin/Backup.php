<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!has_permission('admin')) {
            alert_error('Access Denied');
            redirect('admin/dashboard');
        }

        $this->load->dbutil();
        $this->load->helper(['file', 'download']);
        $this->load->model('Backup_model');
    }

    public function index()
    {
        $backup_path = FCPATH . 'backups/';
        $files = array_diff(scandir($backup_path), ['.', '..']);

        $backups = [];
        foreach ($files as $file) {
            $backups[] = (object)[
                'file_name' => $file,
                'file_size' => filesize($backup_path . $file),
                'created_at' => date("Y-m-d H:i:s", filemtime($backup_path . $file))
            ];
        }

        $data = [
            'title' => 'Database Backup & Restore',
            'backups' => $backups
        ];
        $this->template->render('admin/backup/index', $data);
    }

    public function create()
    {
        $prefs = [
            'format' => 'zip',
            'filename' => 'db-backup.sql'
        ];
        $backup = $this->dbutil->backup($prefs);

        $name = 'backup-' . date('Ymd_His') . '.zip';
        $path = FCPATH . 'backups/' . $name;

        write_file($path, $backup);
        $this->Backup_model->log_backup($name, strlen($backup), 'Manual', $this->ion_auth->get_user_id());


        alert_success('Backup created successfully.');
        redirect('admin/backup');
    }

    public function download($file)
    {
        $path = FCPATH . 'backups/' . $file;
        if (file_exists($path)) {
            force_download($path, NULL);
        } else {
            alert_error('File not found.');
            redirect('admin/backup');
        }
    }

    public function delete($encoded_file)
    {
        $file = base64_decode(urldecode($encoded_file)); // decode twice: URL then base64
        $path = FCPATH . 'backups/' . $file;
    
        if (file_exists($path)) {
            unlink($path);
            alert_success('Backup deleted.');
        } else {
            alert_error('Backup file was already deleted or does not exist.');
        }
    
        redirect('admin/backup');
    }
    
        public function restore()
        {
            if ($_FILES['backup_file']['error'] === UPLOAD_ERR_OK) {
                $zip = new ZipArchive;
                $tmp_path = $_FILES['backup_file']['tmp_name'];
        
                if ($zip->open($tmp_path) === TRUE) {
                    $sql_content = $zip->getFromName('db-backup.sql');
                    $zip->close();
        
                    if (!$sql_content) {
                        alert_error('SQL file not found in backup.');
                        redirect('admin/backup');
                        return;
                    }
        
                    // Begin transaction
                    $this->db->trans_start();
        
                    // Disable foreign key checks
                    $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
                    $queries = explode(";\n", $sql_content);
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query)) {
                            $this->db->query($query);
                        }
                    }
        
                    // Enable foreign key checks again
                    $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        
                    $this->db->trans_complete();
        
                    if ($this->db->trans_status() === FALSE) {
                        alert_error('Database restore failed during execution.');
                    } else {
                        alert_success('Database restored successfully.');
                    }
        
                } else {
                    alert_error('Invalid backup file.');
                }
            } else {
                alert_error('No file uploaded or file upload error.');
            }
        
            redirect('admin/backup');
        }
        
        public function restore_from_file($file)
{
    $file = basename(urldecode($file));
    $path = FCPATH . 'backups/' . $file;

    if (!file_exists($path)) {
        alert_error('Backup file not found.');
        return redirect('admin/backup');
    }

    $zip = new ZipArchive;
    if ($zip->open($path) === TRUE) {
        $sql_content = $zip->getFromName('db-backup.sql');
        $zip->close();

        if ($sql_content) {
            // Disable FK checks temporarily
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

            $queries = explode(";\n", $sql_content);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $this->db->query($query);
                }
            }

            // Re-enable FK checks
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

            alert_success('Database restored from backup.');
        } else {
            alert_error('SQL content not found in backup.');
        }
    } else {
        alert_error('Invalid backup file.');
    }

    redirect('admin/backup');
}


    public function cron_backup($token = '')
    {
        if ($token !== 'secure-token-here') {
            show_404();
        }

        $prefs = ['format' => 'zip', 'filename' => 'db-backup.sql'];
        $backup = $this->dbutil->backup($prefs);

        $name = 'cron-backup-' . date('Ymd_His') . '.zip';
        $path = FCPATH . 'backups/' . $name;

        write_file($path, $backup);
        $this->Backup_model->log_backup($name, strlen($backup), 'Cron', null);
    }
}
