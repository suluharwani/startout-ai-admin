<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= current_url() == base_url('/dashboard') ? 'active' : '' ?>" 
                   href="<?= base_url('/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/users') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/users') ?>">
                    <i class="fas fa-users me-2"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/services') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/services') ?>">
                    <i class="fas fa-cogs me-2"></i>
                    Services
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/blog') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/blog') ?>">
                    <i class="fas fa-blog me-2"></i>
                    Blog Posts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/jobs') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/jobs') ?>">
                    <i class="fas fa-briefcase me-2"></i>
                    Job Positions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/settings') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/settings') ?>">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
</nav>