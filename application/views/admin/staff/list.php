
<div class="container-fluid">
<div class="row">
        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-wallet"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Balance</span>
              <span class="info-box-number"><?= number_format($grand_total, 2) ?> ETB</span>
          </div>
          <!-- /.info-box-content -->
      </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas  fa-building"></i></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Banks</span>
              <span class="info-box-number">10</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Active Staff</span>
                <span class="info-box-number">
                  10
                  <small>%</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-slash"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Inactive</span>
                <span class="info-box-number">41</span>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                                      <i class="fas fa-users mr-1"></i>
                                      Staff Members
                                  </h3>
                                  <div class="card-tools">
                                      <a href="<?= base_url('admin/staff/create') ?>" class="btn btn-primary btn-sm">
                                          <i class="fas fa-plus"></i> Add New Staff
                                      </a>
                                  </div>
                              </div>
                              
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
              
                                      <thead>
                                          <tr>
                                              <th><i class="fas fa-user mr-1"></i> Name</th>
                                              <th><i class="fas fa-dollar-sign mr-1"></i> Balance</th>
                                              <th><i class="fas fa-phone mr-1"></i> Phone</th>
                                              <th><i class="fas fa-map-marker-alt mr-1"></i> Address</th>
                                              <th><i class="fas fa-building mr-1"></i> Department</th>
                                              <th><i class="fas fa-info-circle mr-1"></i> Status</th>
                                              <th><i class="fas fa-cog mr-1"></i> Actions</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach($staff as $s): ?>
                                          <tr>
                                              <td>
                                                  <a href="<?php echo site_url('admin/staff/view_staff/'.$s->staff_id); ?>">
                                                      <?php echo safe_html($s->name); ?>
                                                  </a>
                                              </td>
                                                <?php    $class = ($s->balance < 0) ? 'text-danger' : 'text-success';  ?>
                                              <td class="total-balance-highlight <?= $class ?>">
                                                  <?= safe_html(number_format($s->balance, 2)) ?>
                                              </td>

                                              <td><?php echo safe_html($s->mobile) ?></td>
                                              <td><?php echo safe_html($s->address) ?></td>
                                              <td><?php echo safe_html($s->department) ?></td>
                                              <td>
                                                  <?php if ($s->is_active == 1): ?>
                                                      <span class="badge badge-status badge-success">Active</span>
                                                  <?php else: ?>
                                                      <span class="badge badge-status badge-danger">Inactive</span>
                                                  <?php endif; ?>
                                              </td>

                                              <td>
                                                  <div class="d-flex">
                                                      <a href="<?= site_url('admin/staff/view_staff/'.$s->staff_id) ?>" class="btn btn-sm btn-default action-btn" title="View">
                                                          <i class="fas fa-eye text-success"></i>
                                                      </a>
                                                      <a href="<?= site_url('admin/staff/edit/'.$s->staff_id) ?>" class="btn btn-sm btn-default action-btn"  title="Edit">
                                                      <i class="fas fa-edit text-info"></i>
                                                      </a>
                                                    <button class="btn btn-sm btn-default action-btn" title="Deactivate" onclick="confirmDelete('<?= site_url('admin/staff/delete') ?>', <?= $s->staff_id ?>, null, true)">
                                                          <i class="fas fa-user-slash text-warning"></i>
                                                      </button>
                                                      <button class="btn btn-sm btn-default action-btn" title="Permanently Delete" onclick="confirmDelete('<?= site_url('admin/staff/delete') ?>', <?= $s->staff_id ?>)">
                                                          <i class="fas fa-trash-alt text-danger"></i>
                                                      </button>
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