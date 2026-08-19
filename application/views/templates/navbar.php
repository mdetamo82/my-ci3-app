<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= site_url('admin'); ?>" class="nav-link <?= $this->uri->segment(1) === 'admin' ? 'active' : ''; ?>">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Dark mode toggle -->
    <li class="nav-item">
      <a class="nav-link" href="<?= site_url('theme/toggle_dark_mode') ?>" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas <?= !empty($dark_mode) ? 'fa-sun' : 'fa-moon'; ?>"></i>
      </a>
    </li>

    <!-- Username Display -->
    <?php if ($this->session->userdata('username')): ?>
      <li class="nav-item">
        <span class="nav-link">
          <i class="fas fa-user mr-1"></i> <?= html_escape($this->session->userdata('username')); ?>
        </span>
      </li>
    <?php endif; ?>

    <!-- Logout Button (responsive for all sizes) -->
    <li class="nav-item"> 
    <a class="nav-link" href="<?= site_url('auth/logout') ?>">
        <i class="fas fa-sign-out-alt"></i> 
        <span class="d-none d-sm-inline">Logout</span>
    </a>
</li>
  </ul>
</nav>
<!-- /.navbar -->
