<form id="bankForm">
  <div class="modal-header">
    <h5 class="modal-title"><?= isset($bank) ? 'Edit' : 'Add' ?> Bank</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <input type="hidden" name="id" value="<?= isset($bank) ? $bank->id : '' ?>">
    <div class="form-group">
      <label for="staff_id">Staff</label>
      <select name="staff_id" class="form-control">
        <option value="">Select</option>
        <?php foreach ($staffs as $staff): ?>
          <option value="1" <?= isset($bank) && $bank->staff_id == $staff->id ? 'selected' : '' ?>>
            <?= $staff->name . ' ' . $staff->name ?>
          </option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group">
      <label for="name">Bank Name</label>
      <input type="text" name="name" class="form-control" value="<?= isset($bank) ? $bank->name : '' ?>">
    </div>
    <div class="form-group">
      <label for="number">Account Number</label>
      <input type="text" name="number" class="form-control" value="<?= isset($bank) ? $bank->number : '' ?>">
    </div>
    <div class="form-group">
      <label for="balance">Balance</label>
      <input type="text" name="balance" class="form-control" value="<?= isset($bank) ? $bank->balance : '' ?>">
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-success">Save</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>

<script>
$('#bankForm').on('submit', function (e) {
  e.preventDefault();
  $.post("<?= base_url('admin/staff_bank/save') ?>", $(this).serialize(), function (res) {
    if (res.status == 'success') {
      location.reload();
    } else {
      alert(res.message);
    }
  }, 'json');
});
</script>
