<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('/admin/services') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Services
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= isset($service) ? base_url('/admin/services/update/' . ($service['id'] ?? '')) : base_url('/admin/services/store') ?>" id="serviceForm">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name', $service['name'] ?? '') ?>" 
                                   placeholder="Enter service name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">/</span>
                                <input type="text" class="form-control" id="slug" name="slug" 
                                       value="<?= old('slug', $service['slug'] ?? '') ?>" 
                                       placeholder="service-slug" required>
                                <button type="button" class="btn btn-outline-secondary" id="generateSlug">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="form-text">URL-friendly version of the name (lowercase, hyphens)</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="icon_class" class="form-label">Icon Class</label>
                            <input type="text" class="form-control" id="icon_class" name="icon_class" 
                                   value="<?= old('icon_class', $service['icon_class'] ?? '') ?>" 
                                   placeholder="fas fa-cog">
                            <div class="form-text">Font Awesome icon class (e.g., fas fa-cog, fas fa-chart-line)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                   value="<?= old('sort_order', $service['sort_order'] ?? 0) ?>" 
                                   min="0" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Short Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" placeholder="Brief description of the service" required><?= old('description', $service['description'] ?? '') ?></textarea>
                    <div class="form-text">Maximum 500 characters</div>
                </div>

                <div class="mb-3">
                    <label for="detailed_description" class="form-label">Detailed Description</label>
                    <textarea class="form-control" id="detailed_description" name="detailed_description" 
                              rows="5" placeholder="Detailed description of the service"><?= old('detailed_description', $service['detailed_description'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_service_id" class="form-label">Parent Service</label>
                            <select class="form-select" id="parent_service_id" name="parent_service_id">
                                <option value="">No Parent (Main Service)</option>
                                <?php if (!empty($parentServices)): ?>
                                    <?php foreach ($parentServices as $parent): ?>
                                        <?php 
                                        $selected = (old('parent_service_id', $service['parent_service_id'] ?? '') == $parent['id']) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $parent['id'] ?>" <?= $selected ?>>
                                            <?= esc($parent['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <?php 
                                $isActive = old('is_active', $service['is_active'] ?? 1);
                                ?>
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" <?= ($isActive == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active Service</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" 
                                   value="<?= old('image_url', $service['image_url'] ?? '') ?>" 
                                   placeholder="https://example.com/image.jpg">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="<?= old('meta_title', $service['meta_title'] ?? '') ?>" 
                                   placeholder="SEO meta title">
                            <div class="form-text">Title for SEO (optional)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="2" placeholder="SEO meta description"><?= old('meta_description', $service['meta_description'] ?? '') ?></textarea>
                            <div class="form-text">Description for SEO (optional)</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/admin/services') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <?= isset($service) ? 'Update Service' : 'Create Service' ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const generateSlugBtn = document.getElementById('generateSlug');
    const form = document.getElementById('serviceForm');
    const submitBtn = document.getElementById('submitBtn');

    // Generate slug from name
    function generateSlug(text) {
        return text.toLowerCase()
                   .replace(/[^a-z0-9 -]/g, '')
                   .replace(/\s+/g, '-')
                   .replace(/-+/g, '-')
                   .trim();
    }

    // Auto-generate slug when name changes
    nameInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            slugInput.value = generateSlug(this.value);
        }
    });

    // Manual slug generation
    generateSlugBtn.addEventListener('click', function() {
        if (nameInput.value) {
            slugInput.value = generateSlug(nameInput.value);
        }
    });

    // Form submission handling
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
    });

    // Check for errors and show SweetAlert
    // <?php if (isset($errors) && !empty($errors)): ?>
    //     showErrors(<?= json_encode($errors) ?>);
    // <?php endif; ?>

    // <?php if (session()->getFlashdata('error')): ?>
    //     showError('<?= session()->getFlashdata('error') ?>');
    // <?php endif; ?>

    // <?php if (session()->getFlashdata('success')): ?>
    //     showSuccess('<?= session()->getFlashdata('success') ?>');
    // <?php endif; ?>}

    function showErrors(errors) {
        let errorMessage = '';
        
        if (Array.isArray(errors)) {
            errorMessage = '<ul class="text-start">';
            errors.forEach(error => {
                errorMessage += `<li>${error}</li>`;
            });
            errorMessage += '</ul>';
        } else {
            errorMessage = errors;
        }

        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: errorMessage,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
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

    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('/admin/services') ?>';
            }
        });
    }

    // Auto-hide traditional alerts
    const traditionalAlerts = document.querySelectorAll('.alert');
    traditionalAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<?= $this->include('admin/templates/footer') ?>