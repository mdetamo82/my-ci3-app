</div>
<!-- Reusable Bootstrap Modal -->
 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <!-- Content loaded via AJAX -->
    </div>
  </div>
</div>

<footer class="main-footer">
  <div class="float-right d-none d-sm-inline">
    <b>Version</b> 1.0.0
  </div>
  <strong>&copy; 2017–<?= date('Y') ?> <a href="https://ynebat.com" target="_blank" rel="noopener noreferrer">Nebate</a>.</strong>
  All rights reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Optional sidebar content -->
</aside>
<!-- /.control-sidebar -->
</div> <!-- Close content-wrapper -->
</div> <!-- Close wrapper -->

<!-- Dark Mode Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('darkModeToggle');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      fetch(this.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.json())
      .then(data => {
        document.body.classList.toggle('dark-mode', data.dark_mode);
        const icon = this.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-moon', !data.dark_mode);
          icon.classList.toggle('fa-sun', data.dark_mode);
        }
      });
    });
  }
});
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    <?php if ($this->session->flashdata('success')): ?>
      Swal.fire({ icon: 'success', timer: 1500,  position: 'top-end',  showConfirmButton: false, title: <?= json_encode($this->session->flashdata('success')) ?> });
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      Swal.fire({ icon: 'error', title: <?= json_encode($this->session->flashdata('error')) ?> });
    <?php endif; ?>

    <?php if ($this->session->flashdata('warning')): ?>
      Toast.fire({ icon: 'warning', title: <?= json_encode($this->session->flashdata('warning')) ?> });
    <?php endif; ?>

    <?php if ($this->session->flashdata('info')): ?>
      Toast.fire({ icon: 'info', title: <?= json_encode($this->session->flashdata('info')) ?> });
    <?php endif; ?>
  });
</script>

<!-- <script>
$(document).ready(function () {
    // When any modal trigger is clicked
    $('body').on('click', '[data-toggle="modal"]', function (e) {
        var target = $(this).data('target');
        var url = $(this).attr('href');
        if ($(target).length && url !== '#') {
            e.preventDefault();
            $(target + ' .modal-content').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            $(target).modal('show').find('.modal-content').load(url);
        }
    });
});
</script> -->
<script>
function setupValidation(formSelector, customRules = {}, customMessages = {}) {
  $(function () {
    $(formSelector).validate({
      rules: customRules,
      messages: customMessages,
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element) {
        $(element)
          .addClass('is-invalid')
          .removeClass('is-valid');
        $(element)
          .closest('.form-group')
          .find('.valid-feedback')
          .remove();
      },
      unhighlight: function (element) {
        $(element)
          .removeClass('is-invalid')
          .addClass('is-valid');

         const $group = $(element).closest('.form-group');
      $group.find('.valid-feedback').remove(); // prevent duplicates
       $group.append('<span class="valid-feedback">Looks good!</span>');
      },
      
    });
  });
}
</script>
<!-- <script>
$(function () {
  // Fetch CSRF name and token from meta tags
  const csrfName = $('meta[name="csrf-name"]').attr('content');
  const csrfToken = $('meta[name="csrf-token"]').attr('content');

  // Automatically include CSRF token in all jQuery AJAX requests
  $.ajaxSetup({
    beforeSend: function (xhr, settings) {
      if (settings.type === 'POST') {
        // Add the token to POST data
        if (typeof settings.data === 'string') {
          settings.data += `&${csrfName}=${encodeURIComponent(csrfToken)}`;
        } else if (typeof settings.data === 'object') {
          settings.data[csrfName] = csrfToken;
        }
      }
    }
  });
});
</script> -->



