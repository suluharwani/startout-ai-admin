<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Job Positions</h1>
                <a href="<?= base_url('/admin/jobs/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Job
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <?php if (!empty($jobs)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Department</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Remote</th>
                                        <th>Applications</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($job['title']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= esc($job['slug']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    <?= ucfirst($job['department']) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($job['location']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= ucfirst(str_replace('-', ' ', $job['employment_type'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($job['is_remote']): ?>
                                                    <span class="badge bg-success">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?= $job['application_count'] ?></span>
                                            </td>
                                            <td>
                                                <?php if ($job['is_active']): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url("/admin/jobs/edit/{$job['id']}") ?>" 
                                                       class="btn btn-outline-primary" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/jobs/toggle-status/{$job['id']}") ?>" 
                                                       class="btn btn-outline-<?= $job['is_active'] ? 'warning' : 'success' ?>" 
                                                       title="<?= $job['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                                        <i class="fas fa-<?= $job['is_active'] ? 'pause' : 'play' ?>"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            onclick="confirmDelete('job position', '<?= base_url("/admin/jobs/delete/{$job['id']}") ?>')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <h4>No Job Positions Found</h4>
                            <p class="text-muted">Get started by creating your first job position.</p>
                            <a href="<?= base_url('/admin/jobs/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Job Position
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>