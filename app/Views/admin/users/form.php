<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <!-- Alert Messages (Traditional - sebagai fallback) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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
            <form method="post" action="<?= isset($user) ? base_url('/admin/users/update/' . ($user['id'] ?? $user->id ?? '')) : base_url('/admin/users/store') ?>" id="userForm">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username', $user['username'] ?? $user->username ?? '') ?>" 
                                   placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email', $user['email'] ?? $user->email ?? '') ?>" 
                                   placeholder="Enter email address" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   value="<?= old('first_name', $user['first_name'] ?? $user->first_name ?? '') ?>" 
                                   placeholder="Enter first name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   value="<?= old('last_name', $user['last_name'] ?? $user->last_name ?? '') ?>" 
                                   placeholder="Enter last name" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" <?= (old('role', $user['role'] ?? $user->role ?? '') == 'admin') ? 'selected' : '' ?>>Administrator</option>
                                <option value="manager" <?= (old('role', $user['role'] ?? $user->role ?? '') == 'manager') ? 'selected' : '' ?>>Manager</option>
                                <option value="staff" <?= (old('role', $user['role'] ?? $user->role ?? '') == 'staff') ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <?php 
                                $isActive = old('is_active', $user['is_active'] ?? $user->is_active ?? 1);
                                ?>
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" <?= ($isActive == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active User</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <?= !isset($user) ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="<?= isset($user) ? 'Leave blank to keep current password' : 'Enter password' ?>" 
                                   <?= !isset($user) ? 'required' : '' ?>>
                            <div class="form-text">
                                Password requirements:
                                <ul class="small mb-0">
                                    <li>At least 8 characters</li>
                                    <li>One uppercase letter</li>
                                    <li>One lowercase letter</li>
                                    <li>One number</li>
                                    <li>One special character (@$!%*?&)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">
                                Confirm Password <?= !isset($user) ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                   placeholder="Confirm password" 
                                   <?= !isset($user) ? 'required' : '' ?>>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-1"></i>
                                <?= isset($user) ? 'Update User' : 'Create User' ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<?= $this->include('admin/templates/footer') ?>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for errors from server and show SweetAlert
    <?php if (isset($errors) && !empty($errors)): ?>
        showErrors(<?= json_encode($errors) ?>);
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        showError('<?= session()->getFlashdata('error') ?>');
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        showSuccess('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>

    // Form submission handling
    const form = document.getElementById('userForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        // Client-side validation
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
    });

    function validateForm() {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;
        const isEdit = <?= isset($user) ? 'true' : 'false' ?>;

        // For create form, password is required
        if (!isEdit && password === '') {
            showError('Password is required for new users.');
            return false;
        }

        // If password is provided, check confirmation
        if (password !== '' && password !== passwordConfirm) {
            showError('Password and confirmation do not match.');
            return false;
        }

        // Password strength validation (if password is provided)
        if (password !== '') {
            const passwordErrors = validatePasswordStrength(password);
            if (passwordErrors.length > 0) {
                showErrors(passwordErrors);
                return false;
            }
        }

        return true;
    }

    function validatePasswordStrength(password) {
        const errors = [];

        if (password.length < 8) {
            errors.push('Password must be at least 8 characters long.');
        }

        if (!/[A-Z]/.test(password)) {
            errors.push('Password must contain at least one uppercase letter.');
        }

        if (!/[a-z]/.test(password)) {
            errors.push('Password must contain at least one lowercase letter.');
        }

        if (!/[0-9]/.test(password)) {
            errors.push('Password must contain at least one number.');
        }

        if (!/[@$!%*?&]/.test(password)) {
            errors.push('Password must contain at least one special character (@$!%*?&).');
        }

        return errors;
    }

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
                // Redirect to users list after success
                window.location.href = '<?= base_url('/admin/users') ?>';
            }
        });
    }

    // Auto-hide traditional alerts after 5 seconds
    const traditionalAlerts = document.querySelectorAll('.alert');
    traditionalAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>