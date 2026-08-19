<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['sidebar_menu'] = [

    // Dashboard
    [
        'label'      => 'Dashboard',
        'icon'       => 'fas fa-tachometer-alt',
        'url'        => 'dashboard',
        'permission' => 'view_dashboard',
    ],

    // Users Management
    [
        'label'      => 'Manage Users',
        'icon'       => 'fas fa-database',
        'permission' => ['view_staff', 'view_hawala', 'view_loan'],
        'children'   => [
            [
                'label'      => 'Manage Staff',
                'icon'       => 'fas fa-user-cog',
                'url'        => 'admin/staff',
                'permission' => 'view_staff',
            ],
            [
                'label'      => 'Manage Hawala',
                'icon'       => 'fas fa-exchange-alt',
                'url'        => 'admin/hawala',
                'permission' => 'view_hawala',
            ],
            [
                'label'      => 'Manage Loan',
                'icon'       => 'fas fa-hand-holding-usd',
                'url'        => 'admin/loan',
                'permission' => 'view_loan',
            ],
        ]
    ],

    // Staff Transactions
    [
        'label'      => 'Staff Transactions',
        'icon'       => 'fas fa-briefcase',
        'permission' => 'view_staff_transaction',
        'children'   => [
            ['label' => 'Staff Income',       'url' => 'admin/staff_transaction/create/income',   'permission' => 'view_staff_transaction'],
            ['label' => 'Staff Expense',      'url' => 'admin/staff_transaction/create/expense',  'permission' => 'view_staff_transaction'],
        ],
    ],

    // Hawala Transactions
    [
        'label'      => 'Hawala Transactions',
        'icon'       => 'fas fa-sync',
        'permission' => 'view_hawala_transaction',
        'children'   => [
            ['label' => 'Credit (IN)',              'url' => 'admin/hawala_transaction/create/income',         'permission' => 'view_hawala_transaction'],
            ['label' => 'Debit (OUT)',             'url' => 'admin/hawala_transaction/create/expense',        'permission' => 'view_hawala_transaction'],
            ['label' => 'Hawala Transfer',             'url' => 'admin/hawala_transfer/create',        'permission' => 'view_hawala_transaction'],
        ],
    ],

    // Staff ↔ Hawala
    [
        'label'      => 'Staff ↔ Hawala',
        'icon'       => 'fas fa-random',
        'permission' => 'view_hawala_transaction',
        'children'   => [
            ['label' => 'Credit (IN)','url' => 'admin/hawala_staff_transaction/create/income',  'permission' => 'view_hawala_transaction'],
            ['label' => 'Debit (OUT)','url' => 'admin/hawala_staff_transaction/create/expense', 'permission' => 'view_hawala_transaction'],
        ],
    ],

    // Staff ↔ Loan
    [
        'label'      => 'Staff ↔ Loan',
        'icon'       => 'fas fa-coins',
        'permission' => 'view_loan_transaction',
        'children'   => [
            ['label' => 'Income (Repayment)',  'url' => 'admin/loan_transaction/create/income',           'permission' => 'view_loan_transaction'],
            ['label' => 'Expense (Loan Out)',  'url' => 'admin/loan_transaction/create/expense',          'permission' => 'view_loan_transaction'],
        ],
    ],

    // Reports
    [
        'label'      => 'Reports',
        'icon'       => 'fas fa-chart-line',
        'permission' => 'reports',
        'children'   => [
            ['label' => 'Staff Reports',       'url' => 'admin/reports/staff',        'permission' => 'report_staff'],
            ['label' => 'Hawala Reports',      'url' => 'admin/reports/hawala',       'permission' => 'report_hawala'],
            ['label' => 'Loan Reports',        'url' => 'admin/reports/loan',         'permission' => 'report_loan'],
            ['label' => 'Daily Report',        'url' => 'admin/reports/daily',        'permission' => 'report_daily'],
            ['label' => 'Destination Report',  'url' => 'admin/reports/destination',  'permission' => 'report_destination'],
            ['label' => 'Balance Sheet',       'url' => 'admin/reports/balance',      'permission' => 'report_balance'],
            ['label' => 'Transactions Report', 'url' => 'admin/reports/transaction', 'permission' => 'report_transactions'],
        ],
    ],

    // Settings
    [
        'label'      => 'Settings',
        'icon'       => 'fas fa-cogs',
        'permission' => 'manage_settings',
        'children'   => [
            [
                'label'      => 'General Settings',
                'icon'       => 'fas fa-sliders-h',
                'url'        => 'admin/settings/general',
                'permission' => 'manage_general_settings',
            ],
            [
                'label'      => 'User Management',
                'icon'       => 'fas fa-users-cog',
                'url'        => 'auth',
                'permission' => 'manage_users',
            ],
            [
                'label'      => 'Roles & Permissions',
                'icon'       => 'fas fa-user-shield',
                'url'        => 'admin/permission_admin',
                'permission' => 'manage_roles_permissions',
            ],
            [
                'label'      => 'Backup & Restore',
                'icon'       => 'fas fa-database',
                'url'        => 'admin/backup',
                'permission' => 'manage_backup',
            ],
            [
                'label'      => 'Logs / Audit Trail',
                'icon'       => 'fas fa-file-alt',
                'url'        => 'admin/logs',
                'permission' => 'view_logs',
            ],
        ],
    ],

];
