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
                        <?php if (!empty($users) && is_array($users)): ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user->id ?? $user['id'] ?? '') ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <span class="text-white fw-bold">
                                                    <?= strtoupper(
                                                        substr(($user->first_name ?? $user['first_name'] ?? ''), 0, 1) . 
                                                        substr(($user->last_name ?? $user['last_name'] ?? ''), 0, 1)
                                                    ) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">
                                                <?= esc(($user->first_name ?? $user['first_name'] ?? '') . ' ' . ($user->last_name ?? $user['last_name'] ?? '')) ?>
                                            </h6>
                                            <small class="text-muted">
                                                @<?= esc($user->username ?? $user['username'] ?? '') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div><?= esc($user->email ?? $user['email'] ?? '') ?></div>
                                </td>
                                <td>
                                    <?php 
                                    $role = $user->role ?? $user['role'] ?? '';
                                    if ($role): ?>
                                        <span class="badge bg-<?= $role == 'admin' ? 'danger' : ($role == 'manager' ? 'warning' : 'secondary') ?>">
                                            <i class="fas fa-<?= $role == 'admin' ? 'crown' : ($role == 'manager' ? 'user-tie' : 'user') ?> me-1"></i>
                                            <?= ucfirst($role) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $currentUserId = session()->get('admin_id');
                                    $userId = $user->id ?? $user['id'] ?? '';
                                    $isCurrentUser = $userId && $userId == $currentUserId;
                                    $isActive = $user->is_active ?? $user['is_active'] ?? false;
                                    ?>
                                    
                                    <?php if ($isCurrentUser): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>You
                                        </span>
                                    <?php else: ?>
                                        <?php if (isset($user->is_active) || isset($user['is_active'])): ?>
                                            <a href="<?= base_url('/admin/users/toggle-status/' . $userId) ?>" 
                                               class="badge bg-<?= $isActive ? 'success' : 'danger' ?> text-decoration-none"
                                               onclick="return confirm('Are you sure you want to <?= $isActive ? 'deactivate' : 'activate' ?> this user?')">
                                                <i class="fas fa-<?= $isActive ? 'check' : 'times' ?> me-1"></i>
                                                <?= $isActive ? 'Active' : 'Inactive' ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Unknown</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php 
                                        $lastLogin = $user->last_login ?? $user['last_login'] ?? '';
                                        echo $lastLogin ? date('M j, Y H:i', strtotime($lastLogin)) : 'Never';
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php 
                                        $createdAt = $user->created_at ?? $user['created_at'] ?? '';
                                        echo $createdAt ? date('M j, Y', strtotime($createdAt)) : 'Unknown';
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/admin/users/edit/' . $userId) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if (!$isCurrentUser && $userId): ?>
                                            <a href="<?= base_url('/admin/users/delete/' . $userId) ?>" 
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
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Users Found</h5>
                                    <p class="text-muted">Get started by creating your first user.</p>
                                    <a href="<?= base_url('/admin/users/create') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add New User
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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