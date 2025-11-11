<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startout AI Admin - <?= $title ?? 'Dashboard' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <style>
            .sidebar {
        min-height: calc(100vh - 56px);
        background-color: #343a40;
        z-index: 1000;
    }
    .sidebar .nav-link {
        color: #fff;
        padding: 0.75rem 1rem;
        border: none;
        transition: all 0.3s;
    }
    .sidebar .nav-link:hover {
        background-color: #495057;
        color: #fff;
    }
    .sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
    
    /* Dropdown styling */
    .sidebar .dropdown-menu {
        background-color: #495057;
        border: none;
        border-radius: 0.375rem;
        margin: 0;
        padding: 0;
        /* Geser dropdown ke kanan */
        position: absolute;
        left: 100% !important;
        top: 0 !important;
        margin-left: 1px;
        min-width: 200px;
    }
    
    .sidebar .dropdown-item {
        color: #fff;
        padding: 0.75rem 1rem;
        border: none;
        transition: all 0.3s;
    }
    
    .sidebar .dropdown-item:hover,
    .sidebar .dropdown-item:focus {
        background-color: #5a6268;
        color: #fff;
    }
    
    .sidebar .dropdown-item.active {
        background-color: #0d6efd;
        color: #fff;
    }
    
    .sidebar .dropdown-toggle::after {
        float: right;
        margin-top: 8px;
    }
    
    /* Dropdown positioning untuk sidebar */
    .sidebar .nav-item.dropdown {
        position: relative;
    }
    
    /* Pastikan dropdown tidak terpotong */
    .sidebar .dropdown-menu.show {
        display: block;
    }
    

    
    .navbar-brand {
        font-weight: bold;
    }
    
    /* SweetAlert custom styling */
    .swal2-popup {
        font-size: 1rem;
    }
    
    /* Page title styling */
    .page-title-box {
        padding: 20px 0;
    }
    
    /* Card header styling */
    .card-header h5 {
        margin-bottom: 0;
    }
</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/dashboard') ?>">
                <i class="fas fa-robot me-2"></i>
                Startout AI Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= session()->get('admin_name') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i>Profile
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('/logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">