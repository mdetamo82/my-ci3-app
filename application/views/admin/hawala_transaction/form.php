<?php
$disable_fields = isset($lock_fields) && $lock_fields ? 'disabled' : '';
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <?= isset($transaction) ? 'Edit Transaction' : 'Add New Transaction'; ?>
        </h3>
    </div>

    <?= form_open(
        isset($transaction) ? 'admin/hawala_transaction/edit/' . $transaction->id : 'admin/hawala_transaction/create',
        ['id' => 'transactionForm']
    ); ?>
        <div class="card-body">
            <div class="row">
                <!-- Hawala -->
                <div class="form-group col-md-6">
                    <label for="hawala_id">Hawala</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <select name="hawala_id" id="hawala_id" class="form-control" required <?= $disable_fields ?>>
                            <option value="">Select Hawala</option>
                            <?php foreach ($hawalas as $h): ?>
                                <option 
                                    value="<?= $h->hawala_id ?>"  data-currency="<?= $h->currency ?>"
                                    <?= set_select('hawala_id', $h->hawala_id, isset($transaction) && $transaction->hawala_id == $h->hawala_id); ?>>
                                    <?= $h->mark ?> (<?= strtoupper($h->currency) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Amount -->
                <div class="form-group col-md-6">
                    <label for="birr">Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-money-bill"></i>
                            </div>
                        </div>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="birr" 
                            name="amount"
                            value="<?= set_value('birr', isset($transaction) ? $transaction->amount : ''); ?>" 
                            oninput="formatAmount(this)" 
                            required
                        >
                    </div>
                </div>

                <!-- Destination (Visible only for Income transactions) -->
                <div class="form-group col-md-6" id="destination-group" style="<?= ((isset($transaction) && $transaction->type == 'Income') || (!empty($forced_type) && $forced_type == 'Income')) ? '' : 'display: none;' ?>">
                    <label for="destination">Destination</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        <select name="destination" id="destination" class="form-control" <?= ((isset($transaction) && $transaction->type == 'Income') || (!empty($forced_type) && $forced_type == 'Income')) ? 'required' : '' ?>>
                            <option value="">Select Destination</option>
                            <option value="Cash Box" <?= set_select('destination', 'Cash Box', isset($transaction) && $transaction->destination == 'Cash Box'); ?>>
                                Cash Box
                            </option>
                            <option value="Vendor" <?= set_select('destination', 'Vendor', isset($transaction) && $transaction->destination == 'Vendor'); ?>>
                                Vendor
                            </option>
                            <option value="Other" <?= set_select('destination', 'Other', isset($transaction) && $transaction->destination == 'Other'); ?>>
                                Other
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group col-md-6">
                    <label for="date">Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="datepicker" 
                            name="date"
                            value="<?= set_value('date', isset($transaction) ? $transaction->date : date('Y-m-d')); ?>"
                            placeholder="Select date..." 
                            required
                        >
                    </div>
                </div>

                <!-- Type -->
                <div class="form-group col-md-6">
                    <label for="type">Type</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                        </div>
                        <?php if (!empty($forced_type)): ?>
                            <!-- Force type (Income/Expense) passed from controller -->
                            <input type="hidden" name="type" value="<?= $forced_type ?>">
                            <input type="text" id="types" class="form-control bg-light" value="<?= $forced_type ?>" readonly >
                        <?php else: ?>
                            <select name="type" id="type" class="form-control" required <?= $disable_fields ?>>
                                <option value="">Select Type</option>
                                <option value="Income" <?= set_select('type', 'Income', isset($transaction) && $transaction->type == 'Income'); ?>>
                                    Income
                                </option>
                                <option value="Expense" <?= set_select('type', 'Expense', isset($transaction) && $transaction->type == 'Expense'); ?>>
                                    Expense
                                </option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group col-md-12">
                    <label for="notes">Notes</label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        class="form-control" 
                        rows="3"
                    ><?= set_value('notes', isset($transaction) ? $transaction->notes : ''); ?></textarea>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" id="submit_btn" class="btn btn-primary">
                <?= isset($transaction) ? 'Update' : 'Create'; ?> Transaction
            </button>
            <a href="<?= site_url('admin/hawala_transaction'); ?>" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    <?= form_close(); ?>
</div>

<!-- Scripts -->
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>

<!-- Add Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    // Initialize Flatpickr with custom settings
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        defaultDate: "<?= isset($transaction) ? $transaction->date : date('Y-m-d'); ?>",
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
    const isEditingIncome = "<?= (isset($transaction) && $transaction->type == 'Income') ? 'true' : 'false' ?>";
    const isForcedIncome = "<?= (!empty($forced_type) && $forced_type == 'Income') ? 'true' : 'false' ?>";
    const validationRules = {
        hawala_id: { required: true },
        birr: { required: true, number: true },
        date: { required: true }
    };

    // Add type validation only when not forced_type
    if (isForcedIncome === 'false') {
        validationRules.type = { required: true };
    }

    // Add destination validation when:
    // 1. Editing an income transaction, or
    // 2. forced_type is Income
    if (isEditingIncome === 'true' || isForcedIncome === 'true') {
        validationRules.destination = { required: true };
    }

    setupValidation('#transactionForm', validationRules, {
        hawala_id: { required: "Please select a hawala member" },
        type: { required: "Please choose transaction type" },
        birr: { required: "Please enter amount", number: "Enter a valid number" },
        date: { required: "Please choose a transaction date" },
        destination: { required: "Please select a destination" }
    });

    // Intercept form submission
    $('#transactionForm').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            showConfirmationDialog();
        }
    });

    // Show/hide destination field based on transaction type
    function toggleDestinationField() {
        const selectedType = $('#type').val();
        const isEditingIncome = "<?= (isset($transaction) && $transaction->type == 'Income') ? 'true' : 'false' ?>";
        const forcedType = "<?= !empty($forced_type) ? $forced_type : '' ?>";

        // Show destination only for:
        // 1. Income transactions (when selected)
        // 2. When editing existing Income transaction
        // 3. When forced_type is Income
        if (selectedType === 'Income' || isEditingIncome === 'true' || forcedType === 'Income') {
            $('#destination-group').slideDown();
            $('#destination').prop('required', true);
        } else {
            $('#destination-group').slideUp();
            $('#destination').prop('required', false);
        }
    }

    // Trigger change on load (in case of editing)
    toggleDestinationField();

    // Bind to type change event (only if not forced_type)
    <?php if (empty($forced_type)): ?>
        $('#type').on('change', toggleDestinationField);
    <?php endif; ?>
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
    $('#formatted-amount').text(parts.join('.'));
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
    const amount = $('#birr').val();
    const hawalaName = $('#hawala_id option:selected').text();
    const notes = $('#notes').val();
    const type = $('#type option:selected').text();
    const types = $('#types').val();
    const destination = $('#destination option:selected').text();
    // Get currency info
    const Currency = $('#hawala_id option:selected').data('currency').toUpperCase();
    
    // SweetAlert2 confirmation dialog
    Swal.fire({
        title: 'Confirm Transaction',
        html: `
            <div style="font-size: 16px; text-align: left;">
                <p>Please review transaction details:</p>
                <div style="margin-top: 1rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="margin-bottom: 0.5rem;">
                            <strong>💰 Transaction Type:</strong>
                            <span class="swal2-highlight" style="color: ${(type || types) === 'Income' ? '#16a34a' : '#dc2626'}; font-weight: 600;">
                                ${type || types}
                            </span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>💵 Amount:</strong>
                            <span class="swal2-highlight" style="color: #16a34a; font-weight: 600;">${amount} ${Currency}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>🧑‍💼 Hawala:</strong>
                            <span class="swal2-highlight">${hawalaName}</span>
                        </li>
                        <?php if ((isset($transaction) && $transaction->type == 'Income') || (!empty($forced_type) && $forced_type == 'Income')): ?>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>📍 Destination:</strong>
                            <span class="swal2-highlight">${destination || 'Not specified'}</span>
                        </li>
                        <?php endif; ?>
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
        confirmButtonText: 'Confirm Transaction',
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
            processTransaction();
        }
    });
}

/**
 * Process the transaction via AJAX
 */
function processTransaction() {
    // Show loading state
    Swal.fire({
        title: 'Processing Transaction',
        html: '<div class="swal2-content-custom">Please wait while we process your transaction...</div>',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Submit the form via AJAX
    $.ajax({
        url: $('#transactionForm').attr('action'),
        type: 'POST',
        data: $('#transactionForm').serialize(),
        success: function(response) {
            showSuccessMessage(response);
        },
        error: function(xhr) {
            showErrorMessage(xhr);
        }
    });
}

/**
 * Show success message after transaction
 */
function showSuccessMessage(response) {
    const hawalaId = $('#hawala_id').val();
    const amount = $('#birr').val();
    const hawalaName = $('#hawala_id option:selected').text();
    const type = $('#type option:selected').text() || "<?= !empty($forced_type) ? $forced_type : '' ?>";
    const Currency = $('#hawala_id option:selected').data('currency').toUpperCase();
    Swal.fire({
        title: 'Transaction Successful!',
        html: `
            <div style="text-align:center;">
                <div class="swal2-content-custom">
                    <p><strong class="swal2-highlight">${amount} ${Currency}</strong> (${type}) has been successfully processed for:</p>
                    <p><strong class="swal2-highlight">${hawalaName}</strong></p>
                </div>
            </div>
        `,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonText: 'View Hawala Profile',
        showCancelButton: true,
        cancelButtonText: 'New Transaction',
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
                html: '<div class="swal2-content-custom">Taking you to the hawala profile page</div>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 1500,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '<?= site_url('admin/hawala/view_hawala/') ?>' + hawalaId;
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // When "New Transaction" is clicked
            window.location.href = '<?= site_url('admin/hawala_transaction/create') ?>';
        }
    });
}

/**
 * Show error message if transaction fails
 */
function showErrorMessage(xhr) {
    let errorMessage = 'There was an error processing your transaction. Please try again.';
    
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    }
    
    Swal.fire({
        title: 'Transaction Failed',
        html: `<div class="swal2-content-custom">${errorMessage}</div>`,
        icon: 'error',
        confirmButtonText: 'Try Again',
        customClass: {
            popup: 'swal2-popup',
            confirmButton: 'swal2-confirm'
        }
    });
}
</script>