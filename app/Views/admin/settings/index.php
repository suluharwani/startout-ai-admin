<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Site Settings</h4>
            </div>
        </div>
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

    <form action="<?= base_url('/admin/settings/update') ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Company Information -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>
                    Company Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (isset($groupedSettings['company_info'])): ?>
                        <?php foreach ($groupedSettings['company_info'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="setting_<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= ucwords(str_replace('_', ' ', $setting['setting_key'])) ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted d-block"><?= $setting['description'] ?></small>
                                    <?php endif; ?>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="setting_<?= $setting['setting_key'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-book me-2"></i>
                    Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (isset($groupedSettings['contact_info'])): ?>
                        <?php foreach ($groupedSettings['contact_info'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="setting_<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= ucwords(str_replace('_', ' ', $setting['setting_key'])) ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted d-block"><?= $setting['description'] ?></small>
                                    <?php endif; ?>
                                </label>
                                <input type="<?= strpos($setting['setting_key'], 'email') !== false ? 'email' : 'text' ?>" 
                                       class="form-control" 
                                       id="setting_<?= $setting['setting_key'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-share-alt me-2"></i>
                    Social Media Links
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (isset($groupedSettings['social_media'])): ?>
                        <?php foreach ($groupedSettings['social_media'] as $setting): ?>
                            <div class="col-md-6 mb-3">
                                <label for="setting_<?= $setting['setting_key'] ?>" class="form-label">
                                    <i class="fab fa-<?= str_replace('social_', '', $setting['setting_key']) ?> me-2"></i>
                                    <?= ucwords(str_replace('_', ' ', str_replace('social_', '', $setting['setting_key']))) ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted d-block"><?= $setting['description'] ?></small>
                                    <?php endif; ?>
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="setting_<?= $setting['setting_key'] ?>" 
                                       name="settings[<?= $setting['setting_key'] ?>]" 
                                       value="<?= htmlspecialchars($setting['setting_value']) ?>"
                                       placeholder="https://...">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Site Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-globe me-2"></i>
                    Site Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if (isset($groupedSettings['site_info'])): ?>
                        <?php foreach ($groupedSettings['site_info'] as $setting): ?>
                            <div class="col-12 mb-3">
                                <label for="setting_<?= $setting['setting_key'] ?>" class="form-label">
                                    <?= ucwords(str_replace('_', ' ', $setting['setting_key'])) ?>
                                    <?php if ($setting['description']): ?>
                                        <small class="text-muted d-block"><?= $setting['description'] ?></small>
                                    <?php endif; ?>
                                </label>
                                <?php if ($setting['setting_type'] === 'boolean'): ?>
                                    <select class="form-select" id="setting_<?= $setting['setting_key'] ?>" name="settings[<?= $setting['setting_key'] ?>]">
                                        <option value="1" <?= $setting['setting_value'] ? 'selected' : '' ?>>Enabled</option>
                                        <option value="0" <?= !$setting['setting_value'] ? 'selected' : '' ?>>Disabled</option>
                                    </select>
                                <?php elseif (strlen($setting['setting_value']) > 100): ?>
                                    <textarea class="form-control" 
                                              id="setting_<?= $setting['setting_key'] ?>" 
                                              name="settings[<?= $setting['setting_key'] ?>]" 
                                              rows="3"><?= htmlspecialchars($setting['setting_value']) ?></textarea>
                                <?php else: ?>
                                    <input type="text" 
                                           class="form-control" 
                                           id="setting_<?= $setting['setting_key'] ?>" 
                                           name="settings[<?= $setting['setting_key'] ?>]" 
                                           value="<?= htmlspecialchars($setting['setting_value']) ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Save All Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Real-time saving for individual settings (optional enhancement)
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Optional: Add real-time saving functionality here
            console.log('Setting changed:', this.name, this.value);
        });
    });
});
</script>
<?= $this->endSection() ?>