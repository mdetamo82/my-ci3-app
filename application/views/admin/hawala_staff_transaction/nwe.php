<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title"><?= isset($transaction) ? 'Edit Transaction' : 'Add New Transaction'; ?></h3>
    </div>

    <?= form_open(isset($transaction) ? 'admin/staff_transaction/edit/' . $transaction->id : 'admin/staff_transaction/create', ['id' => 'transactionForm']); ?>
        <div class="card-body">
            <div class="row">

                <!-- Staff -->
                <div class="form-group col-md-6">
                    <label for="staff_id">Staff</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        <select name="staff_id" id="staff_id" class="form-control" required>
                            <option value="">Select Staff</option>
                            <?php foreach ($staffs as $s): ?>
                                <option value="<?= $s->staff_id ?>" <?= set_select('staff_id', $s->staff_id, isset($transaction) && $transaction->staff_id == $s->staff_id); ?>>
                                    <?= $s->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Bank Account -->
                <div class="form-group col-md-6">
                    <label for="bank_id">Bank Account</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-university"></i></div></div>
                        <select name="bank_id" id="bank_id" class="form-control" required>
                            <option value="">Select Bank Account</option>
                        </select>
                    </div>
                </div>
                
                <!-- Amount in Birr -->
                <div class="form-group col-md-4">
                    <label for="birr">Amount (Birr)</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-money-bill"></i></div></div>
                        <input type="text" class="form-control" id="birr" name="birr"
                               value="<?= set_value('birr', isset($transaction) ? $transaction->birr : ''); ?>" 
                               oninput="calculateAmountFromBirr(this)" required>
                    </div>
                </div>

                <!-- Exchange Rate -->
                <div class="form-group col-md-4">
                    <label for="exchange_rate">Exchange Rate</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-exchange-alt"></i></div></div>
                        <input type="text" class="form-control" id="exchange_rate" name="exchange_rate"
                               value="<?= set_value('exchange_rate', isset($transaction) ? $transaction->exchange_rate : ''); ?>"
                               oninput="calculateAmountFromBirr()">
                    </div>
                </div>

                <!-- Calculated Amount -->
                <div class="form-group col-md-4">
                    <label for="amount">Calculated Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calculator"></i></div></div>
                        <input type="text" class="form-control" id="amount" name="amount" readonly
                               value="<?= set_value('amount', isset($transaction) ? $transaction->amount : ''); ?>">
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group col-md-6">
                    <label for="date">Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                        <input type="text" class="form-control" id="datepicker" name="date"
                               value="<?= set_value('date', isset($transaction) ? $transaction->date : date('Y-m-d')); ?>"
                               placeholder="Select date..." required>
                    </div>
                </div>

                <!-- Type -->
               <div class="form-group col-md-6">
              <label for="type">Type</label>
              <div class="input-group">
                  <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-exchange-alt"></i></div>
                  </div>
                  <?php if (!empty($forced_type)): ?>
                      <!-- Force type (Income/Expense) passed from controller -->
                      <input type="hidden" name="type" value="<?= $forced_type ?>">
                      <input type="text" class="form-control bg-light" value="<?= $forced_type ?>" readonly>
                  <?php else: ?>
                      <select name="type" id="type" class="form-control" required>
                          <option value="">Select Type</option>
                          <option value="Income" <?= set_select('type', 'Income', isset($transaction) && $transaction->type == 'Income'); ?>>Income</option>
                          <option value="Expense" <?= set_select('type', 'Expense', isset($transaction) && $transaction->type == 'Expense'); ?>>Expense</option>
                      </select>
                  <?php endif; ?>
                  </div>
              </div>

                <!-- Notes -->
                <div class="form-group col-md-12">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3"><?= set_value('notes', isset($transaction) ? $transaction->notes : ''); ?></textarea>
                </div>

            </div>
        </div>

        <div class="card-footer">
            <button type="submit" id="submit_btn" class="btn btn-primary"><?= isset($transaction) ? 'Update' : 'Create'; ?> Transaction</button>
            <a href="<?= site_url('admin/staff_transaction'); ?>" class="btn btn-secondary">Cancel</a>
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
    // Initialize Flatpickr
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        defaultDate: "<?= isset($transaction) ? $transaction->date : date('Y-m-d'); ?>",
        theme: "material_blue",
        maxDate: "today"
    });

    // Format initial values if they exist
    if($('#birr').val()) {
        formatCurrency(document.getElementById('birr'));
        calculateAmountFromBirr();
    }
    if($('#exchange_rate').val()) {
        formatCurrency(document.getElementById('exchange_rate'));
    }
});

// ===== CURRENCY FORMATTING AND CALCULATION FUNCTIONS =====

/**
 * Format currency input with commas
 */
function formatCurrency(input) {
    // Get the raw value without commas
    let value = input.value.replace(/[^0-9.]/g, '');
    
    // Remove extra decimal points
    if ((value.match(/\./g) || []).length > 1) {
        value = value.substring(0, value.lastIndexOf('.'));
    }
    
    // Split into whole and decimal parts
    const parts = value.split('.');
    
    // Add commas to the whole number part
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    
    // Rejoin and update the input value
    input.value = parts.join('.');
    
    // Return the raw numeric value for calculations
    return parseFloat(value.replace(/,/g, ''));
}

/**
 * Calculate amount based on birr and exchange rate
 */
function calculateAmountFromBirr(inputElement) {
    // Format the input if an element was passed
    if(inputElement) {
        formatCurrency(inputElement);
    }
    
    // Get the numeric values
    const birrValue = $('#birr').val().replace(/,/g, '') || 0;
    const rateValue = $('#exchange_rate').val().replace(/,/g, '') || 1;
    
    // Calculate the amount
    const calculatedAmount = parseFloat(birrValue) / parseFloat(rateValue);
    
    // Format and display the result
    if(!isNaN(calculatedAmount)) {
        $('#amount').val(calculatedAmount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    } else {
        $('#amount').val('');
    }
}

/**
 * Calculate birr based on amount and exchange rate
 */
function calculateBirrFromAmount(inputElement) {
    // Format the input if an element was passed
    if(inputElement) {
        formatCurrency(inputElement);
    }
    
    // Get the numeric values
    const amountValue = $('#amount').val().replace(/,/g, '') || 0;
    const rateValue = $('#exchange_rate').val().replace(/,/g, '') || 1;
    
    // Calculate the birr value
    const calculatedBirr = parseFloat(amountValue) * parseFloat(rateValue);
    
    // Format and display the result
    if(!isNaN(calculatedBirr)) {
        $('#birr').val(calculatedBirr.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    } else {
        $('#birr').val('');
    }
}

// ===== FORM VALIDATION AND SUBMISSION =====

// Setup form validation
$(function () {
    setupValidation('#transactionForm', {
        staff_id: { required: true },
        type: { required: true },
        birr: { required: true, number: true },
        date: { required: true }
    }, {
        staff_id: { required: "Please select a staff member" },
        type: { required: "Please choose transaction type" },
        birr: { required: "Please enter amount", number: "Enter a valid number" },
        date: { required: "Please choose a transaction date" }
    });

    // Intercept form submission
    $('#transactionForm').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            // Clean up formatted values before submission
            $('#birr').val($('#birr').val().replace(/,/g, ''));
            $('#exchange_rate').val($('#exchange_rate').val().replace(/,/g, ''));
            showConfirmationDialog();
        }
    });

    // Staff change handler for bank accounts
    $('#staff_id').on('change', function() {
        const staffId = $(this).val();
        $('#bank_id').html('<option value="">Loading...</option>');
        $('#submit_btn').prop('disabled', true);

        if (staffId) {
            $.ajax({
                url: '<?= base_url("admin/staff/get_banks_by_staff/") ?>' + staffId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let options = '<option value="">Select Bank Account</option>';
                    if (data.length > 0) {
                        $.each(data, function(index, bank) {
                            options += `<option value="${bank.id}">${bank.name} - ${bank.number} (${parseFloat(bank.balance).toFixed(2)} Birr)</option>`;
                        });
                        $('#submit_btn').prop('disabled', false);
                    } else {
                        options = '<option value="">No Bank Accounts Found</option>';
                        $('#submit_btn').prop('disabled', true);
                    }
                    $('#bank_id').html(options);
                },
                error: function() {
                    $('#bank_id').html('<option value="">Error loading banks</option>');
                    $('#submit_btn').prop('disabled', true);
                }
            });
        } else {
            $('#bank_id').html('<option value="">Select Staff First</option>');
            $('#submit_btn').prop('disabled', true);
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
    const staffName = $('#staff_id option:selected').text();
    const bankName = $('#bank_id option:selected').text();
    const notes = $('#notes').val();
    const type = $('#type option:selected').text();

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
                            <span class="swal2-highlight" style="color: ${type === 'Income' ? '#16a34a' : '#dc2626'}; font-weight: 600;">${type}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>💵 Amount:</strong>
                            <span class="swal2-highlight" style="color: #16a34a; font-weight: 600;">${amount} ETB</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>🧑‍💼 Staff:</strong>
                            <span class="swal2-highlight">${staffName}</span>
                        </li>
                        <li style="margin-bottom: 0.5rem;">
                            <strong>🏦 Bank Account:</strong>
                            <span class="swal2-highlight">${bankName || 'Not specified'}</span>
                        </li>
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
    const staffId = $('#staff_id').val();
    const amount = $('#birr').val();
    const staffName = $('#staff_id option:selected').text();
    const type = $('#type option:selected').text();
    
    Swal.fire({
        title: 'Transaction Successful!',
        html: `
            <div style="text-align:center;">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <h3 style="color:#16a34a; margin-top:15px;">Transaction Processed!</h3>
                <div class="swal2-content-custom">
                    <p><strong class="swal2-highlight">${amount} ETB</strong> (${type}) has been successfully processed for:</p>
                    <p><strong class="swal2-highlight">${staffName}</strong></p>
                </div>
            </div>
        `,
        icon: 'success',
        showConfirmButton: true,
        confirmButtonText: 'View Staff Profile',
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
                html: '<div class="swal2-content-custom">Taking you to the staff profile page</div>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 1500,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '<?= site_url('admin/staff/view_staff/') ?>' + staffId;
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // When "New Transaction" is clicked
            window.location.href = '<?= site_url('admin/staff_transaction/create') ?>';
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