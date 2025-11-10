<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('/admin/blog/categories') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Categories
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
            <form method="post" action="<?= isset($category) ? base_url('/admin/blog/categories/update/' . ($category['id'] ?? '')) : base_url('/admin/blog/categories/store') ?>" id="categoryForm">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= old('name', $category['name'] ?? '') ?>" 
                                   placeholder="Enter category name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <div class="input-group">
                                <span class="input-group-text">/</span>
                                <input type="text" class="form-control" id="slug" name="slug" 
                                       value="<?= old('slug', $category['slug'] ?? '') ?>" 
                                       placeholder="category-slug">
                                <button type="button" class="btn btn-outline-secondary" id="generateSlug">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="form-text">URL-friendly version of the name</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3" placeholder="Brief description of the category"><?= old('description', $category['description'] ?? '') ?></textarea>
                    <div class="form-text">Optional description for the category</div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/admin/blog/categories') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <?= isset($category) ? 'Update Category' : 'Create Category' ?>
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
    const form = document.getElementById('categoryForm');
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
    <?php if (isset($errors) && !empty($errors)): ?>
        showErrors(<?= json_encode($errors) ?>);
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        showError('<?= session()->getFlashdata('error') ?>');
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        showSuccess('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>

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
                window.location.href = '<?= base_url('/admin/blog/categories') ?>';
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