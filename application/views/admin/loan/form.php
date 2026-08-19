<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($loan) ? 'Edit Loan' : 'Add New Loan'; ?></h3>
    </div>

    <?= form_open(isset($loan) ? 'admin/loan/edit/' . $loan->loan_id : 'admin/loan/create', ['id' => 'loanForm']); ?>
    <input type="hidden" name="loan_id" value="<?= isset($loan) ? $loan->loan_id : ''; ?>">

    <div class="card-body">
        <div class="row">

            <!-- Loan Name -->
            <div class="form-group col-md-4">
                <label for="name">Loan Name</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user-tag"></i></div></div>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= set_value('name', isset($loan) ? $loan->name : ''); ?>">
                </div>
            </div>

            <!-- Balance -->
            <?php if (!isset($loan)): ?>
            <!-- Forward Balance (only on create) -->
            <div class="form-group col-md-4">
                <label for="balance">Forward Balance</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-dollar-sign"></i></div></div>
                    <input type="number" class="form-control" id="balance" name="balance"
                        value="<?= set_value('balance', 0); ?>" step="0.01">
                </div>
            </div>
            <?php endif; ?>

            <?php if (!isset($loan)): ?>
            <!-- Forward Balance Date -->
            <div class="form-group col-md-4">
                <label for="created_date">Forward Balance Date</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                    <input type="text" class="form-control" id="created_date" name="created_date"
                        value="<?= set_value('created_date', date('Y-m-d')); ?>" placeholder="Select date...">
                </div>
            </div>
            <?php endif; ?>

            <!-- Mobile -->
            <div class="form-group col-md-4">
                <label for="mobile">Mobile</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                    <input type="text" class="form-control" id="mobile" name="mobile"
                        value="<?= set_value('mobile', isset($loan) ? $loan->mobile : ''); ?>">
                </div>
            </div>

            <!-- Address -->
            <div class="form-group col-md-4">
                <label for="address">Address</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                    <input type="text" class="form-control" id="address" name="address"
                        value="<?= set_value('address', isset($loan) ? $loan->address : ''); ?>">
                </div>
            </div>

            <!-- Active Status -->
            <div class="form-group col-md-4">
                <label for="is_active">Active Status</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                    </div>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" <?= (isset($loan) && $loan->is_active == 1) ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= (isset($loan) && $loan->is_active == 0) ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <small>
                    <span id="status-badge" class="badge mt-2 badge-<?= (isset($loan) && $loan->is_active == 1) ? 'success' : 'danger'; ?>">
                        <?= (isset($loan) && $loan->is_active == 1) ? 'Active' : 'Inactive'; ?>
                    </span>
                </small>
            </div>

        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><?= isset($loan) ? 'Update' : 'Create'; ?> Loan</button>
        <a href="<?= site_url('admin/loan'); ?>" class="btn btn-secondary">Cancel</a>
    </div>

    <?= form_close(); ?>
</div>
<!-- Scripts -->
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#created_date", {
    dateFormat: "Y-m-d",
    maxDate: "today",
    allowInput: true
});
</script>

<script>
$(function () {
    $("#loanForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
               
            },
            balance: {
                required: true,
                number: true
            },
            mobile: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            address: {
                required: true
            },
            is_active: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter loan name",
                minlength: "Name must be at least 2 characters",
                remote: "This loan name already exists"
            },
            balance: {
                required: "Please enter balance",
                number: "Balance must be a number"
            },
            mobile: {
                digits: "Only numbers allowed",
                minlength: "At least 10 digits",
                maxlength: "Max 15 digits"
            },
            address: {
                required: "Please enter address"
            },
            is_active: {
                required: "Please select status"
            }
        },
        errorElement: 'small',
        errorClass: 'text-danger',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        }
    });

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
