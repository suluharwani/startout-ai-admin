<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= isset($subscription) ? 'Edit Subscription' : 'Create New Subscription' ?></h1>
                <a href="<?= base_url('/admin/subscriptions') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Subscriptions
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

            <form action="<?= isset($subscription) ? base_url("/admin/subscriptions/update/{$subscription['id']}") : base_url('/admin/subscriptions/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Subscription Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-file-contract me-2"></i>
                                    Subscription Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="client_id" class="form-label">Client *</label>
                                        <select class="form-select" id="client_id" name="client_id" required>
                                            <option value="">Select Client</option>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>"
                                                    <?= old('client_id', $subscription['client_id'] ?? '') == $client['id'] ? 'selected' : '' ?>
                                                    <?= (isset($_GET['client_id']) && $_GET['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                                    <?= esc($client['company_name']) ?>
                                                    <?php if (!empty($client['contact_person'])): ?>
                                                        - <?= esc($client['contact_person']) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (empty($clients)): ?>
                                            <div class="alert alert-warning mt-2">
                                                <small>
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    No active clients found.
                                                    <a href="<?= base_url('/admin/clients/create') ?>" class="alert-link">Create a client first</a>.
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="service_id" class="form-label">Service *</label>
                                        <select class="form-select" id="service_id" name="service_id" required>
                                            <option value="">Select Service</option>
                                            <?php foreach ($services as $service): ?>
                                                <option value="<?= $service['id'] ?>"
                                                    <?= old('service_id', $subscription['service_id'] ?? '') == $service['id'] ? 'selected' : '' ?>>
                                                    <?= esc($service['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cooperation_type_id" class="form-label">Cooperation Type *</label>
                                        <select class="form-select" id="cooperation_type_id" name="cooperation_type_id" required>
                                            <option value="">Select Cooperation Type</option>
                                            <?php foreach ($cooperationTypes as $type): ?>
                                                <option value="<?= $type['id'] ?>"
                                                    <?= old('cooperation_type_id', $subscription['cooperation_type_id'] ?? '') == $type['id'] ? 'selected' : '' ?>
                                                    data-duration="<?= $type['duration_days'] ?>">
                                                    <?= esc($type['name']) ?>
                                                    <?php if ($type['duration_days']): ?>
                                                        (<?= $type['duration_days'] ?> days)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_cycle" class="form-label">Billing Cycle *</label>
                                        <select class="form-select" id="billing_cycle" name="billing_cycle" required>
                                            <option value="monthly" <?= old('billing_cycle', $subscription['billing_cycle'] ?? 'monthly') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                            <option value="quarterly" <?= old('billing_cycle', $subscription['billing_cycle'] ?? '') == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                            <option value="yearly" <?= old('billing_cycle', $subscription['billing_cycle'] ?? '') == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                                            <option value="one_time" <?= old('billing_cycle', $subscription['billing_cycle'] ?? '') == 'one_time' ? 'selected' : '' ?>>One Time</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Period -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Subscription Period
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Start Date *</label>
                                        <input type="date"
                                            class="form-control"
                                            id="start_date"
                                            name="start_date"
                                            value="<?= old('start_date', $subscription['start_date'] ?? date('Y-m-d')) ?>"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">End Date *</label>
                                        <input type="date"
                                            class="form-control"
                                            id="end_date"
                                            name="end_date"
                                            value="<?= old('end_date', $subscription['end_date'] ?? '') ?>"
                                            required>
                                        <small class="text-muted">End date will be auto-calculated based on cooperation type</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Financial Information
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
                                                value="<?= old('amount', $subscription['amount'] ?? '') ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="currency" class="form-label">Currency *</label>
                                        <select class="form-select" id="currency" name="currency" required>
                                            <option value="USD" <?= old('currency', $subscription['currency'] ?? 'USD') == 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                            <option value="IDR" <?= old('currency', $subscription['currency'] ?? '') == 'IDR' ? 'selected' : '' ?>>IDR (Rp)</option>
                                            <option value="EUR" <?= old('currency', $subscription['currency'] ?? '') == 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                                            <option value="GBP" <?= old('currency', $subscription['currency'] ?? '') == 'GBP' ? 'selected' : '' ?>>GBP (£)</option>
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
                                        placeholder="Additional notes about this subscription..."><?= old('notes', $subscription['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Subscription Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Subscription Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" <?= old('status', $subscription['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="active" <?= old('status', $subscription['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="suspended" <?= old('status', $subscription['status'] ?? '') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                        <option value="cancelled" <?= old('status', $subscription['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        <option value="completed" <?= old('status', $subscription['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            id="auto_renew"
                                            name="auto_renew"
                                            value="1"
                                            <?= old('auto_renew', $subscription['auto_renew'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto_renew">
                                            Auto Renew
                                        </label>
                                    </div>
                                    <small class="text-muted">Automatically renew this subscription when it expires</small>
                                </div>
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
                                    <?= isset($subscription) ? 'Update Subscription' : 'Create Subscription' ?>
                                </button>
                                <a href="<?= base_url('/admin/subscriptions') ?>" class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>

                                <?php if (isset($subscription)): ?>
                                    <hr>
                                    <div class="text-center">
                                        <small class="text-muted">
                                            Created: <?= date('M j, Y', strtotime($subscription['created_at'])) ?><br>
                                            <?php if ($subscription['updated_at'] != $subscription['created_at']): ?>
                                                Updated: <?= date('M j, Y', strtotime($subscription['updated_at'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Selected Service:</small>
                                    <div id="selected-service-info" class="fw-bold">-</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Cooperation Type:</small>
                                    <div id="selected-type-info" class="fw-bold">-</div>
                                </div>
                                <div>
                                    <small class="text-muted">Subscription Period:</small>
                                    <div id="period-info" class="fw-bold">-</div>
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
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const cooperationTypeSelect = document.getElementById('cooperation_type_id');
        const serviceSelect = document.getElementById('service_id');
        const clientSelect = document.getElementById('client_id');

        // Auto-calculate end date based on cooperation type duration
        cooperationTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const durationDays = selectedOption.getAttribute('data-duration');
            const startDate = startDateInput.value;

            if (durationDays && startDate) {
                const start = new Date(startDate);
                const end = new Date(start);
                end.setDate(start.getDate() + parseInt(durationDays));

                endDateInput.value = end.toISOString().split('T')[0];
            }

            updateQuickInfo();
        });

        // Update quick info when any field changes
        startDateInput.addEventListener('change', updateQuickInfo);
        endDateInput.addEventListener('change', updateQuickInfo);
        serviceSelect.addEventListener('change', updateQuickInfo);
        cooperationTypeSelect.addEventListener('change', updateQuickInfo);

        function updateQuickInfo() {
            // Update service info
            const serviceOption = serviceSelect.options[serviceSelect.selectedIndex];
            document.getElementById('selected-service-info').textContent =
                serviceOption.textContent || '-';

            // Update cooperation type info
            const typeOption = cooperationTypeSelect.options[cooperationTypeSelect.selectedIndex];
            document.getElementById('selected-type-info').textContent =
                typeOption.textContent || '-';

            // Update period info
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate).toLocaleDateString();
                const end = new Date(endDate).toLocaleDateString();
                document.getElementById('period-info').textContent = `${start} to ${end}`;
            } else {
                document.getElementById('period-info').textContent = '-';
            }
        }

        // Set default start date to today if not set
        if (!startDateInput.value) {
            startDateInput.value = new Date().toISOString().split('T')[0];
        }

        // Set minimum date for end date to start date
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });

        // Initialize quick info
        updateQuickInfo();
    });
</script>