<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['permissions'] = [
    'staff' => [
        'view_staff' => 'View Staff',
        'add_staff' => 'Add Staff',
        'edit_staff' => 'Edit Staff',
        'delete_staff' => 'Delete Staff',
    ],

    'hawala' => [
        'view_hawala' => 'View Hawala',
        'add_hawala' => 'Add Hawala',
        'edit_hawala' => 'Edit Hawala',
        'delete_hawala' => 'Delete Hawala',
    ],

    'loan' => [
        'view_loan' => 'View Loan',
        'add_loan' => 'Add Loan',
        'edit_loan' => 'Edit Loan',
        'delete_loan' => 'Delete Loan',
    ],

    'Staff_transaction' => [
        'view_staff_transaction' => 'Staff Transaction',
        'add_staff_transaction' => 'Add Staff Transaction',
        'edit_staff_transaction' => 'Edit Staff Transaction',
        'delete_staff_transaction' => 'Delete Staff Transaction',
    ],

    'Hawala_transaction' => [
        'view_hawala_transaction' => 'Hawala Transaction',
        'add_hawala_transaction' => 'Add Hawala Transaction',
        'edit_hawala_transaction' => 'Edit Hawala Transaction',
        'delete_hawala_transaction' => 'Delete Hawala Transaction',
    ],

    'Loan_transaction' => [
        'view_loan_transaction' => 'Loan Transaction',
        'add_loan_transaction' => 'Add Loan Transaction',
        'edit_loan_transaction' => 'Edit Loan Transaction',
        'delete_loan_transaction' => 'Delete Loan Transaction',
    ],

    // 'backup' => [
    //     'create_backup' => 'Create Backup'
    // ]

];
