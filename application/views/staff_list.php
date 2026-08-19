<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Staff Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="<?= $this->security->get_csrf_hash() ?>">
</head>
<body>

    <div class="container mt-5">
        <h2>Staff Management</h2>
        <div class="mb-3">
            <a href="<?= site_url('admin/staff/add') ?>" class="btn btn-primary">Add New Staff</a>
        </div>
        
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $member): ?>
                <tr>
                    <td><?= $member->staff_id ?></td>
                    <td><?= htmlspecialchars($member->name) ?></td>
                   
                    <td>
                        <a href="<?= site_url('admin/staff/edit/'.$member->staff_id) ?>" 
                           class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" 
                                data-staff-id="<?= $member->staff_id ?>">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>