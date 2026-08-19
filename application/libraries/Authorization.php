<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Central authorization service.
 *
 * This library is the single application-level entry point
 * for permission checks.
 *
 * IMPORTANT:
 * Menu visibility is not authorization.
 * Controllers must call this library independently.
 */
class Authorization
{
    protected $CI;

    /**
     * Cached permissions for the current request.
     *
     * @var array|null
     */
    protected $user_permissions = null;

    /**
     * Cached authentication state.
     *
     * @var bool|null
     */
    protected $authenticated = null;

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->CI->load->database();
        $this->CI->load->library('ion_auth');
    }

    /**
     * Determine whether the current user is authenticated.
     *
     * @return bool
     */
    public function authenticated()
    {
        if ($this->authenticated !== null) {
            return $this->authenticated;
        }

        $this->authenticated = (bool) $this->CI->ion_auth->logged_in();

        return $this->authenticated;
    }

    /**
     * Determine whether the current user is an administrator.
     *
     * Administrator policy remains centralized here rather than
     * being duplicated throughout controllers.
     *
     * @return bool
     */
    public function is_admin()
    {
        if (!$this->authenticated()) {
            return false;
        }

        $groups = $this->CI->ion_auth
            ->get_users_groups()
            ->result();

        foreach ($groups as $group) {
            if (isset($group->name) && $group->name === 'admin') {
                return true;
            }
        }

        return false;
    }

    /**
     * Check whether the current user has a permission.
     *
     * @param string $permission
     * @return bool
     */
    public function can($permission)
    {
        if (!is_string($permission) || trim($permission) === '') {
            return false;
        }

        $permission = trim($permission);

        if (!$this->authenticated()) {
            return false;
        }

        /*
         * Administrator policy.
         *
         * This keeps the existing application's admin behavior,
         * but the rule is now centralized instead of being scattered
         * throughout the application.
         */
        if ($this->is_admin()) {
            return true;
        }

        $permissions = $this->get_user_permissions();

        return isset($permissions[$permission]);
    }

    /**
     * Require a permission.
     *
     * Controllers should use this method at the authorization
     * boundary before executing protected actions.
     *
     * @param string $permission
     * @param string $redirect_url
     * @return void
     */
    public function require_permission($permission, $redirect_url = 'auth/login')
    {
        if (!$this->can($permission)) {
            show_error(
                'You do not have permission to perform this action.',
                403,
                'Forbidden'
            );
        }
    }

    /**
     * Get all permissions belonging to the current user.
     *
     * The result is cached for the lifetime of the request.
     *
     * @return array
     */
    protected function get_user_permissions()
    {
        if ($this->user_permissions !== null) {
            return $this->user_permissions;
        }

        $this->user_permissions = [];

        if (!$this->authenticated()) {
            return $this->user_permissions;
        }

        /*
         * Resolve permissions through:
         *
         * users
         *   -> users_groups
         *       -> groups
         *           -> group_permissions
         *               -> permissions
         */
        $this->CI->db
            ->select('permissions.method')
            ->from('users_groups')
            ->join(
                'group_permissions',
                'group_permissions.group_id = users_groups.group_id'
            )
            ->join(
                'permissions',
                'permissions.id = group_permissions.permission_id'
            )
            ->where('users_groups.user_id', $this->CI->ion_auth->get_user_id())
            ->where('permissions.method IS NOT NULL', null, false);

        $query = $this->CI->db->get();

        foreach ($query->result() as $row) {
            if (!empty($row->method)) {
                $this->user_permissions[$row->method] = true;
            }
        }

        return $this->user_permissions;
    }

    /**
     * Clear the request-level permission cache.
     *
     * Useful after changing group permissions during the same request.
     *
     * @return void
     */
    public function clear_cache()
    {
        $this->user_permissions = null;
        $this->authenticated = null;
    }
}
