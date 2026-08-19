<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($hawala) ? 'Edit Hawala' : 'Add New Hawala'; ?></h3>
    </div>

    <?php echo form_open(isset($hawala) ? 'admin/hawala/edit/' . $hawala->hawala_id : 'admin/hawala/create', ['id' => 'hawalaForm']); ?>
        <div class="card-body">
            <div class="row">
                <!-- Mark -->
                <div class="form-group col-md-4">
                    <label for="mark">Mark</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-tag"></i></div></div>
                        <input type="text" class="form-control" id="mark" name="mark"
                               value="<?= set_value('mark', isset($hawala) ? $hawala->mark : ''); ?>">
                    </div>
                    <small id="markFeedback" class="form-text text-danger d-none"></small>
                </div>

                <!-- Hawala Name -->
                <div class="form-group col-md-4">
                    <label for="name">Hawala Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?= set_value('name', isset($hawala) ? $hawala->name : ''); ?>">
                    </div>
                </div>

                
                <!-- Currency -->
                <div class="form-group col-md-4">
                    <label for="currency">Currency</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-coins"></i></div>
                        </div>
                        <select class="form-control" id="currency" name="currency">
                            <option value="">-- Select Currency --</option>
                            <?php foreach ($currency as $curr): ?>
                                <option value="<?= $curr->code; ?>" 
                                    <?= (isset($hawala) && $hawala->currency === $curr->code) ? 'selected' : ''; ?>>
                                    <?= $curr->code; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php if (!isset($hawala)): ?>
                        <!-- Forward Balance -->
                        <div class="form-group col-md-4">
                            <label for="balance">Forward Balance</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-money-bill"></i></div>
                                </div>
                                <input type="number" step="0.01" name="balance" id="balance" class="form-control"
                                    value="<?= set_value('balance', 0); ?>">
                            </div>
                        </div>

                        <!-- Forward Balance Date -->
                        <div class="form-group col-md-4">
                            <label for="created_date">Forward Balance Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="created_date" 
                                    name="created_date"
                                    value="<?= set_value('created_date', date('Y-m-d')); ?>"
                                    placeholder="Select date...">
                            </div>
                        </div>
                    <?php endif; ?>

                        
                        <!-- Mobile -->
                        <div class="form-group col-md-4">
                            <label for="mobile">Mobile</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                                <input type="text" class="form-control" id="mobile" name="mobile"
                                       value="<?= set_value('mobile', isset($hawala) ? $hawala->mobile : ''); ?>">
                            </div>
                        </div>
                        
                <!-- Address -->
                <div class="form-group col-md-12">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= set_value('address', isset($hawala) ? $hawala->address : ''); ?></textarea>
                </div>

                <!-- Active Status -->
                <div class="form-group col-md-6">
                    <label for="is_active">Active Status</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" <?= (isset($hawala) && $hawala->is_active == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (isset($hawala) && $hawala->is_active == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <!-- Badge placed outside for better alignment -->
                    <small>
                        <span id="status-badge" class="badge mt-2 badge-<?= (isset($hawala) && $hawala->is_active == 1) ? 'success' : 'danger'; ?>">
                            <?= (isset($hawala) && $hawala->is_active == 1) ? 'Active' : 'Inactive'; ?>
                        </span>
                    </small>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><?= isset($hawala) ? 'Update' : 'Create'; ?> Hawala</button>
            <a href="<?= site_url('admin/hawala'); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    <?= form_close(); ?>
</div>
<!-- Flatpickr CSS & JS -->

<!-- Add Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function () {
    const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    $('#mark').on('blur', function () {
        const mark = $(this).val();
        const id = <?= isset($hawala) ? $hawala->hawala_id : 'null'; ?>;

        if (mark.trim().length < 3) return;

        const data = {
            mark: mark,
            id: id
        };
        data[csrfName] = csrfHash; // Add CSRF token

        $.ajax({
            url: '<?= site_url('admin/hawala/check_mark_unique_ajax'); ?>',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.exists) {
                    $('#markFeedback').text('This mark is already in use.').removeClass('d-none');
                    $('#mark').addClass('is-invalid').removeClass('is-valid');
                } else {
                    $('#markFeedback').addClass('d-none');
                    $('#mark').addClass('is-valid').removeClass('is-invalid');
                }

                // Update CSRF token (if you're rotating it per request)
                if (res.csrf_token) {
                    $('input[name="' + csrfName + '"]').val(res.csrf_token);
                }
            }
        });
    });
});
</script>

<!-- Scripts -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>

<script>


$(function () {
    setupValidation('#hawalaForm', {
        name: {
            required: true,
            minlength: 2
        },
        mark: {
            required: true,
            minlength: 2
        },
        mobile: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 15
        },
        currency: {
            required: true
        },
        address: {
            required: true
        },
        is_active: {
            required: true
        }
        balance: { 
            number: true } 

    }, {
        name: {
            required: "Please enter hawala name",
            minlength: "Name must be at least 2 characters"
        },
        mark: {
            required: "Please enter hawala mark",
            minlength: "Mark must be at least 2 characters"
        },
        balance: {
        number: "Please enter a valid number"
        },
        mobile: {
            required: "Please provide mobile number",
            digits: "Please enter only numbers",
            minlength: "Mobile must be at least 10 digits",
            maxlength: "Mobile cannot exceed 15 digits"
        },
        currency: {
            required: "Please enter currency"
        },
        address: {
            required: "Please enter address"
        },
        is_active: {
            required: "Please select status"
        }
    });
});
</script>
<script>
  $(function () {
    $('#is_active').on('change', function () {
      let value = $(this).val();
      let badge = $('#status-badge');

      if (value === '1') {
        badge.removeClass('badge-danger').addClass('badge-success').text('Active');
      } else {
        badge.removeClass('badge-success').addClass('badge-danger').text('Inactive');
      }
    });
  });

  flatpickr("#created_date", {
    dateFormat: "Y-m-d",
    maxDate: "today",
    allowInput: true
});

</script>


