
  
  <style>
    /* Profile & Balance Combo */
    .profile-balance-combo {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }
    /* Bank Accounts */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-lg);
        }

    /* Bank Cards Grid */
    .bank-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
    }
    
    /* Profile Card Styles */
    .profile-card {
      border-left: 4px solid #007bff;
      background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, white 100%);
      position: relative;
      overflow: hidden;
    }
    
    .profile-card::before {
      content: "";
      position: absolute;
      top: -20px;
      right: -20px;
      width: 100px;
      height: 100px;
      background: rgba(0, 123, 255, 0.1);
      border-radius: 50%;
      opacity: 0.3;
    }
    
    .profile-badge {
      width: 48px;
      height: 48px;
      background: #007bff;
      color: white;
      border-radius: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-weight: 600;
      box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
    }
    
    .profile-name {
      font-size: 2.25rem;
      font-weight: 700;
      margin: 0;
      letter-spacing: -0.5px;
      position: relative;
      display: inline-block;
    }
    
    .profile-name::after {
      content: "";
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 40px;
      height: 3px;
      background: #007bff;
      border-radius: 50px;
    }
    
    /* Balance Card Styles */
    .balance-card {
      border-left: 4px solid #28a745;
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, white 100%);
      position: relative;
      overflow: hidden;
    }
    
    .balance-card::before {
      content: "";
      position: absolute;
      top: -20px;
      right: -20px;
      width: 100px;
      height: 100px;
      background: rgba(40, 167, 69, 0.1);
      border-radius: 50%;
      opacity: 0.3;
    }
    
    .balance-icon {
      width: 48px;
      height: 48px;
      background: #28a745;
      color: white;
      border-radius: 0.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1.5rem;
      flex-shrink: 0;
      box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
    }
    
    .balance-amount {
      font-size: 2.25rem;
      font-weight: 700;
      color: #1e7e34;
      margin-bottom: 0.5rem;
      line-height: 1;
      letter-spacing: -0.5px;
    }
    
    /* Bank Card Styles */
    .bank-card {
      border-left: 4px solid #17a2b8;
      background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, white 100%);
      position: relative;
      overflow: hidden;
    }
    
    .bank-card::before {
      content: "";
      position: absolute;
      top: -20px;
      right: -20px;
      width: 100px;
      height: 100px;
      background: rgba(23, 162, 184, 0.1);
      border-radius: 50%;
      opacity: 0.3;
    }
    
    .bank-logo {
      width: 48px;
      height: 48px;
      background: white;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-sm);
      padding: var(--space-sm);
      flex-shrink: 0;
    }

    .bank-logo img {
      max-width: 100%;
      height: auto;
    }

    .bank-balance {
      font-size: 1.25rem;
      font-weight: 700;
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
      .bank-cards {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 768px) {
      .profile-balance-combo {
        grid-template-columns: 1fr;
      }
      
      .bank-cards {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
 

      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Staff Dashboard</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="content">
        <div class="container-fluid">
          <!-- Profile & Balance Section -->
          <div class="card">
            <div class="card-body">
              <div class="profile-balance-combo">
                <!-- Profile Card -->
                <div class="profile-card card">
                  <div class="card-body">
                    <div class="profile-header d-flex align-items-center">
                      <div class="profile-badge">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="ml-3">
                        <h1 class="profile-name"><?=$staff->name; ?></h1>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Balance Card -->
                <div class="balance-card card">
                  <div class="card-body d-flex align-items-center">
                    <div class="balance-icon">
                      <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="balance-content">
                      <div class="balance-label text-muted text-uppercase small">Total Balance</div>
                      <div class="balance-amount"><?= number_format($staff_total, 2) ?> ETB</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Bank Accounts Section -->
          <section class="transactions-container">
            <div class="section-header">
              <h2 class="section-title"><i class="fa fa-university"></i> Bank Accounts</h2>
              <button class="btn btn-primary">
                <a href="#" 
                  data-toggle="modal" 
                  data-target="#myModal" 
                  data-staff-id="<?= urlencode($staff->staff_id) ?>" 
                  style="color: white;">
                  <i class="fa fa-plus"></i> Add Bank Account
                </a>
              </button>
            </div>
            
           <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add Bank Account</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div id="modalContent">
                      <p class="text-center">Loading...</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Bank Cards -->
            <div class="bank-cards">
              <?php 
                $account_banks = $this->db->get_where('staff_bank', ['staff_id' => $staff->staff_id])->result(); 
                foreach ($account_banks as $account_bank) { 
              ?>
              <div class="bank-card card">
                <div class="card-body d-flex align-items-center">
                  <div class="bank-logo">
                    <img src="<?php echo base_url('uploads/banks/' . strtolower($account_bank->name) . '.png') ?>" alt="<?php echo $staff->name; ?>">
                  </div>
                  <div class="bank-details ml-3">
                    <div class="bank-name font-weight-bold"><?= $staff->name ?></div>
                    <div class="bank-balance"><?= number_format($account_bank->balance , 2) ?> ETB</div>
                    <div class="bank-account text-muted small">Acc: <?= number_format($account_bank->balance , 2)?></div>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </section>
        </div>
      </div>
      <div class="card">     
                <div class="card-header">
                                  <h3 class="card-title">
                                      <i class="fas fa-dollar-sign mr-1"></i>
                                      Recent Transactions
                                  </h3>
                                  
                              </div>
                              
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
              
                                      <thead>
                                         <tr>
                                            <th><i class="fa fa-list-ol me-1"></i> NO</th>
                                            <th><i class="fas fa-calendar-alt me-1"></i> Date</th>
                                            <th><i class="fas fa-sticky-note me-1"></i> Notes</th>
                                            <th><i class="fas fa-coins me-1"></i> Birr</th>
                                            <th><i class="fas fa-exchange-alt me-1"></i> Rate</th>
                                            <th><i class="fas fa-money-bill-wave me-1"></i> Amount</th>
                                            <th><i class="fas fa-info-circle me-1"></i>Trans Type</th>
                                            <th><i class="fas fa-info-circle me-1"></i> Type</th>
                                            <th><i class="fas fa-university mr-1"></i> Bank</th>
                                            <th><i class="fas fa-align-left me-1"></i> Descriptions</th>
                                            <th><i class="fas fa-tools me-1"></i> Action</th>
                                        </tr>

                                      </thead>
                                      <tbody>
                                          <?php $i = 1; foreach($trans as $s): ?>
                                            <tr style="<?= !is_null($s->updated_by) ? 'color: black; background-color: #fff3cd;' : '' ?>">
                                              <td>
                                                  <a href="<?php echo site_url('admin/staff/view_staff/'.$s->id); ?>">
                                                    <?= $i++ ?>
                                                  </a>
                                              </td>
                                              <td><?php echo safe_html($s->date); ?></td>
                                              <td><?php echo safe_html($s->notes); ?></td>
                                              <td class="total-balance-highlight">
                                                  <?php echo isset($s->birr) ? safe_html(number_format($s->birr, 2)) : '0.00'; ?>
                                              </td>
                                              <td>
                                                  <?php  if($s->rate != 0.00 )  { echo safe_html(number_format($s->rate, 2));  }else{  echo '-'; } ?>
                                              </td>
                                              <td>
                                                  <?php  if($s->amount != 0.00 )  { echo safe_html(number_format($s->amount, 2));  }else{  echo '-'; } ?>
                                              </td>
                                              <td><?php echo safe_html(ucfirst($s->transaction_type)) ?></td>
                                              <td>
                                                  <?php if ($s->type == "Income"): ?>
                                                      <span class="badge badge-status badge-success"><?php echo safe_html($s->type) ?></span>
                                                  <?php else: ?>
                                                      <span class="badge badge-status badge-danger"><?php echo safe_html($s->type) ?></span>
                                                  <?php endif; ?>
                                              </td>
                                              <td>
                                                  <?= !empty($s->bank_name) ? safe_html($s->bank_name) : '-' ?>
                                              </td>
                                              <td><?php echo safe_html($s->description) ?></td>
                                              <td>
                                                  <div class="d-flex">
                                                      <a href="<?= site_url('admin/staff_transaction/view/' . $s->id) ?>" class="btn btn-sm btn-default action-btn" title="View">
                                                          <i class="fas fa-eye text-success"></i>
                                                      </a>
                                                      <a href="<?= site_url('admin/staff_transaction/edit_route/'.$s->id) ?>" class="btn btn-sm btn-default action-btn"  title="Edit">
                                                      <i class="fas fa-edit text-info"></i>
                                                      </a>
                                                    
                                                      <button class="btn btn-sm btn-default action-btn" title="Permanently Delete" onclick="confirmDelete('<?= site_url('admin/staff_transaction/delete') ?>', <?= $s->id ?>)">
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

  <!-- JavaScript -->
  <script>
    $('#myModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var staffId = button.data('staff-id');
      
      // Fetch content dynamically
      $.ajax({
        url: "<?= base_url('admin/staff/modal_form_open') ?>",
        type: "GET",
        data: { id: staffId },
        success: function (response) {
          $('#modalContent').html(response); // Load the form inside modal
        },
        error: function () {
          $('#modalContent').html('<p class="text-danger">Error loading form.</p>');
        }
      });
    });
  </script>
