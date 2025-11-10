<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Install extends BaseController
{
    public function createAdmin()
    {
        $userModel = new UserModel();
        
        $adminData = [
            'username' => 'admin',
            'email' => 'admin@startoutai.com',
            'password_hash' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'role' => 'admin',
            'is_active' => 1
        ];

        if ($userModel->save($adminData)) {
            echo "Admin user created successfully!<br>";
            echo "Email: admin@startoutai.com<br>";
            echo "Password: Admin123!<br>";
        } else {
            echo "Failed to create admin user.";
        }
    }
}