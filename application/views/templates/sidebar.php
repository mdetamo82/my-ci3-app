  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo site_url('admin'); ?>" class="brand-link">
      <img src="<?php echo base_url('assets/dist/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
      <span class="brand-text font-weight-light">NEBAT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
     <!-- Sidebar user panel (secure) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <!-- <div class="image">
        <?php
        $user_image = 'dist/img/user2-160x160.jpg'; // Default image
        if (!empty($current_user->avatar)) {
            // Validate the avatar path for security
            if (file_exists(FCPATH . $current_user->avatar)) {
                $user_image = $current_user->avatar;
            }
        }
        ?>
        <img src="<?php echo base_url($user_image); ?>" class="img-circle elevation-2" alt="User Image">
    </div> -->
    <div class="info">
        <a href="<?php echo site_url('admin/profile'); ?>" class="d-block">
            <?php 
            // Securely escape output
            echo html_escape($current_user->first_name . ' ' . $current_user->last_name); 
            ?>
            <small class="text-muted d-block">
                <?php 
                // Display user group securely
                $groups = $this->ion_auth->get_users_groups($current_user->id)->result();
                if (!empty($groups)) {
                    echo html_escape($groups[0]->name);
                }
                ?>
            </small>
        </a>
    </div>
</div>
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" />
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">

      <?php
$CI =& get_instance();
$CI->load->config('menu');
$menu_items = $CI->config->item('sidebar_menu');

/**
 * Check if current URL exactly matches the menu URL.
 * 
 * @param string $url
 * @return bool
 */
function is_active_menu($url)
{
    $current = trim(uri_string(), '/');
    $target = trim($url, '/');

    return $current === $target;
}

/**
 * Recursively check if any child menu is active.
 * 
 * @param array $items
 * @return bool
 */
function has_active_child($items)
{
    foreach ($items as $item) {
        if (isset($item['url']) && is_active_menu($item['url'])) {
            return true;
        }
        if (isset($item['children']) && has_active_child($item['children'])) {
            return true;
        }
    }
    return false;
}

/**
 * Render the sidebar menu recursively.
 * 
 * @param array $items
 * @return void
 */
function render_sidebar_menu($items)
{
    $CI =& get_instance();
    $current_url = trim(uri_string(), '/');

    foreach ($items as $item) {
        // Check permission (skip if no permission)
        if (!has_permission($item['permission'] ?? '')) {
            continue;
        }

        $has_children = isset($item['children']) && is_array($item['children']);
        $url = isset($item['url']) ? $item['url'] : '#';

        // Check if this menu or any child menu is active
        $is_active = $url !== '#' && is_active_menu($url);

        if ($has_children) {
            foreach ($item['children'] as $child) {
                if (!has_permission($child['permission'] ?? '')) {
                    continue;
                }
                if (isset($child['url']) && is_active_menu($child['url'])) {
                    $is_active = true;
                    break;
                }
            }
        }

        $icon = $item['icon'] ?? 'far fa-circle';
        $active_class = $is_active ? ' active' : '';
        $menu_open_class = ($has_children && $is_active) ? ' menu-open' : '';

        echo '<li class="nav-item' . ($has_children ? ' has-treeview' : '') . $menu_open_class . '">';
        echo '<a href="' . site_url($url) . '" class="nav-link' . $active_class . '">';
        echo '<i class="nav-icon ' . $icon . '"></i>';
        echo '<p>' . $item['label'];
        if ($has_children) {
            echo '<i class="right fas fa-angle-left"></i>';
        }
        echo '</p></a>';

        if ($has_children) {
            echo '<ul class="nav nav-treeview">';
            render_sidebar_menu($item['children']); // recursive call
            echo '</ul>';
        }

        echo '</li>';
    }
}
?>

<!-- Sidebar menu UL container -->
<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
    <?php render_sidebar_menu($menu_items); ?>
</ul>


      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">