<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-piggy-bank"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Accounts</span>
                    <span class="info-box-number">
                       
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-university"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Banks Represented</span>
                    <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Balance</span>
                    <span class="info-box-number"> ETB</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Staff With Accounts</span>
                    <span class="info-box-number"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-university mr-1"></i>
            Staff Bank Accounts
        </h3>
        <div class="card-tools">
            <?php if(has_permission('staff_bank', 'add_staff_bank')): ?>
                <a href="<?= base_url('admin/staff_bank/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Account
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card-body">
        <table id="staffBankTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><i class="fas fa-user mr-1"></i> Staff Member</th>
                    <th><i class="fas fa-university mr-1"></i> Bank Name</th>
                    <th><i class="fas fa-credit-card mr-1"></i> Account Number</th>
                    <th><i class="fas fa-dollar-sign mr-1"></i> Balance</th>
                    <th><i class="fas fa-calendar-alt mr-1"></i> Created At</th>
                    <th><i class="fas fa-cog mr-1"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($accounts as $account): ?>
                <tr>
                    <td>
                        <a href="<?= site_url('admin/staff/view/'.$account->staff_id) ?>">
                            <?= safe_html($account->staff_name) ?>
                        </a>
                    </td>
                    <td><?= safe_html($account->name) ?></td>
                    <td><?= safe_html($account->number) ?></td>
                    <td class="total-balance-highlight">
                        <?= number_format($account->balance, 2) ?> ETB
                    </td>
                    <td><?= date('M d, Y', strtotime($account->created_at)) ?></td>
                    <td>
                        <div class="d-flex">
                            <?php if(has_permission('staff_bank', 'edit_staff_bank')): ?>
                                <a href="<?= site_url('admin/staff_bank/edit/'.$account->id) ?>" class="btn btn-sm btn-default action-btn" title="Edit">
                                    <i class="fas fa-edit text-info"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if(has_permission('staff_bank', 'delete_staff_bank')): ?>
                                <button class="btn btn-sm btn-default action-btn" title="Delete" 
                                    onclick="confirmDelete('<?= site_url('admin/staff_bank/delete') ?>', <?= $account->id ?>)">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>

<script>
$(function () {
    $("#staffBankTable").DataTable({
        "responsive": true, 
        "lengthChange": true, 
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"],
        "order": [[4, "desc"]], // Default sort by created_at descending
        "columnDefs": [
            { "orderable": false, "targets": [5] } // Disable sorting for actions column
        ]
    }).buttons().container().appendTo('#staffBankTable_wrapper .col-md-6:eq(0)');
});

function confirmDelete(url, id) {
    if (confirm('Are you sure you want to delete this bank account?')) {
        $.ajax({
            url: url + '/' + id,
            type: 'POST',
            dataType: 'json',
            data: {
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    }
}
</script>