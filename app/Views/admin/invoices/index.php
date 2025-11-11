<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Invoices</h1>
                <a href="<?= base_url('/admin/invoices/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create New Invoice
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
                <div class="col-md-2">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['total'] ?></h4>
                                    <p>Total Invoices</p>
                                </div>
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['paid'] ?></h4>
                                    <p>Paid</p>
                                </div>
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['sent'] ?></h4>
                                    <p>Sent</p>
                                </div>
                                <i class="fas fa-paper-plane fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['draft'] ?></h4>
                                    <p>Draft</p>
                                </div>
                                <i class="fas fa-edit fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $stats['overdue'] ?></h4>
                                    <p>Overdue</p>
                                </div>
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>$<?= number_format($stats['total_amount'], 2) ?></h4>
                                    <p>Total Amount</p>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <?php if (!empty($invoices)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Client</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($invoice['invoice_number']) ?></strong>
                                            </td>
                                            <td><?= esc($invoice['company_name']) ?></td>
                                            <td><?= date('M j, Y', strtotime($invoice['issue_date'])) ?></td>
                                            <td>
                                                <?= date('M j, Y', strtotime($invoice['due_date'])) ?>
                                                <?php if ($invoice['status'] === 'overdue'): ?>
                                                    <br>
                                                    <small class="text-danger">Overdue</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= number_format($invoice['total_amount'], 2) ?> <?= $invoice['currency'] ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $statusBadge = [
                                                    'draft' => 'bg-secondary',
                                                    'sent' => 'bg-info',
                                                    'paid' => 'bg-success',
                                                    'overdue' => 'bg-danger',
                                                    'cancelled' => 'bg-warning'
                                                ];
                                                ?>
                                                <span class="badge <?= $statusBadge[$invoice['status']] ?>">
                                                    <?= ucfirst($invoice['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url("/admin/invoices/view/{$invoice['id']}") ?>"
                                                        class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/invoices/download/{$invoice['id']}") ?>"
                                                        class="btn btn-outline-info" title="Download PDF" target="_blank">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="<?= base_url("/admin/invoices/edit/{$invoice['id']}") ?>"
                                                        class="btn btn-outline-success" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($invoice['status'] !== 'paid'): ?>
                                                        <a href="<?= base_url("/admin/invoices/mark-as-paid/{$invoice['id']}") ?>"
                                                            class="btn btn-outline-warning" title="Mark as Paid">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button"
                                                        class="btn btn-outline-danger"
                                                        onclick="confirmDelete('invoice', '<?= base_url("/admin/invoices/delete/{$invoice['id']}") ?>')"
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
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h4>No Invoices Found</h4>
                            <p class="text-muted">Get started by creating your first invoice.</p>
                            <a href="<?= base_url('/admin/invoices/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Create Invoice
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>