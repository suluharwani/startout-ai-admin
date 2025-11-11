<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Clients</h1>
                <a href="<?= base_url('/admin/clients/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Client
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

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['total'] ?></h4>
                                    <p>Total Clients</p>
                                </div>
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['active'] ?></h4>
                                    <p>Active Clients</p>
                                </div>
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['prospect'] ?></h4>
                                    <p>Prospects</p>
                                </div>
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['inactive'] ?></h4>
                                    <p>Inactive</p>
                                </div>
                                <i class="fas fa-pause-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($clients)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Type</th>
                                        <th>Industry</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($client['company_name']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= esc($client['email']) ?></small>
                                            </td>
                                            <td>
                                                <?= esc($client['contact_person']) ?>
                                                <br>
                                                <small class="text-muted"><?= esc($client['phone']) ?></small>
                                            </td>
                                            <td><?= esc($client['client_type'] ?? 'N/A') ?></td>
                                            <td><?= esc($client['industry'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php
                                                $statusBadge = [
                                                    'prospect' => 'bg-warning',
                                                    'active' => 'bg-success',
                                                    'inactive' => 'bg-secondary',
                                                    'suspended' => 'bg-danger'
                                                ];
                                                ?>
                                                <span class="badge <?= $statusBadge[$client['status']] ?>">
                                                    <?= ucfirst($client['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?= date('M j, Y', strtotime($client['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url("/admin/clients/edit/{$client['id']}") ?>" 
                                                       class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/clients/toggle-status/{$client['id']}") ?>" 
                                                       class="btn btn-outline-<?= $client['status'] === 'active' ? 'warning' : 'success' ?>" 
                                                       title="<?= $client['status'] === 'active' ? 'Deactivate' : 'Activate' ?>">
                                                        <i class="fas fa-<?= $client['status'] === 'active' ? 'pause' : 'play' ?>"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/subscriptions/create?client_id={$client['id']}") ?>" 
                                                       class="btn btn-outline-info" title="Add Subscription">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            onclick="confirmDelete('client', '<?= base_url("/admin/clients/delete/{$client['id']}") ?>')"
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
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4>No Clients Found</h4>
                            <p class="text-muted">Get started by adding your first client.</p>
                            <a href="<?= base_url('/admin/clients/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add Client
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>