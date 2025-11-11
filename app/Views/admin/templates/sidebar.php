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
            
            <!-- Client Management Menu -->
            <li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle <?= strpos(current_url(), '/admin/clients') !== false || strpos(current_url(), '/admin/subscriptions') !== false || strpos(current_url(), '/admin/invoices') !== false ? 'active' : '' ?>" 
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-handshake me-2"></i>
                    Client Management
                </a>
                <ul class="dropdown-menu bg-dark">
                    <li>
                        <a class="dropdown-item text-white <?= strpos(current_url(), '/admin/clients') !== false ? 'active' : '' ?>" 
                           href="<?= base_url('/admin/clients') ?>">
                            <i class="fas fa-building me-2"></i>
                            Clients
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white <?= strpos(current_url(), '/admin/subscriptions') !== false ? 'active' : '' ?>" 
                           href="<?= base_url('/admin/subscriptions') ?>">
                            <i class="fas fa-file-contract me-2"></i>
                            Subscriptions
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white <?= strpos(current_url(), '/admin/invoices') !== false ? 'active' : '' ?>" 
                           href="<?= base_url('/admin/invoices') ?>">
                            <i class="fas fa-receipt me-2"></i>
                            Invoices
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= strpos(current_url(), '/admin/services') !== false ? 'active' : '' ?>" 
                   href="<?= base_url('/admin/services') ?>">
                    <i class="fas fa-cogs me-2"></i>
                    Services
                </a>
            </li>
            
            <!-- Blog Management Menu -->
            <li class="nav-item dropdown position-static">
                <a class="nav-link dropdown-toggle <?= strpos(current_url(), '/admin/blog') !== false ? 'active' : '' ?>" 
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-blog me-2"></i>
                    Blog Management
                </a>
                <ul class="dropdown-menu bg-dark">
                    <li>
                        <a class="dropdown-item text-white <?= strpos(current_url(), '/admin/blog') !== false && strpos(current_url(), '/categories') === false ? 'active' : '' ?>" 
                           href="<?= base_url('/admin/blog') ?>">
                            <i class="fas fa-newspaper me-2"></i>
                            Blog Posts
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-white <?= strpos(current_url(), '/admin/blog/categories') !== false ? 'active' : '' ?>" 
                           href="<?= base_url('/admin/blog/categories') ?>">
                            <i class="fas fa-tags me-2"></i>
                            Categories
                        </a>
                    </li>
                </ul>
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