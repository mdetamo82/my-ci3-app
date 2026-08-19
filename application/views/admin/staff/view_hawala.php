<div class="card">
    <div class="card-header bg-primary">
        <h3 class="card-title"><i class="fas fa-eye"></i> View Hawala</h3>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Name -->
            <div class="col-md-6 mb-3">
                <strong>Name:</strong>
                <p class="form-control-plaintext"><?= safe_html($hawala->name); ?></p>
            </div>

            <!-- Mark -->
            <div class="col-md-6 mb-3">
                <strong>Mark:</strong>
                <p class="form-control-plaintext"><?= safe_html($hawala->mark); ?></p>
            </div>

            <!-- Currency -->
            <div class="col-md-6 mb-3">
                <strong>Currency:</strong>
                <p class="form-control-plaintext"><?= safe_html($hawala->currency); ?></p>
            </div>

            <!-- Balance -->
            <div class="col-md-6 mb-3">
                <strong>Balance:</strong>
                <p class="form-control-plaintext"><?= number_format($hawala->balance, 2); ?></p>
            </div>

            <!-- Mobile -->
            <div class="col-md-6 mb-3">
                <strong>Mobile:</strong>
                <p class="form-control-plaintext"><?= safe_html($hawala->mobile); ?></p>
            </div>

            <!-- Address -->
            <div class="col-md-6 mb-3">
                <strong>Address:</strong>
                <p class="form-control-plaintext"><?= safe_html($hawala->address); ?></p>
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-3">
                <strong>Status:</strong>
                <p class="form-control-plaintext">
                    <?= $hawala->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= site_url('admin/hawala/edit/' . $hawala->hawala_id); ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="<?= site_url('admin/hawala'); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>
