<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Subscriptions</h1>
                <a href="<?= base_url('/admin/subscriptions/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add New Subscription
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
                    <?php if (!empty($subscriptions)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Cooperation Type</th>
                                        <th>Period</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscriptions as $subscription): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($subscription['company_name']) ?></strong>
                                            </td>
                                            <td><?= esc($subscription['service_name']) ?></td>
                                            <td><?= esc($subscription['cooperation_type']) ?></td>
                                            <td>
                                                <?= date('M j, Y', strtotime($subscription['start_date'])) ?> -
                                                <?= date('M j, Y', strtotime($subscription['end_date'])) ?>
                                                <br>
                                                <small class="text-muted"><?= ucfirst($subscription['billing_cycle']) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= number_format($subscription['amount'], 2) ?> <?= $subscription['currency'] ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $statusBadge = [
                                                    'active' => 'bg-success',
                                                    'pending' => 'bg-warning',
                                                    'suspended' => 'bg-danger',
                                                    'cancelled' => 'bg-secondary',
                                                    'completed' => 'bg-info'
                                                ];
                                                ?>
                                                <span class="badge <?= $statusBadge[$subscription['status']] ?>">
                                                    <?= ucfirst($subscription['status']) ?>
                                                </span>
                                                <?php if ($subscription['auto_renew']): ?>
                                                    <br>
                                                    <small class="text-muted">Auto-renew</small>
                                                <?php endif; ?>
                                            </td>
                                            <!-- ... kode sebelumnya ... -->
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url("/admin/subscriptions/edit/{$subscription['id']}") ?>"
                                                        class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/subscriptions/toggle-status/{$subscription['id']}") ?>"
                                                        class="btn btn-outline-<?= $subscription['status'] === 'active' ? 'warning' : 'success' ?>"
                                                        title="<?= $subscription['status'] === 'active' ? 'Suspend' : 'Activate' ?>">
                                                        <i class="fas fa-<?= $subscription['status'] === 'active' ? 'pause' : 'play' ?>"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-outline-danger"
                                                        onclick="confirmDelete('subscription', '<?= base_url("/admin/subscriptions/delete/{$subscription['id']}") ?>')"
                                                        title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <!-- ... kode setelahnya ... -->
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                            <h4>No Subscriptions Found</h4>
                            <p class="text-muted">Get started by creating your first subscription.</p>
                            <a href="<?= base_url('/admin/subscriptions/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Subscription
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>