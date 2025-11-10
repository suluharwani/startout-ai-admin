<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('/admin/blog') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Posts
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
            <form method="post" action="<?= isset($post) ? base_url('/admin/blog/update/' . ($post['id'] ?? '')) : base_url('/admin/blog/store') ?>" id="blogForm">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Post Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= old('title', $post['title'] ?? '') ?>" 
                                   placeholder="Enter post title" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <div class="input-group">
                                <span class="input-group-text">/</span>
                                <input type="text" class="form-control" id="slug" name="slug" 
                                       value="<?= old('slug', $post['slug'] ?? '') ?>" 
                                       placeholder="post-slug">
                                <button type="button" class="btn btn-outline-secondary" id="generateSlug">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="form-text">URL-friendly version of the title</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Excerpt <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="excerpt" name="excerpt" 
                              rows="3" placeholder="Brief excerpt of the post" required><?= old('excerpt', $post['excerpt'] ?? '') ?></textarea>
                    <div class="form-text">Maximum 500 characters</div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" 
                              rows="10" placeholder="Post content" required><?= old('content', $post['content'] ?? '') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="featured_image_url" class="form-label">Featured Image URL</label>
                            <input type="url" class="form-control" id="featured_image_url" name="featured_image_url" 
                                   value="<?= old('featured_image_url', $post['featured_image_url'] ?? '') ?>" 
                                   placeholder="https://example.com/image.jpg">
                            <?php if (!empty($post['featured_image_url'])): ?>
                                <div class="mt-2">
                                    <img src="<?= $post['featured_image_url'] ?>" alt="Featured Image" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="author_id" class="form-label">Author</label>
                            <select class="form-select" id="author_id" name="author_id" required>
                                <option value="">Select Author</option>
                                <?php if (!empty($authors)): ?>
                                    <?php foreach ($authors as $author): ?>
                                        <?php 
                                        $selected = (old('author_id', $post['author_id'] ?? '') == $author['id']) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $author['id'] ?>" <?= $selected ?>>
                                            <?= esc($author['first_name'] . ' ' . $author['last_name']) ?> (<?= $author['email'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="categories[]" 
                                                   value="<?= $category['id'] ?>" 
                                                   id="category_<?= $category['id'] ?>"
                                                   <?= (in_array($category['id'], $selectedCategoryIds ?? [])) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="category_<?= $category['id'] ?>">
                                                <?= esc($category['name']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No categories available. <a href="#">Create categories first</a>.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Publication Status</label>
                            <div class="form-check form-switch mt-2">
                                <?php 
                                $isPublished = old('is_published', $post['is_published'] ?? 0);
                                ?>
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                       value="1" <?= ($isPublished == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_published">Publish this post</label>
                            </div>
                            <div class="form-text">When published, the post will be visible to the public.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="<?= old('meta_title', $post['meta_title'] ?? '') ?>" 
                                   placeholder="SEO meta title">
                            <div class="form-text">Title for SEO (optional)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="2" placeholder="SEO meta description"><?= old('meta_description', $post['meta_description'] ?? '') ?></textarea>
                            <div class="form-text">Description for SEO (optional)</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/admin/blog') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <?= isset($post) ? 'Update Post' : 'Create Post' ?>
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
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const generateSlugBtn = document.getElementById('generateSlug');
    const form = document.getElementById('blogForm');
    const submitBtn = document.getElementById('submitBtn');

    // Generate slug from title
    function generateSlug(text) {
        return text.toLowerCase()
                   .replace(/[^a-z0-9 -]/g, '')
                   .replace(/\s+/g, '-')
                   .replace(/-+/g, '-')
                   .trim();
    }

    // Auto-generate slug when title changes
    titleInput.addEventListener('blur', function() {
        if (!slugInput.value) {
            slugInput.value = generateSlug(this.value);
        }
    });

    // Manual slug generation
    generateSlugBtn.addEventListener('click', function() {
        if (titleInput.value) {
            slugInput.value = generateSlug(titleInput.value);
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