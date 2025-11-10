<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manage Services</h1>
        <a href="<?= base_url('/admin/services/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Service
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
            <?php if (!empty($services)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="servicesTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="60">#</th>
                                <th>Service Name</th>
                                <th>Description</th>
                                <th>Parent</th>
                                <th width="100">Status</th>
                                <th width="100">Sort</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $index => $service): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($service['icon_class'])): ?>
                                            <i class="<?= $service['icon_class'] ?> me-2 text-primary"></i>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?= esc($service['name']) ?></strong>
                                            <?php if (!empty($service['slug'])): ?>
                                                <br>
                                                <small class="text-muted">/<?= esc($service['slug']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= esc(substr($service['description'], 0, 100)) ?><?= strlen($service['description']) > 100 ? '...' : '' ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (!empty($service['parent_name'])): ?>
                                        <span class="badge bg-info"><?= esc($service['parent_name']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Main Service</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin/services/toggle-status/' . $service['id']) ?>" 
                                       class="badge bg-<?= $service['is_active'] ? 'success' : 'danger' ?> text-decoration-none"
                                       onclick="return confirm('Are you sure you want to <?= $service['is_active'] ? 'deactivate' : 'activate' ?> this service?')">
                                        <i class="fas fa-<?= $service['is_active'] ? 'check' : 'times' ?> me-1"></i>
                                        <?= $service['is_active'] ? 'Active' : 'Inactive' ?>
                                    </a>
                                </td>
                                <td>
                                    <input type="number" 
                                           class="form-control form-control-sm sort-order" 
                                           value="<?= $service['sort_order'] ?>" 
                                           data-id="<?= $service['id'] ?>" 
                                           min="0" 
                                           style="width: 70px;">
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('/admin/services/edit/' . $service['id']) ?>" 
                                           class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('/admin/services/delete/' . $service['id']) ?>" 
                                           class="btn btn-outline-danger" title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-success btn-sm" id="saveSortOrder">
                        <i class="fas fa-save me-1"></i> Save Sort Order
                    </button>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                    <h5>No Services Found</h5>
                    <p class="text-muted">Get started by creating your first service.</p>
                    <a href="<?= base_url('/admin/services/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Service
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Save sort order
    const saveBtn = document.getElementById('saveSortOrder');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const sortData = {};
            const sortInputs = document.querySelectorAll('.sort-order');
            
            sortInputs.forEach(input => {
                sortData[input.getAttribute('data-id')] = input.value;
            });

            // Show loading
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

            fetch('<?= base_url('/admin/services/update-sort-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ sort_order: sortData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                showError('An error occurred while saving sort order.');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Sort Order';
            });
        });
    }

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    }
});
</script>

<?= $this->include('admin/templates/footer') ?>