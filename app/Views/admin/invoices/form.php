<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= isset($invoice) ? 'Edit Invoice' : 'Create New Invoice' ?></h1>
                <a href="<?= base_url('/admin/invoices') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Invoices
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

            <!-- Validation Errors -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= isset($invoice) ? base_url("/admin/invoices/update/{$invoice['id']}") : base_url('/admin/invoices/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Invoice Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    Invoice Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="invoice_number" class="form-label">Invoice Number *</label>
                                        <input type="text"
                                            class="form-control"
                                            id="invoice_number"
                                            name="invoice_number"
                                            value="<?= old('invoice_number', $invoice['invoice_number'] ?? $invoiceNumber ?? '') ?>"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client_id" class="form-label">Client *</label>
                                        <select class="form-select" id="client_id" name="client_id" required>
                                            <option value="">Select Client</option>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>"
                                                    <?= old('client_id', $invoice['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>
                                                    <?= (isset($_GET['client_id']) && $_GET['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                                    <?= esc($client['company_name']) ?> - <?= esc($client['contact_person']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="subscription_id" class="form-label">Subscription (Optional)</label>
                                        <select class="form-select" id="subscription_id" name="subscription_id">
                                            <option value="">Select Subscription</option>
                                            <?php foreach ($subscriptions as $subscription): ?>
                                                <option value="<?= $subscription['id'] ?>"
                                                    <?= old('subscription_id', $invoice['subscription_id'] ?? '') == $subscription['id'] ? 'selected' : '' ?>
                                                    data-amount="<?= $subscription['amount'] ?>">
                                                    <?= esc($subscription['company_name']) ?> -
                                                    <?= number_format($subscription['amount'], 2) ?> <?= $subscription['currency'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="currency" class="form-label">Currency *</label>
                                        <select class="form-select" id="currency" name="currency" required>
                                            <option value="USD" <?= old('currency', $invoice['currency'] ?? 'USD') == 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                            <option value="IDR" <?= old('currency', $invoice['currency'] ?? '') == 'IDR' ? 'selected' : '' ?>>IDR (Rp)</option>
                                            <option value="EUR" <?= old('currency', $invoice['currency'] ?? '') == 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                                            <option value="GBP" <?= old('currency', $invoice['currency'] ?? '') == 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Dates -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Invoice Dates
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="issue_date" class="form-label">Issue Date *</label>
                                        <input type="date"
                                            class="form-control"
                                            id="issue_date"
                                            name="issue_date"
                                            value="<?= old('issue_date', $invoice['issue_date'] ?? date('Y-m-d')) ?>"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="due_date" class="form-label">Due Date *</label>
                                        <input type="date"
                                            class="form-control"
                                            id="due_date"
                                            name="due_date"
                                            value="<?= old('due_date', $invoice['due_date'] ?? date('Y-m-d', strtotime('+30 days'))) ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Financial Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Amount *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number"
                                                class="form-control"
                                                id="amount"
                                                name="amount"
                                                step="0.01"
                                                min="0"
                                                value="<?= old('amount', $invoice['amount'] ?? '') ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tax_amount" class="form-label">Tax Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number"
                                                class="form-control"
                                                id="tax_amount"
                                                name="tax_amount"
                                                step="0.01"
                                                min="0"
                                                value="<?= old('tax_amount', $invoice['tax_amount'] ?? 0) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text"
                                                class="form-control"
                                                id="total_amount"
                                                value="0.00"
                                                readonly
                                                style="background-color: #f8f9fa; font-weight: bold;">
                                        </div>
                                        <small class="text-muted">Calculated automatically (Amount + Tax)</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="">Select Payment Method</option>
                                            <option value="bank_transfer" <?= old('payment_method', $invoice['payment_method'] ?? '') == 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                            <option value="credit_card" <?= old('payment_method', $invoice['payment_method'] ?? '') == 'credit_card' ? 'selected' : '' ?>>Credit Card</option>
                                            <option value="paypal" <?= old('payment_method', $invoice['payment_method'] ?? '') == 'paypal' ? 'selected' : '' ?>>PayPal</option>
                                            <option value="other" <?= old('payment_method', $invoice['payment_method'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Additional Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control"
                                        id="notes"
                                        name="notes"
                                        rows="4"
                                        placeholder="Additional notes about this invoice..."><?= old('notes', $invoice['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Invoice Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Invoice Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?= old('status', $invoice['status'] ?? 'draft') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="sent" <?= old('status', $invoice['status'] ?? '') == 'sent' ? 'selected' : '' ?>>Sent</option>
                                        <option value="paid" <?= old('status', $invoice['status'] ?? '') == 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="overdue" <?= old('status', $invoice['status'] ?? '') == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                                        <option value="cancelled" <?= old('status', $invoice['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>

                                <?php if (isset($invoice) && $invoice['status'] === 'paid' && $invoice['paid_date']): ?>
                                    <div class="alert alert-success">
                                        <small>
                                            <i class="fas fa-check-circle me-1"></i>
                                            Paid on <?= date('M j, Y', strtotime($invoice['paid_date'])) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-save me-2"></i>
                                    <?= isset($invoice) ? 'Update Invoice' : 'Create Invoice' ?>
                                </button>
                                <a href="<?= base_url('/admin/invoices') ?>" class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>

                                <?php if (isset($invoice)): ?>
                                    <hr>
                                    <div class="d-grid gap-2">
                                        <a href="<?= base_url("/admin/invoices/view/{$invoice['id']}") ?>"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            View Invoice
                                        </a>
                                        <?php if ($invoice['status'] !== 'paid'): ?>
                                            <button type="button"
                                                class="btn btn-outline-success btn-sm"
                                                onclick="markAsPaid(<?= $invoice['id'] ?>)">
                                                <i class="fas fa-check me-1"></i>
                                                Mark as Paid
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            Created: <?= date('M j, Y', strtotime($invoice['created_at'])) ?><br>
                                            <?php if ($invoice['updated_at'] != $invoice['created_at']): ?>
                                                Updated: <?= date('M j, Y', strtotime($invoice['updated_at'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Summary -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Invoice Number:</small>
                                    <div class="fw-bold" id="summary-invoice-number"><?= $invoiceNumber ?? '-' ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Client:</small>
                                    <div class="fw-bold" id="summary-client">-</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Due Date:</small>
                                    <div class="fw-bold" id="summary-due-date">-</div>
                                </div>
                                <div>
                                    <small class="text-muted">Total Amount:</small>
                                    <div class="fw-bold text-success" id="summary-total-amount">$0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const taxInput = document.getElementById('tax_amount');
        const totalInput = document.getElementById('total_amount');
        const subscriptionSelect = document.getElementById('subscription_id');
        const clientSelect = document.getElementById('client_id');
        const dueDateInput = document.getElementById('due_date');
        const invoiceNumberInput = document.getElementById('invoice_number');

        // Auto-fill amount from subscription
        subscriptionSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const subscriptionAmount = selectedOption.getAttribute('data-amount');

            if (subscriptionAmount) {
                amountInput.value = parseFloat(subscriptionAmount).toFixed(2);
                calculateTotal();
            }
        });

        // Calculate total amount
        function calculateTotal() {
            const amount = parseFloat(amountInput.value) || 0;
            const tax = parseFloat(taxInput.value) || 0;
            const total = amount + tax;

            totalInput.value = total.toFixed(2);
            updateSummary();
        }

        // Update summary panel
        function updateSummary() {
            // Update total amount in summary
            document.getElementById('summary-total-amount').textContent = '$' + totalInput.value;

            // Update client in summary
            const clientOption = clientSelect.options[clientSelect.selectedIndex];
            document.getElementById('summary-client').textContent = clientOption.textContent || '-';

            // Update due date in summary
            document.getElementById('summary-due-date').textContent = dueDateInput.value || '-';

            // Update invoice number in summary
            document.getElementById('summary-invoice-number').textContent = invoiceNumberInput.value || '-';
        }

        // Add event listeners for calculations
        amountInput.addEventListener('input', calculateTotal);
        taxInput.addEventListener('input', calculateTotal);
        clientSelect.addEventListener('change', updateSummary);
        dueDateInput.addEventListener('change', updateSummary);
        invoiceNumberInput.addEventListener('input', updateSummary);

        // Set default due date to 30 days from today if not set
        if (!dueDateInput.value) {
            const today = new Date();
            const dueDate = new Date(today);
            dueDate.setDate(today.getDate() + 30);
            dueDateInput.value = dueDate.toISOString().split('T')[0];
        }

        // Initialize calculations
        calculateTotal();
        updateSummary();
    });

    // Mark as paid function
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
                // Create a form to submit
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