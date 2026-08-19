<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($staff) ? 'Edit Staff' : 'Add New Staff'; ?></h3>
    </div>

    <?= form_open(isset($staff) ? 'admin/staff/edit/' . $staff->staff_id : 'admin/staff/create', ['id' => 'staffForm']); ?>
        <div class="card-body">
            <div class="row">

                <!-- Name -->
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?= set_value('name', isset($staff) ? $staff->name : ''); ?>">
                    </div>
                </div>

                <!-- Mobile -->
                <div class="form-group col-md-6">
                    <label for="mobile">Mobile</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                        <input type="text" class="form-control" id="mobile" name="mobile"
                               value="<?= set_value('mobile', isset($staff) ? $staff->mobile : ''); ?>">
                    </div>
                </div>

                            <!-- Department -->
                <div class="form-group col-md-6">
                    <label for="department">Department</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-building"></i></div></div>
                        <input type="text" class="form-control" id="department" name="department"
                            value="<?= set_value('department', isset($staff) ? $staff->department : ''); ?>">
                    </div>
                </div>

                            <!-- Address -->
                <div class="form-group col-md-6">
                    <label for="address">Address</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?= set_value('address', isset($staff) ? $staff->address : ''); ?>">
                    </div>
                </div>

                           <!-- Active Status -->
                <div class="form-group col-md-6">
                    <label for="is_active">Active Status</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" <?= (isset($staff) && $staff->is_active == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= (isset($staff) && $staff->is_active == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                    <small>
                        <span id="status-badge" class="badge mt-2 badge-<?= (isset($staff) && $staff->is_active == 1) ? 'success' : 'danger'; ?>">
                            <?= (isset($staff) && $staff->is_active == 1) ? 'Active' : 'Inactive'; ?>
                        </span>
                    </small>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><?= isset($staff) ? 'Update' : 'Create'; ?> Staff</button>
            <a href="<?= site_url('admin/staff'); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    <?= form_close(); ?>
</div>

<!-- Scripts -->
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>

<script>
$(function () {
    setupValidation('#staffForm', {
        name: {
            required: true,
            minlength: 2
        },
        mobile: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 15
        },
        address: {
            required: true
        },
        department: {
            required: true
        },
        is_active: {
            required: true
        }
    }, {
        name: {
            required: "Please enter staff name",
            minlength: "Name must be at least 2 characters"
        },
        mobile: {
            required: "Please provide mobile number",
            digits: "Please enter only numbers",
            minlength: "Mobile must be at least 10 digits",
            maxlength: "Mobile cannot exceed 15 digits"
        },
        address: {
            required: "Please enter address"
        },
        department: {
            required: "Please enter department"
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
</script>
