<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Safely encodes a value for HTML output.
 *
 * @param mixed $input
 * @param bool  $preserveLineBreaks
 * @return string
 */
function safe_html($input, bool $preserveLineBreaks = false): string
{
    if ($input === null) {
        return '';
    }

    if (is_bool($input)) {
        return $input ? 'true' : 'false';
    }

    if (
        is_array($input) ||
        (is_object($input) && !method_exists($input, '__toString'))
    ) {
        throw new InvalidArgumentException(
            'Cannot sanitize array/object as HTML'
        );
    }

    $string = (string) $input;

    if (!mb_check_encoding($string, 'UTF-8')) {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');

        if (!mb_check_encoding($string, 'UTF-8')) {
            throw new InvalidArgumentException(
                'Invalid UTF-8 string after conversion attempt'
            );
        }
    }

    if ($preserveLineBreaks) {
        $string = nl2br($string, false);
    }

    return htmlspecialchars(
        $string,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8',
        false
    );
}


/**
 * Check whether the current user has a permission method.
 *
 * Permission is identified by the `method` column in the
 * permissions table.
 *
 * Examples:
 *
 *     has_permission('view_staff');
 *     has_permission('add_staff');
 *     has_permission('edit_staff');
 *     has_permission('delete_staff');
 *
 * @param string $method
 * @return bool
 */
function has_permission($method)
{
    $CI =& get_instance();

    // User must be authenticated.
    if (!$CI->ion_auth->logged_in()) {
        return false;
    }

    $user = $CI->ion_auth->user()->row();

    if (!$user) {
        return false;
    }

    // Get all groups belonging to the current user.
    $groups = $CI->ion_auth
        ->get_users_groups($user->id)
        ->result();

    if (empty($groups)) {
        return false;
    }

    // Administrator has unrestricted access.
    foreach ($groups as $group) {
        if ($group->name === 'admin') {
            return true;
        }
    }

    // Find the permission by METHOD.
    $permission = $CI->db
        ->select('id')
        ->where('method', $method)
        ->get('permissions')
        ->row();

    if (!$permission) {
        return false;
    }

    // Check whether any user group owns this permission.
    foreach ($groups as $group) {
        $exists = $CI->db
            ->where('group_id', $group->id)
            ->where('permission_id', $permission->id)
            ->count_all_results('group_permissions');

        if ($exists > 0) {
            return true;
        }
    }

    return false;
}
