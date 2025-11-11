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
$routes->get('/admin/settings', 'Admin\Settings::index');
$routes->post('/admin/settings/update', 'Admin\Settings::update');
$routes->post('/admin/settings/update-single', 'Admin\Settings::updateSingle');

$routes->get('/admin/jobs', 'Admin\Jobs::index');
$routes->get('/admin/jobs/create', 'Admin\Jobs::create');
$routes->post('/admin/jobs/store', 'Admin\Jobs::store');
$routes->get('/admin/jobs/edit/(:num)', 'Admin\Jobs::edit/$1');
$routes->post('/admin/jobs/update/(:num)', 'Admin\Jobs::update/$1');
$routes->get('/admin/jobs/delete/(:num)', 'Admin\Jobs::delete/$1');
$routes->get('/admin/jobs/toggle-status/(:num)', 'Admin\Jobs::toggleStatus/$1');

// Client Management Routes
// Client Management Routes
$routes->get('/admin/clients', 'Admin\Clients::index');
$routes->get('/admin/clients/create', 'Admin\Clients::create');
$routes->post('/admin/clients/store', 'Admin\Clients::store');
$routes->get('/admin/clients/edit/(:num)', 'Admin\Clients::edit/$1');
$routes->post('/admin/clients/update/(:num)', 'Admin\Clients::update/$1');
$routes->get('/admin/clients/delete/(:num)', 'Admin\Clients::delete/$1');
$routes->get('/admin/clients/toggle-status/(:num)', 'Admin\Clients::toggleStatus/$1');

// Subscriptions Routes
$routes->get('/admin/subscriptions', 'Admin\Subscriptions::index');
$routes->get('/admin/subscriptions/create', 'Admin\Subscriptions::create');
$routes->post('/admin/subscriptions/store', 'Admin\Subscriptions::store');
$routes->get('/admin/subscriptions/edit/(:num)', 'Admin\Subscriptions::edit/$1');
$routes->post('/admin/subscriptions/update/(:num)', 'Admin\Subscriptions::update/$1');
$routes->get('/admin/subscriptions/delete/(:num)', 'Admin\Subscriptions::delete/$1');
$routes->get('/admin/subscriptions/toggle-status/(:num)', 'Admin\Subscriptions::toggleStatus/$1');


// Invoices Routes
$routes->get('/admin/invoices', 'Admin\Invoices::index');
$routes->get('/admin/invoices/create', 'Admin\Invoices::create');
$routes->post('/admin/invoices/store', 'Admin\Invoices::store');
$routes->get('/admin/invoices/view/(:num)', 'Admin\Invoices::view/$1');
$routes->get('/admin/invoices/edit/(:num)', 'Admin\Invoices::edit/$1');
$routes->post('/admin/invoices/update/(:num)', 'Admin\Invoices::update/$1');
$routes->get('/admin/invoices/delete/(:num)', 'Admin\Invoices::delete/$1');
$routes->post('/admin/invoices/mark-as-paid/(:num)', 'Admin\Invoices::markAsPaid/$1');
$routes->get('/admin/invoices/download/(:num)', 'Admin\Invoices::download/$1');

// Industries Routes
$routes->get('/admin/industries', 'Admin\Industries::index');
$routes->get('/admin/industries/create', 'Admin\Industries::create');
$routes->post('/admin/industries/store', 'Admin\Industries::store');