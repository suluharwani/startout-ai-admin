<?= $this->include('admin/templates/header') ?>
<?= $this->include('admin/templates/sidebar') ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><?= $title ?></h1>
        <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
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
            <form method="post" action="<?= isset($user) ? base_url('/admin/users/update/' . $user->id) : base_url('/admin/users/store') ?>">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username', $user->username ?? '') ?>" 
                                   placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email', $user->email ?? '') ?>" 
                                   placeholder="Enter email address" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   value="<?= old('first_name', $user->first_name ?? '') ?>" 
                                   placeholder="Enter first name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   value="<?= old('last_name', $user->last_name ?? '') ?>" 
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
                                <option value="admin" <?= (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' ?>>Administrator</option>
                                <option value="manager" <?= (old('role', $user->role ?? '') == 'manager') ? 'selected' : '' ?>>Manager</option>
                                <option value="staff" <?= (old('role', $user->role ?? '') == 'staff') ? 'selected' : '' ?>>Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" <?= (old('is_active', $user->is_active ?? 1) == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active User</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- ... existing code ... -->

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

<!-- ... existing code ... -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
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

<?= $this->include('admin/templates/footer') ?>