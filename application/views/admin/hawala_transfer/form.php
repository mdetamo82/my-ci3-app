<?php
$disable_fields = isset($lock_fields) && $lock_fields ? 'disabled' : '';
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($transfer) ? 'Edit Hawala Transfer' : 'Add New Hawala Transfer'; ?></h3>
    </div>

    <?= form_open(isset($transfer) ? 'admin/hawala_transfer/edit/' . $transfer->id : 'admin/hawala_transfer/create', ['id' => 'transferForm']); ?>
    <div class="card-body">
        <div class="row">
            <!-- From Hawala -->
            <div class="form-group col-md-6">
                <label for="from_id">From Hawala</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-arrow-circle-left"></i></div></div>
                    <select name="from_id" id="from_id" class="form-control" required <?= $disable_fields ?>>
                        <option value="">Select From Hawala</option>
                        <?php foreach ($hawalas as $h): ?>
                            <option value="<?= $h->hawala_id ?>" data-currency="<?= $h->currency ?>"
                                <?= set_select('from_id', $h->hawala_id, isset($transfer) && $transfer->from_id == $h->hawala_id); ?>>
                                <?= $h->mark ?> (<?= strtoupper($h->currency) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- To Hawala -->
            <div class="form-group col-md-6">
                <label for="to_id">To Hawala</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-arrow-circle-right"></i></div></div>
                    <select name="to_id" id="to_id" class="form-control" required <?= $disable_fields ?>>
                        <option value="">Select To Hawala</option>
                        <?php foreach ($hawalas as $h): ?>
                            <option value="<?= $h->hawala_id ?>" data-currency="<?= $h->currency ?>"
                                <?= set_select('to_id', $h->hawala_id, isset($transfer) && $transfer->to_id == $h->hawala_id); ?>>
                                <?= $h->mark ?> (<?= strtoupper($h->currency) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- From Amount -->
            <div class="form-group col-md-6">
                <label for="amount_from">Amount (From Currency)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-money-bill-wave"></i></div></div>
                    <input type="text" class="form-control" id="amount_from" name="amount_from"
                        value="<?= set_value('amount_from', isset($transfer) ? $transfer->amount_from : ''); ?>"
                        oninput="formatAmount(this)" required>
                </div>
            </div>

            <!-- To Amount -->
            <div class="form-group col-md-6">
                <label for="amount_to">Amount (To Currency)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-money-check-alt"></i></div></div>
                    <input type="text" class="form-control" id="amount_to" name="amount_to"
                        value="<?= set_value('amount_to', isset($transfer) ? $transfer->amount_to : ''); ?>"
                        oninput="formatAmount(this)" required>
                </div>
            </div>

            <!-- Manual Exchange Rate (Optional) -->
            <div class="form-group col-md-6">
                <label for="rate">Exchange Rate (Optional for Log)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-exchange-alt"></i></div></div>
                    <input type="text" class="form-control" id="rate" name="rate"
                        value="<?= set_value('rate', isset($transfer) ? $transfer->rate : ''); ?>"
                        oninput="formatAmount(this)">
                </div>
                <small class="text-muted">Enter exchange rate for documentation purposes only</small>
            </div>

            <!-- Date -->
            <div class="form-group col-md-6">
                <label for="date">Date</label>
                <div class="input-group">
                    <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                    <input type="text" class="form-control" id="datepicker" name="date"
                        value="<?= set_value('date', isset($transfer) ? $transfer->date : date('Y-m-d')); ?>" required>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group col-md-12">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"><?= set_value('notes', isset($transfer) ? $transfer->notes : ''); ?></textarea>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" id="submit_btn" class="btn btn-primary"><?= isset($transfer) ? 'Update' : 'Create'; ?> Transfer</button>
        <a href="<?= site_url('admin/hawala_transfer'); ?>" class="btn btn-secondary">Cancel</a>
    </div>
    <?= form_close(); ?>
</div>

<!-- Dependencies -->
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Validation Script -->
<script>
$(document).ready(function () {
    // Initialize Flatpickr with custom settings
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        defaultDate: "<?= isset($transfer) ? $transfer->date : date('Y-m-d'); ?>",
        theme: "material_blue",
        allowInput: true,
        clickOpens: true,
        maxDate: "today", // Prevent future dates
        locale: {
            firstDayOfWeek: 1 // Start week on Monday
        },
        onChange: function(selectedDates, dateStr, instance) {
            // Additional validation if needed
            if (selectedDates[0] > new Date()) {
                Swal.fire('Warning', 'Future dates are not allowed', 'warning');
                instance.setDate(new Date());
            }
        }
    });

    // Setup form validation
    $("#transferForm").validate({
        rules: {
            from_id: { required: true },
            to_id: { 
                required: true,
                notEqual: "#from_id"
            },
            amount_from: { required: true, number: true },
            amount_to: { required: true, number: true },
            date: { required: true },
            rate: { number: true }
        },
        messages: {
            from_id: { required: "Please select a from hawala" },
            to_id: { 
                required: "Please select a to hawala",
                notEqual: "From and To Hawala cannot be the same"
            },
            amount_from: { 
                required: "Please enter amount", 
                number: "Enter a valid number" 
            },
            amount_to: { 
                required: "Please enter amount", 
                number: "Enter a valid number" 
            },
            date: { required: "Please choose a transfer date" },
            rate: { number: "Enter a valid exchange rate" }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            showConfirmationDialog();
            return false;
        }
    });

    // Custom validation method to check if from and to are different
    $.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value !== $(param).val();
    }, "From and To Hawala cannot be the same");

    // Intercept form submission
    $('#transferForm').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            showConfirmationDialog();
        }
    });
});

/**
 * Format amount input and display
 */
function formatAmount(input) {
    let value = input.value.replace(/[^0-9.]/g, '');
    
    if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf('.'));
    }
    
    const parts = value.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    
    input.value = parts.join('.');
    validateAmount(input);
}

/**
 * Show confirmation dialog before submission
 */
function showConfirmationDialog() {
    // Get the formatted date from Flatpickr
    const fp = $("#datepicker")[0]._flatpickr;
    const formattedDate = fp.selectedDates[0] ? fp.formatDate(fp.selectedDates[0], "F j, Y") : $('#datepicker').val();
    
    // Get other form values
    const amountFrom = $('#amount_from').val();
    const amountTo = $('#amount_to').val();
    const rate = $('#rate').val();
    const fromHawala = $('#from_id option:selected').text();
    const toHawala = $('#to_id option:selected').text();
    const notes = $('#notes').val();
    
    // Get currency info
    const fromCurrency = $('#from_id option:selected').data('currency').toUpperCase();
    const toCurrency = $('#to_id option:selected').data('currency').toUpperCase();

    // SweetAlert2 confirmation dialog
    Swal.fire({
        title: 'Confirm Transfer',
        html: `
            <div style="font-size: 16px; text-align: left;">
                <p>Please review transfer details:</p>
                <div style="margin-top: 1rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.5rem;">
                            <strong>⬅️ From:</strong>
                            <span class="swal2-highlight">${fromHawala}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>➡️ To:</strong>
                            <span class="swal2-highlight">${toHawala}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>💵 Amount Sent:</strong>
                            <span class="swal2-highlight" style="color: #16a34a; font-weight: 600;">${amountFrom} ${fromCurrency}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>💰 Amount Received:</strong>
                            <span class="swal2-highlight" style="color: #16a34a; font-weight: 600;">${amountTo} ${toCurrency}</span>
                        </li>
                        ${rate ? `
                        <li style="margin-bottom: 0.5rem;">
                            <strong>🔁 Exchange Rate:</strong>
                            <span class="swal2-highlight">${rate}</span>
                        </li>
                        ` : ''}
                        <li style="margin-bottom: 0.5rem;">
                            <strong>📅 Date:</strong>
                            <span class="swal2-highlight">${formattedDate}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>📝 Notes:</strong>
                            <span class="swal2-highlight">${notes || 'None'}</span>
                        </li>
                    </ul>
                </div>
                <p style="margin-top: 1rem; font-size: 15px; color: #6b7280;">
                    Make sure all information is correct.
                </p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirm Transfer',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusConfirm: false,
        customClass: {
            popup: 'swal2-popup',
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            processTransfer();
        }
    });
}

/**
 * Process the transfer via AJAX
 */
function processTransfer() {
    // Show loading state
    Swal.fire({
        title: 'Processing Transfer',
        html: '<div class="swal2-content-custom">Please wait while we process your transfer...</div>',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Submit the form via AJAX
    $.ajax({
        url: $('#transferForm').attr('action'),
        type: 'POST',
        data: $('#transferForm').serialize(),
        success: function(response) {
            showSuccessMessage(response);
        },
        error: function(xhr) {
            showErrorMessage(xhr);
        }
    });
}

/**
 * Show success message after transfer
 */
function showSuccessMessage(response) {
    const fromHawala = $('#from_id option:selected').text();
    const toHawala = $('#to_id option:selected').text();
    const amountFrom = $('#amount_from').val();
    const amountTo = $('#amount_to').val();
    const fromCurrency = $('#from_id option:selected').data('currency').toUpperCase();
    const toCurrency = $('#to_id option:selected').data('currency').toUpperCase();
    
    Swal.fire({
        title: 'Transfer Successful!',
        html: `
            <div style="text-align:center;">
                <div class="swal2-content-custom">
                    <p><strong class="swal2-highlight">${amountFrom} ${fromCurrency}</strong> has been successfully transferred to:</p>
                    <p><strong class="swal2-highlight">${toHawala}</strong></p>
                    <p>Received amount: <strong>${amountTo} ${toCurrency}</strong></p>
                </div>
            </div>
        `,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonText: 'View Hawala List',
        showCancelButton: true,
        cancelButtonText: 'New Transfer',
        focusConfirm: false,
        customClass: {
            popup: 'swal2-popup',
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state before redirect
            Swal.fire({
                title: 'Redirecting...',
                html: '<div class="swal2-content-custom">Taking you to the hawala list page</div>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 1500,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '<?= site_url('admin/hawala_transfer') ?>';
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // When "New Transfer" is clicked
            window.location.href = '<?= site_url('admin/hawala_transfer/create') ?>';
        }
    });
}

/**
 * Show error message if transfer fails
 */
function showErrorMessage(xhr) {
    let errorMessage = 'There was an error processing your transfer. Please try again.';
    
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    }
    
    Swal.fire({
        title: 'Transfer Failed',
        html: `<div class="swal2-content-custom">${errorMessage}</div>`,
        icon: 'error',
        confirmButtonText: 'Try Again',
        customClass: {
            popup: 'swal2-popup',
            confirmButton: 'swal2-confirm'
        }
    });
}

/**
 * Validate amount input
 */
function validateAmount(input) {
    const value = input.value.replace(/,/g, '');
    if (isNaN(value) || parseFloat(value) <= 0) {
        $(input).addClass('is-invalid');
        return false;
    } else {
        $(input).removeClass('is-invalid');
        return true;
    }
}
</script>