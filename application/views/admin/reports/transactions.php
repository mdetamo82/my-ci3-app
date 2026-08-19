<section class="content-header">
  <h1><i class="fas fa-file-alt mr-1"></i> Transaction Report</h1>
</section>

<section class="content">
  <div class="container-fluid">

    <!-- Filters -->
    <div class="card card-primary card-outline">
      <div class="card-header"><strong><i class="fas fa-filter mr-1"></i>Filter Transactions</strong></div>
      <div class="card-body">
        <form method="get" class="form-row">
          <div class="form-group col-md-2">
            <label for="from">From</label>
            <input type="date" name="from" class="form-control" value="<?= html_escape($filters['from']) ?>">
          </div>
          <div class="form-group col-md-2">
            <label for="to">To</label>
            <input type="date" name="to" class="form-control" value="<?= html_escape($filters['to']) ?>">
          </div>
          <div class="form-group col-md-2">
            <label for="type">Type</label>
            <select name="type" class="form-control">
              <option value="">All</option>
              <option value="Income" <?= $filters['type'] == 'Income' ? 'selected' : '' ?>>Income</option>
              <option value="Expense" <?= $filters['type'] == 'Expense' ? 'selected' : '' ?>>Expense</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="transaction_type">Category</label>
            <select name="transaction_type" class="form-control">
              <option value="">All</option>
              <option value="staff" <?= $filters['transaction_type'] == 'staff' ? 'selected' : '' ?>>Staff</option>
              <option value="hawala" <?= $filters['transaction_type'] == 'hawala' ? 'selected' : '' ?>>Hawala</option>
              <option value="loan" <?= $filters['transaction_type'] == 'loan' ? 'selected' : '' ?>>Loan</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="bank_id">Bank</label>
            <select name="bank_id" class="form-control">
              <option value="">All</option>
              <?php foreach ($banks as $b): ?>
                <option value="<?= $b->id ?>" <?= $filters['bank_id'] == $b->id ? 'selected' : '' ?>>
                  <?= html_escape($b->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="staff_id">Staff</label>
            <select name="staff_id" class="form-control">
              <option value="">All</option>
              <?php foreach ($staff as $s): ?>
                <option value="<?= $s->staff_id ?>" <?= $filters['staff_id'] == $s->staff_id ? 'selected' : '' ?>>
                  <?= html_escape($s->name) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-12 mt-2">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search mr-1"></i>Apply Filter
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?= number_format($summary->total_income ?? 0, 2) ?> <sup>ብር</sup></h3>
            <p>Total Income</p>
          </div>
          <div class="icon"><i class="fas fa-arrow-down"></i></div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3><?= number_format($summary->total_expense ?? 0, 2) ?> <sup>ብር</sup></h3>
            <p>Total Expense</p>
          </div>
          <div class="icon"><i class="fas fa-arrow-up"></i></div>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>
              <?= number_format(($summary->total_income ?? 0) - ($summary->total_expense ?? 0), 2) ?> <sup>ብር</sup>
            </h3>
            <p>Net Balance</p>
          </div>
          <div class="icon"><i class="fas fa-balance-scale"></i></div>
        </div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="card card-outline card-secondary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table mr-1"></i> Transaction List</h3>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-sm" id="transactionTable">
          <thead class="thead-light">
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Category</th>
              <th>Staff</th>
              <th>Bank</th>
              <th>Amount (ብር)</th>
              <th>Notes</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($transactions as $t): ?>
              <tr>
                <td><?= html_escape($t->date) ?></td>
                <td>
                  <span class="badge badge-<?= $t->type == 'Income' ? 'success' : 'danger' ?>">
                    <?= html_escape($t->type) ?>
                  </span>
                </td>
                <td><?= ucfirst(html_escape($t->transaction_type)) ?></td>
                <td><?= html_escape($t->staff_name ?? '-') ?></td>
                <td><?= html_escape($t->bank_name ?? '-') ?></td>
                <td class="text-right"><?= number_format($t->birr, 2) ?></td>
                <td><?= html_escape($t->notes) ?></td>
                <td><?= html_escape($t->description) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<script>
  $(function () {
    $('#transactionTable').DataTable({
      ordering: false,
      pageLength: 10,
      responsive: true,
      autoWidth: false,
    });
  });
</script>
