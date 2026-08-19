
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Bank Accounts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Bank Accounts</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">DD's Bank Accounts</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#addBankModal">
                                    <i class="fas fa-plus"></i> Add Account
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Bank Name</th>
                                            <th>Account Number</th>
                                            <th>Balance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($bank_accounts)) : ?>
                                            <?php foreach ($bank_accounts as $account) : ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($account->name) ?></td>
                                                    <td><?= htmlspecialchars($account->number) ?></td>
                                                    <td><?= number_format($account->balance, 2) ?> ETB</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary edit-account" 
                                                                data-id="<?= $account->id ?>"
                                                                data-name="<?= htmlspecialchars($account->name) ?>"
                                                                data-number="<?= htmlspecialchars($account->number) ?>"
                                                                data-balance="<?= $account->balance ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-account" data-id="<?= $account->id ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No bank accounts found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Bank Account Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankModalLabel">Add Bank Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addBankForm" method="post" action="<?= base_url('staff/add_bank_account') ?>">
                <div class="modal-body">
                    <input type="hidden" name="staff_id" value="1">
                    <div class="form-group">
                        <label for="name">Bank Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="number">Account Number</label>
                        <input type="text" class="form-control" id="number" name="number" required>
                    </div>
                    <div class="form-group">
                        <label for="balance">Initial Balance</label>
                        <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="0.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bank Account Modal -->
<div class="modal fade" id="editBankModal" tabindex="-1" role="dialog" aria-labelledby="editBankModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBankModalLabel">Edit Bank Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBankForm" method="post" action="<?= base_url('staff/update_bank_account') ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="staff_id" value="<?= $staff->id ?>">
                    <div class="form-group">
                        <label for="edit_name">Bank Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_number">Account Number</label>
                        <input type="text" class="form-control" id="edit_number" name="number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_balance">Balance</label>
                        <input type="number" step="0.01" class="form-control" id="edit_balance" name="balance" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this bank account? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Edit account button click handler
    $('.edit-account').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var number = $(this).data('number');
        var balance = $(this).data('balance');
        
        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_number').val(number);
        $('#edit_balance').val(balance);
        
        $('#editBankModal').modal('show');
    });

    // Delete account button click handler
    var accountIdToDelete;
    $('.delete-account').click(function() {
        accountIdToDelete = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete button click handler
    $('#confirmDelete').click(function() {
        $.ajax({
            url: '<?= base_url('staff/delete_bank_account') ?>',
            type: 'POST',
            data: { id: accountIdToDelete },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error deleting account: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting the account.');
            }
        });
    });

    // Form submission with AJAX
    $('#addBankForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error adding account: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while adding the account.');
            }
        });
    });

    $('#editBankForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error updating account: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating the account.');
            }
        });
    });
});
</script>