<?php

use App\Controllers\Admin\Auth;
use App\Controllers\Admin\Dashboard;
use App\Controllers\Admin\Users;
use App\Controllers\Admin\Blog;
use App\Controllers\Admin\Services;
use App\Controllers\Admin\BlogCategories;
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

// Blog management routes
$routes->group('admin/blog', function($routes) {
    $routes->get('/', [Blog::class, 'index']);
    $routes->get('create', [Blog::class, 'create']);
    $routes->post('store', [Blog::class, 'store']);
    $routes->get('edit/(:num)', [Blog::class, 'edit']);
    $routes->post('update/(:num)', [Blog::class, 'update']);
    $routes->get('delete/(:num)', [Blog::class, 'delete']);
    $routes->get('toggle-status/(:num)', [Blog::class, 'toggleStatus']);
});

// Blog categories management routes
$routes->group('admin/blog/categories', function($routes) {
    $routes->get('/', [BlogCategories::class, 'index']);
    $routes->get('create', [BlogCategories::class, 'create']);
    $routes->post('store', [BlogCategories::class, 'store']);
    $routes->get('edit/(:num)', [BlogCategories::class, 'edit']);
    $routes->post('update/(:num)', [BlogCategories::class, 'update']);
    $routes->get('delete/(:num)', [BlogCategories::class, 'delete']);
});

$routes->get('/admin/settings', 'Admin\Settings::index');
$routes->post('/admin/settings/update', 'Admin\Settings::update');
$routes->post('/admin/settings/update-single', 'Admin\Settings::updateSingle');