<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Application Permissions
|--------------------------------------------------------------------------
|
| These permissions belong to the reusable application foundation.
|
| Business-specific permissions must NOT be added here.
|
| Example:
|
|     view_product
|     add_product
|     edit_product
|     delete_product
|
| would belong to a future Product module, not this base.
|
*/

$config['permissions'] = [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard' => [

        'view_dashboard' => 'View Dashboard',

    ],

    /*
    |--------------------------------------------------------------------------
    | Sample CRUD
    |--------------------------------------------------------------------------
    |
    | Reference CRUD used as the implementation pattern for
    | future modules.
    |
    */
    'sample' => [

        'view_sample'   => 'View Sample Records',
        'add_sample'    => 'Add Sample Record',
        'edit_sample'   => 'Edit Sample Record',
        'delete_sample' => 'Delete Sample Record',

    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    'settings' => [

        'manage_settings' => 'Manage Settings',

    ],

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    'users' => [

        'manage_users' => 'Manage Users',

    ],

    /*
    |--------------------------------------------------------------------------
    | Roles & Permissions
    |--------------------------------------------------------------------------
    */
    'roles' => [

        'manage_roles_permissions' => 'Manage Roles & Permissions',

    ],

    /*
    |--------------------------------------------------------------------------
    | Backup
    |--------------------------------------------------------------------------
    */
    'backup' => [

        'manage_backup' => 'Manage Backup & Restore',

    ],

    /*
    |--------------------------------------------------------------------------
    | Audit / Logs
    |--------------------------------------------------------------------------
    */
    'logs' => [

        'view_logs' => 'View Logs / Audit Trail',

    ],

];
