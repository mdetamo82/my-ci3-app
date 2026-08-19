<div class="container-fluid">
<div class="row">
  <?php foreach ($currency_summary as $item): ?>
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box currency-box" data-currency="<?= $item->currency ?>">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><?= $item->currency ?></span>
          <span class="info-box-number"><?= $item->count ?></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="hawalaModal" tabindex="-1" role="dialog" aria-labelledby="hawalaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hawalaModalLabel">Hawala Members</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead><tr><th>Name</th><th>Mark</th><th>Balance</th></tr></thead>
          <tbody id="hawalaList"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <strong>Total Balance: <span id="totalBalance">0</span></strong>
      </div>
    </div>
  </div>
</div>

          
       
    <div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-1"></i> Hawala Members</h3>
        <div class="card-tools">
            <a href="<?= base_url('admin/hawala/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Hawala
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><i class="fas fa-tag mr-1"></i> Mark</th>
                    <th><i class="fas fa-user mr-1"></i> Name</th>
                    <th><i class="fas fa-phone mr-1"></i> Mobile</th>
                    <th><i class="fas fa-money-bill-alt mr-1"></i> Currency</th>
                    <th><i class="fas fa-dollar-sign mr-1"></i> Balance</th>
                    <th><i class="fas fa-info-circle mr-1"></i> Status</th>
                    <th><i class="fas fa-cog mr-1"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hawalas as $hawala): ?>
                <tr>
                    <td>
                        <a href="<?= site_url('admin/hawala/view_hawala/' . $hawala->hawala_id); ?>">
                            <?= safe_html($hawala->mark); ?>
                        </a>
                    </td>
                    <td><?= safe_html($hawala->name); ?></td>
                    <td><?= safe_html($hawala->mobile); ?></td>
                    <td><?= safe_html($hawala->currency); ?></td>
                    <td class="total-balance-highlight">
                        <?= isset($hawala->balance) ? safe_html(number_format($hawala->balance, 2)) : '0.00'; ?> <?= safe_html($hawala->currency); ?>
                    </td>
                    <td>
                        <?php if ($hawala->is_active == 1): ?>
                            <span class="badge badge-success">Active</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="<?= site_url('admin/hawala/view_hawala/' . $hawala->hawala_id) ?>" class="btn btn-sm btn-default action-btn" title="View">
                                <i class="fas fa-eye text-success"></i>
                            </a>
                            <a href="<?= site_url('admin/hawala/edit/' . $hawala->hawala_id) ?>" class="btn btn-sm btn-default action-btn" title="Edit">
                                <i class="fas fa-edit text-info"></i>
                            </a>
                            <button class="btn btn-sm btn-default action-btn" title="Deactivate" onclick="confirmDelete('<?= site_url('admin/hawala/delete') ?>', <?= $hawala->hawala_id ?>, null, true)">
                                <i class="fas fa-user-slash text-warning"></i>
                            </button>
                            <button class="btn btn-sm btn-default action-btn" title="Permanently Delete" onclick="confirmDelete('<?= site_url('admin/hawala/delete') ?>', <?= $hawala->hawala_id ?>)">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
            <script src="<?= base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
            <script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<script>
$(document).ready(function () {
  $('.currency-box').on('click', function () {
    const currency = $(this).data('currency');

    // CSRF tokens from meta tags
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const data = {};
    data[csrfName] = csrfToken;

    $.ajax({
      url: '<?= site_url('admin/hawala/get_hawalas_by_currency/') ?>' + currency,
      method: 'POST', // use POST to include CSRF token
      data: data,
      dataType: 'json',
      success: function (data) {
        let html = '';
        let total = 0;
        data.forEach(h => {
          html += `<tr><td>${h.name}</td><td>${h.mark}</td><td>${parseFloat(h.balance).toFixed(2)}</td></tr>`;
          total += parseFloat(h.balance);
        });
        $('#hawalaList').html(html);
        $('#totalBalance').text(total.toFixed(2));
        $('#hawalaModalLabel').text(`Hawala Members - ${currency}`);
        $('#hawalaModal').modal('show');
      }
    });
  });
});
</script>
