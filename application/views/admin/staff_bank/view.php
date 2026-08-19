<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?></title>
  
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    .profile-balance-combo {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }
    
    .bank-cards {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
    }
    
    .profile-card {
      border-left: 4px solid #007bff;
      background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, white 100%);
    }
    
    .balance-card {
      border-left: 4px solid #28a745;
      background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, white 100%);
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
    }
    
    .profile-name {
      font-size: 1.8rem;
      font-weight: 700;
    }
    
    .balance-amount {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1e7e34;
    }
    
    .bank-card {
      border-left: 4px solid #17a2b8;
      background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, white 100%);
      position: relative;
    }
    
    .bank-actions {
      position: absolute;
      top: 10px;
      right: 10px;
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

    
    @media (max-width: 768px) {
      .profile-balance-combo {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= $title ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('admin/staff_bank') ?>">Staff Banks</a></li>
            <li class="breadcrumb-item active"><?= $staff->name ?></li>
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
            <div class="profile-card card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="profile-badge">
                    <i class="fas fa-user"></i>
                  </div>
                  <div class="ml-3">
                    <h1 class="profile-name m-0"><?= htmlspecialchars($staff->name) ?></h1>
                    <div class="text-muted">Employee ID: <?= $staff->employee_id ?? 'N/A' ?></div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="balance-card card">
              <div class="card-body d-flex align-items-center">
                <div class="bg-success text-white p-3 rounded mr-3">
                  <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <div>
                  <div class="text-muted text-uppercase small">Total Balance</div>
                  <div class="balance-amount"><?= number_format($total_balance, 2) ?> ETB</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bank Accounts Section -->
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="card-title m-0">
              <i class="fas fa-university mr-2"></i> Bank Accounts
            </h2>
            <?php if($permissions['add']): ?>
              <a href="<?= site_url('admin/staff_bank/create?staff_id='.$staff->staff_id) ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Account
              </a>
            <?php endif; ?>
          </div>
          
          <?php if(empty($accounts)): ?>
            <div class="alert alert-info">No bank accounts found for this staff member.</div>
          <?php else: ?>
            <div class="bank-cards">
              <?php foreach($accounts as $account): ?>
                <div class="bank-card card">
                  <div class="card-body">
                    <?php if($permissions['edit'] || $permissions['delete']): ?>
                      <div class="bank-actions dropdown">
                        <button class="btn btn-sm btn-link text-dark" type="button" data-toggle="dropdown">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                          <?php if($permissions['edit']): ?>
                            <a class="dropdown-item" href="<?= site_url('admin/staff_bank/edit/'.$account->id) ?>">
                              <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                          <?php endif; ?>
                          <?php if($permissions['delete']): ?>
                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('<?= site_url('admin/staff_bank/delete') ?>', <?= $account->id ?>)">
                              <i class="fas fa-trash mr-2"></i> Delete
                            </a>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <div class="d-flex align-items-center mb-3">
                    <div class="bank-logo">
                    <img src="<?php echo base_url('uploads/banks/' . strtolower($account->name) . '.png') ?>" alt="<?php echo $account->name; ?>">
                  </div>
                      <div>
                        <h4 class="m-0"><?= htmlspecialchars($account->name) ?></h4>
                        <small class="text-muted"><?= htmlspecialchars($account->number) ?></small>
                      </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <div class="text-muted small">Current Balance</div>
                        <div class="h4 text-primary"><?= number_format($account->balance, 2) ?> ETB</div>
                      </div>
                      <div class="text-right">
                        <div class="text-muted small">Created</div>
                        <div><?= date('M d, Y', strtotime($account->created_at)) ?></div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
function confirmDelete(url, id) {
    if (confirm('Are you sure you want to delete this bank account?')) {
        window.location.href = url + '/' + id;
    }
}
</script>
</body>
</html>