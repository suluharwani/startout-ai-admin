<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Users</h1>
        <a href="<?= base_url('/admin/users/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New User
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">
                                                <?= strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0"><?= $user->first_name . ' ' . $user->last_name ?></h6>
                                        <small class="text-muted">@<?= $user->username ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?= $user->email ?></div>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user->role == 'admin' ? 'danger' : ($user->role == 'manager' ? 'warning' : 'secondary') ?>">
                                    <i class="fas fa-<?= $user->role == 'admin' ? 'crown' : ($user->role == 'manager' ? 'user-tie' : 'user') ?> me-1"></i>
                                    <?= ucfirst($user->role) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user->id == session()->get('admin_id')): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>You
                                    </span>
                                <?php else: ?>
                                    <a href="<?= base_url('/admin/users/toggle-status/' . $user->id) ?>" 
                                       class="badge bg-<?= $user->is_active ? 'success' : 'danger' ?> text-decoration-none"
                                       onclick="return confirm('Are you sure you want to <?= $user->is_active ? 'deactivate' : 'activate' ?> this user?')">
                                        <i class="fas fa-<?= $user->is_active ? 'check' : 'times' ?> me-1"></i>
                                        <?= $user->is_active ? 'Active' : 'Inactive' ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= $user->last_login ? date('M j, Y H:i', strtotime($user->last_login)) : 'Never' ?>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($user->created_at)) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= base_url('/admin/users/edit/' . $user->id) ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($user->id != session()->get('admin_id')): ?>
                                        <a href="<?= base_url('/admin/users/delete/' . $user->id) ?>" 
                                           class="btn btn-outline-danger" title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary" disabled title="Cannot delete your own account">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($users)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No Users Found</h5>
                    <p class="text-muted">Get started by creating your first user.</p>
                    <a href="<?= base_url('/admin/users/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New User
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<?= $this->include('admin/templates/footer') ?>