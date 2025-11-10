<?php

use App\Controllers\Admin\Auth;
use App\Controllers\Admin\Dashboard;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Services;
use App\Controllers\Install as Setup;
use App\Filters\AdminFilter;

// Setup route (remove in production)
$routes->get('setup/create-admin', [Setup::class, 'createAdmin']);

// Public routes
$routes->get('/', [Auth::class, 'login']);
$routes->post('/login', [Auth::class, 'attemptLogin']);
$routes->get('/logout', [Auth::class, 'logout']);

// Protected admin routes
$routes->group('', ['filter' => AdminFilter::class], function($routes) {
    $routes->get('dashboard', [Dashboard::class, 'index']);
    
    // User management routes
    $routes->group('admin/users', function($routes) {
        $routes->get('/', [Users::class, 'index']);
        $routes->get('create', [Users::class, 'create']);
        $routes->post('store', [Users::class, 'store']);
        $routes->get('edit/(:num)', [Users::class, 'edit']);
        $routes->post('update/(:num)', [Users::class, 'update']);
        $routes->get('delete/(:num)', [Users::class, 'delete']);
        $routes->get('toggle-status/(:num)', [Users::class, 'toggleStatus']);
    });
});
// Services management routes
$routes->group('admin/services', function($routes) {
    $routes->get('/', [Services::class, 'index']);
    $routes->get('create', [Services::class, 'create']);
    $routes->post('store', [Services::class, 'store']);
    $routes->get('edit/(:num)', [Services::class, 'edit']);
    $routes->post('update/(:num)', [Services::class, 'update']);
    $routes->get('delete/(:num)', [Services::class, 'delete']);
    $routes->get('toggle-status/(:num)', [Services::class, 'toggleStatus']);
    $routes->post('update-sort-order', [Services::class, 'updateSortOrder']);
});