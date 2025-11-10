<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Dashboard extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'stats' => $this->adminModel->getDashboardStats()
        ];

        return view('admin/dashboard', $data);
    }
}