<section class="content">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    
        <a href="<?= base_url('admin/staff_transaction/create') ?>" class="btn btn-sm btn-primary pull-right">Add Transaction</a>
    
    </div>
    <div class="box-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th><th>Staff</th><th>Amount</th><th>Type</th><th>Date</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $i => $row): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= $row->staff_id ?></td>
              <td><?= number_format($row->birr, 2) ?></td>
              <td><span class="badge bg-<?= $row->type == 'Income' ? 'green' : 'red' ?>"><?= $row->type ?></span></td>
              <td><?= $row->date ?></td>
              <td>
               
                  <a href="<?= base_url('admin/staff_transaction/edit/'.$row->id) ?>" class="btn btn-sm btn-info">Edit</a>
               
               
                  <button class="btn btn-sm btn-danger delete-transaction" data-id="<?= $row->id ?>">Delete</button>
              
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
