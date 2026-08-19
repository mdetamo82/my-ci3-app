<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($account) ? 'Edit Bank Account' : 'Add New Bank Account'; ?></h3>
    </div>

    <?= form_open(isset($account) ? 'admin/staff_bank/edit/' . $account->id : 'admin/staff_bank/create', ['id' => 'bankForm']); ?>
    <input type="hidden" name="id" value="<?= isset($account) ? $account->id : ''; ?>">

    <div class="card-body">
        <div class="row">

            <!-- Staff ID -->
            <div class="form-group col-md-6">
                <label for="staff_id">Staff Member</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                    <select class="form-control" id="staff_id" name="staff_id" required>
                        <option value="">Select Staff</option>
                        <?php foreach($staff_members as $staff): ?>
                            <option value="<?= $staff->staff_id ?>" 
                                <?= set_select('staff_id', $staff->staff_id, (isset($account) && $account->staff_id == $staff->staff_id)) ?>>
                                <?= $staff->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Bank Name -->
            <div class="form-group col-md-6">
                <label for="name">Bank Name</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-university"></i></div></div>
                    <input type="text" class="form-control" id="name" name="name" required
                        value="<?= set_value('name', isset($account) ? $account->name : ''); ?>">
                </div>
            </div>

            <!-- Account Number -->
            <div class="form-group col-md-6">
                <label for="number">Account Number</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-credit-card"></i></div></div>
                    <input type="text" class="form-control" id="number" name="number" required
                        value="<?= set_value('number', isset($account) ? $account->number : ''); ?>">
                </div>
            </div>

            <!-- Balance -->
            <div class="form-group col-md-6">
                <label for="balance">Current Balance</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-dollar-sign"></i></div></div>
                    <input type="number" step="0.01" class="form-control" id="balance" name="balance" required
                        value="<?= set_value('balance', isset($account) ? $account->balance : '0.00'); ?>">
                </div>
            </div>

        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><?= isset($account) ? 'Update' : 'Create'; ?> Account</button>
        <a href="<?= site_url('admin/staff_bank'); ?>" class="btn btn-secondary">Cancel</a>
    </div>

    <?= form_close(); ?>
</div>



<!-- Scripts -->
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>

<script>
$(function () {
    $("#bankForm").validate({
        rules: {
            staff_id: {
                required: true
            },
            name: {
                required: true,
                minlength: 2
            },
            number: {
                required: true,
                minlength: 5
            },
            balance: {
                required: true,
                number: true
            }
        },
        messages: {
            staff_id: {
                required: "Please select staff member"
            },
            name: {
                required: "Please enter bank name",
                minlength: "Bank name must be at least 2 characters"
            },
            number: {
                required: "Please enter account number",
                minlength: "Account number must be at least 5 characters"
            },
            balance: {
                required: "Please enter current balance",
                number: "Balance must be a number"
            }
        },
        errorElement: 'small',
        errorClass: 'text-danger',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            showConfirmationDialog(form);
            return false;
        }
    });

    function showConfirmationDialog(form) {
        const staffName = $('#staff_id option:selected').text();
        const bankName = $('#name').val();
        const accountNumber = $('#number').val();
        const balance = $('#balance').val();
        const isEdit = <?= isset($account) ? 'true' : 'false' ?>;
        
        Swal.fire({
            title: isEdit ? 'Confirm Account Update' : 'Confirm New Account',
            html: `
                <div style="font-size: 16px; text-align: left;">
                    <p>Please review the account details:</p>
                    <div style="margin-top: 1rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 0.5rem;">
                                <strong>🧑‍💼 Staff Member:</strong>
                                <span style="color: #4361ee; font-weight: 600;">${staffName}</span>
                            </li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>🏦 Bank Name:</strong>
                                <span style="color: #4361ee; font-weight: 600;">${bankName}</span>
                            </li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>🔢 Account Number:</strong>
                                <span style="color: #4361ee; font-weight: 600;">${accountNumber}</span>
                            </li>
                            <li style="margin-bottom: 0.5rem;">
                                <strong>💰 Current Balance:</strong>
                                <span style="color: #10b981; font-weight: 600;">${parseFloat(balance).toFixed(2)} ETB</span>
                            </li>
                        </ul>
                    </div>
                    <p style="margin-top: 1rem; font-size: 15px; color: #6b7280;">
                        Make sure all information is correct before ${isEdit ? 'updating' : 'creating'} the account.
                    </p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isEdit ? 'Update Account' : 'Create Account',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusConfirm: false,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                processFormSubmission(form);
            }
        });
    }

    function processFormSubmission(form) {
        // Show loading state
        Swal.fire({
            title: 'Processing...',
            html: 'Please wait while we process your request...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Submit the form via AJAX
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: $(form).serialize(),
            success: function(response) {
                showSuccessMessage(response);
            },
            error: function(xhr) {
                showErrorMessage(xhr);
            }
        });
    }

    function showSuccessMessage(response) {
        const isEdit = <?= isset($account) ? 'true' : 'false' ?>;
        
        Swal.fire({
            title: 'Success!',
            html: `
                <div style="text-align:center;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <h3 style="color:#10b981; margin-top:15px;">Account ${isEdit ? 'Updated' : 'Created'}!</h3>
                    <div style="margin-top: 1rem;">
                        <p>The bank account has been successfully ${isEdit ? 'updated' : 'created'}.</p>
                    </div>
                </div>
            `,
            icon: 'success',
            showConfirmButton: true,
            confirmButtonText: 'View Accounts',
            showCancelButton: true,
            cancelButtonText: 'Stay Here',
            focusConfirm: false,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('admin/staff_bank') ?>';
            } else if (result.dismiss === Swal.DismissReason.cancel && isEdit) {
                // Refresh the page to show updated data if editing
                window.location.reload();
            }
        });
    }

    function showErrorMessage(xhr) {
        let errorMessage = 'There was an error processing your request. Please try again.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#4361ee'
        });
    }
});
</script>