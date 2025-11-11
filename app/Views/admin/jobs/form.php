<?php include(APPPATH . 'Views/admin/templates/header.php'); ?>

<div class="container-fluid">
    <div class="row">
        <?php include(APPPATH . 'Views/admin/templates/sidebar.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= isset($job) ? 'Edit Job Position' : 'Create New Job Position' ?></h1>
                <a href="<?= base_url('/admin/jobs') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Jobs
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

            <form action="<?= isset($job) ? base_url("/admin/jobs/update/{$job['id']}") : base_url('/admin/jobs/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Job Title *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="title" 
                                               name="title" 
                                               value="<?= old('title', $job['title'] ?? '') ?>" 
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="slug" class="form-label">Slug *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="slug" 
                                               name="slug" 
                                               value="<?= old('slug', $job['slug'] ?? '') ?>" 
                                               required>
                                        <small class="text-muted">URL-friendly version of the title</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="department" class="form-label">Department *</label>
                                        <select class="form-select" id="department" name="department" required>
                                            <option value="">Select Department</option>
                                            <option value="engineering" <?= old('department', $job['department'] ?? '') == 'engineering' ? 'selected' : '' ?>>Engineering</option>
                                            <option value="operations" <?= old('department', $job['department'] ?? '') == 'operations' ? 'selected' : '' ?>>Operations</option>
                                            <option value="product" <?= old('department', $job['department'] ?? '') == 'product' ? 'selected' : '' ?>>Product</option>
                                            <option value="sales" <?= old('department', $job['department'] ?? '') == 'sales' ? 'selected' : '' ?>>Sales</option>
                                            <option value="marketing" <?= old('department', $job['department'] ?? '') == 'marketing' ? 'selected' : '' ?>>Marketing</option>
                                            <option value="hr" <?= old('department', $job['department'] ?? '') == 'hr' ? 'selected' : '' ?>>HR</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="employment_type" class="form-label">Employment Type *</label>
                                        <select class="form-select" id="employment_type" name="employment_type" required>
                                            <option value="">Select Type</option>
                                            <option value="full-time" <?= old('employment_type', $job['employment_type'] ?? '') == 'full-time' ? 'selected' : '' ?>>Full-time</option>
                                            <option value="part-time" <?= old('employment_type', $job['employment_type'] ?? '') == 'part-time' ? 'selected' : '' ?>>Part-time</option>
                                            <option value="contract" <?= old('employment_type', $job['employment_type'] ?? '') == 'contract' ? 'selected' : '' ?>>Contract</option>
                                            <option value="internship" <?= old('employment_type', $job['employment_type'] ?? '') == 'internship' ? 'selected' : '' ?>>Internship</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="location" 
                                               name="location" 
                                               value="<?= old('location', $job['location'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_remote" 
                                               name="is_remote" 
                                               value="1" 
                                               <?= old('is_remote', $job['is_remote'] ?? '') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_remote">
                                            This is a remote position
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Job Description *</h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="6" 
                                          required><?= old('description', $job['description'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Requirements *</h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" 
                                          id="requirements" 
                                          name="requirements" 
                                          rows="6" 
                                          required><?= old('requirements', $job['requirements'] ?? '') ?></textarea>
                                <small class="text-muted">List the qualifications and skills required for this position</small>
                            </div>
                        </div>

                        <!-- Responsibilities -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Responsibilities *</h5>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" 
                                          id="responsibilities" 
                                          name="responsibilities" 
                                          rows="6" 
                                          required><?= old('responsibilities', $job['responsibilities'] ?? '') ?></textarea>
                                <small class="text-muted">Describe the day-to-day responsibilities of this role</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               <?= old('is_active', $job['is_active'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active (Accepting applications)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-save me-2"></i>
                                    <?= isset($job) ? 'Update Job Position' : 'Create Job Position' ?>
                                </button>
                                <a href="<?= base_url('/admin/jobs') ?>" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
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
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slugInput = document.getElementById('slug');
    
    if (slugInput.value === '' || slugInput.dataset.manual !== 'true') {
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        slugInput.value = slug;
    }
});

// Mark slug as manually edited
document.getElementById('slug').addEventListener('input', function() {
    this.dataset.manual = 'true';
});
</script>