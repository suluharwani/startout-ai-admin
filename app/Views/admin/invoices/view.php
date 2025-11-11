<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Invoice #<?= $invoice['invoice_number'] ?></h1>
                <div class="btn-group">
                    <a href="<?= base_url("/admin/invoices/download/{$invoice['id']}") ?>" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-download me-2"></i>
                        Download PDF
                    </a>
                    <a href="<?= base_url("/admin/invoices/edit/{$invoice['id']}") ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i>
                        Edit
                    </a>
                    <a href="<?= base_url('/admin/invoices') ?>" class="btn btn-outline-dark">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Invoices
                    </a>
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Invoice Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>From:</h6>
                                    <address>
                                        <strong>Startout AI</strong><br>
                                        123 AI Boulevard<br>
                                        San Francisco, CA 94107<br>
                                        United States<br>
                                        <i class="fas fa-phone me-1"></i> +1 (800) 123-4567<br>
                                        <i class="fas fa-envelope me-1"></i> info@startoutai.com
                                    </address>
                                </div>
                                <div class="col-md-6">
                                    <h6>To:</h6>
                                    <address>
                                        <strong><?= esc($invoice['company_name']) ?></strong><br>
                                        <?php if (!empty($invoice['contact_person'])): ?>
                                            Attn: <?= esc($invoice['contact_person']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($invoice['address'])): ?>
                                            <?= esc($invoice['address']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($invoice['city'])): ?>
                                            <?= esc($invoice['city']) ?>,
                                        <?php endif; ?>
                                        <?php if (!empty($invoice['country'])): ?>
                                            <?= esc($invoice['country']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($invoice['email'])): ?>
                                            <i class="fas fa-envelope me-1"></i> <?= esc($invoice['email']) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($invoice['phone'])): ?>
                                            <i class="fas fa-phone me-1"></i> <?= esc($invoice['phone']) ?>
                                        <?php endif; ?>
                                    </address>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <strong>Invoice Number:</strong><br>
                                    <?= esc($invoice['invoice_number']) ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Issue Date:</strong><br>
                                    <?= date('F j, Y', strtotime($invoice['issue_date'])) ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Due Date:</strong><br>
                                    <?= date('F j, Y', strtotime($invoice['due_date'])) ?>
                                </div>
                            </div>

                            <?php if (!empty($invoice['service_name'])): ?>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <strong>Service:</strong><br>
                                        <?= esc($invoice['service_name']) ?>
                                        <?php if (!empty($invoice['cooperation_type'])): ?>
                                            (<?= esc($invoice['cooperation_type']) ?>)
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Invoice Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th width="100" class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?= !empty($invoice['service_name']) ? esc($invoice['service_name']) : 'Professional Services' ?>
                                                </strong>
                                                <?php if (!empty($invoice['notes'])): ?>
                                                    <br><small class="text-muted"><?= esc($invoice['notes']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?= number_format($invoice['amount'], 2) ?> <?= $invoice['currency'] ?>
                                            </td>
                                        </tr>
                                        <?php if ($invoice['tax_amount'] > 0): ?>
                                            <tr>
                                                <td><strong>Tax</strong></td>
                                                <td class="text-end">
                                                    <?= number_format($invoice['tax_amount'], 2) ?> <?= $invoice['currency'] ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td class="text-end">
                                                <strong>
                                                    <?= number_format($invoice['total_amount'], 2) ?> <?= $invoice['currency'] ?>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($invoice['notes'])): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notes</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0"><?= nl2br(esc($invoice['notes'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <!-- Invoice Status -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Invoice Status</h5>
                        </div>
                        <div class="card-body text-center">
                            <?php
                            $statusConfig = [
                                'draft' => ['class' => 'bg-secondary', 'icon' => 'edit'],
                                'sent' => ['class' => 'bg-info', 'icon' => 'paper-plane'],
                                'paid' => ['class' => 'bg-success', 'icon' => 'check-circle'],
                                'overdue' => ['class' => 'bg-danger', 'icon' => 'exclamation-circle'],
                                'cancelled' => ['class' => 'bg-warning', 'icon' => 'times-circle']
                            ];
                            $config = $statusConfig[$invoice['status']] ?? $statusConfig['draft'];
                            ?>
                            <div class="mb-3">
                                <span class="badge <?= $config['class'] ?> fs-6 p-2">
                                    <i class="fas fa-<?= $config['icon'] ?> me-1"></i>
                                    <?= ucfirst($invoice['status']) ?>
                                </span>
                            </div>

                            <?php if ($invoice['status'] === 'paid' && $invoice['paid_date']): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Paid on <?= date('F j, Y', strtotime($invoice['paid_date'])) ?>
                                    <?php if (!empty($invoice['payment_method'])): ?>
                                        <br><small>via <?= ucfirst(str_replace('_', ' ', $invoice['payment_method'])) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($invoice['status'] !== 'paid'): ?>
                                <button type="button"
                                    class="btn btn-success w-100 mb-2"
                                    onclick="markAsPaid(<?= $invoice['id'] ?>)">
                                    <i class="fas fa-check me-2"></i>
                                    Mark as Paid
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Payment Information</h5>
                        </div>
                        <div class="card-body">
                            <h6>Bank Transfer</h6>
                            <small class="text-muted">
                                Bank: ABC Bank<br>
                                Account: 1234567890<br>
                                Name: Startout AI Inc.<br>
                                SWIFT: ABCDEFG123
                            </small>

                            <hr>

                            <h6>PayPal</h6>
                            <small class="text-muted">
                                Email: payments@startoutai.com<br>
                                Please include invoice number in notes.
                            </small>
                        </div>
                    </div>

                    <!-- Invoice Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= base_url("/admin/invoices/edit/{$invoice['id']}") ?>"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit Invoice
                                </a>
                                <a href="<?= base_url("/admin/clients/edit/{$invoice['client_id']}") ?>"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-user me-1"></i>
                                    View Client
                                </a>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="confirmDelete('invoice', '<?= base_url("/admin/invoices/delete/{$invoice['id']}") ?>')">
                                    <i class="fas fa-trash me-1"></i>
                                    Delete Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>

<script>
    function markAsPaid(invoiceId) {
        Swal.fire({
            title: 'Mark as Paid?',
            text: 'Are you sure you want to mark this invoice as paid?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark as paid!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= base_url('/admin/invoices/mark-as-paid/') ?>${invoiceId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '<?= csrf_token() ?>';
                csrfToken.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>