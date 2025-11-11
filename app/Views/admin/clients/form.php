<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= isset($client) ? 'Edit Client' : 'Create New Client' ?></h1>
                <a href="<?= base_url('/admin/clients') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Clients
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

            <form action="<?= isset($client) ? base_url("/admin/clients/update/{$client['id']}") : base_url('/admin/clients/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Company Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-building me-2"></i>
                                    Company Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Company Name *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="company_name" 
                                               name="company_name" 
                                               value="<?= old('company_name', $client['company_name'] ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_person" class="form-label">Contact Person</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="contact_person" 
                                               name="contact_person" 
                                               value="<?= old('contact_person', $client['contact_person'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?= old('email', $client['email'] ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="phone" 
                                               name="phone" 
                                               value="<?= old('phone', $client['phone'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" 
                                               class="form-control" 
                                               id="website" 
                                               name="website" 
                                               value="<?= old('website', $client['website'] ?? '') ?>"
                                               placeholder="https://...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="client_type_id" class="form-label">Client Type</label>
                                        <select class="form-select" id="client_type_id" name="client_type_id">
                                            <option value="">Select Client Type</option>
                                            <?php foreach ($clientTypes as $type): ?>
                                                <option value="<?= $type['id'] ?>" 
                                                    <?= old('client_type_id', $client['client_type_id'] ?? '') == $type['id'] ? 'selected' : '' ?>>
                                                    <?= esc($type['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Address Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" 
                                              id="address" 
                                              name="address" 
                                              rows="3"><?= old('address', $client['address'] ?? '') ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="city" 
                                               name="city" 
                                               value="<?= old('city', $client['city'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="country" 
                                               name="country" 
                                               value="<?= old('country', $client['country'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Additional Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="industry_id" class="form-label">Industry</label>
                                        <select class="form-select" id="industry_id" name="industry_id">
                                            <option value="">Select Industry</option>
                                            <?php foreach ($industries as $industry): ?>
                                                <option value="<?= $industry['id'] ?>" 
                                                    <?= old('industry_id', $client['industry_id'] ?? '') == $industry['id'] ? 'selected' : '' ?>>
                                                    <?= esc($industry['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="prospect" <?= old('status', $client['status'] ?? 'prospect') == 'prospect' ? 'selected' : '' ?>>Prospect</option>
                                            <option value="active" <?= old('status', $client['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= old('status', $client['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            <option value="suspended" <?= old('status', $client['status'] ?? '') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" 
                                              id="notes" 
                                              name="notes" 
                                              rows="4"
                                              placeholder="Additional notes about the client..."><?= old('notes', $client['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Action Buttons -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-save me-2"></i>
                                    <?= isset($client) ? 'Update Client' : 'Create Client' ?>
                                </button>
                                <a href="<?= base_url('/admin/clients') ?>" class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
                                
                                <?php if (isset($client)): ?>
                                    <hr>
                                    <div class="text-center">
                                        <small class="text-muted">
                                            Created: <?= date('M j, Y', strtotime($client['created_at'])) ?><br>
                                            <?php if ($client['updated_at'] != $client['created_at']): ?>
                                                Updated: <?= date('M j, Y', strtotime($client['updated_at'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <?php if (isset($client)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url("/admin/subscriptions/create?client_id={$client['id']}") ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i>
                                        Add Subscription
                                    </a>
                                    <a href="<?= base_url("/admin/invoices/create?client_id={$client['id']}") ?>" 
                                       class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-receipt me-1"></i>
                                        Create Invoice
                                    </a>
                                    <a href="<?= base_url("/admin/clients/toggle-status/{$client['id']}") ?>" 
                                       class="btn btn-outline-<?= $client['status'] === 'active' ? 'warning' : 'success' ?> btn-sm">
                                        <i class="fas fa-<?= $client['status'] === 'active' ? 'pause' : 'play' ?> me-1"></i>
                                        <?= $client['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<?php include(APPPATH . 'Views/admin/templates/footer.php'); ?>

<script>
// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const companyName = document.getElementById('company_name').value;
        
        if (!email || !companyName) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });
});

// Auto-format phone number
document.getElementById('phone')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        value = value.match(/.{1,4}/g).join(' ');
    }
    e.target.value = value;
});
</script>