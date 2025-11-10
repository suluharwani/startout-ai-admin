<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function isLoggedIn()
    {
        return $this->session->has('admin_logged_in') && $this->session->get('admin_logged_in') === true;
    }

    public function isAdmin()
    {
        return $this->session->get('admin_role') === 'admin';
    }

    public function isManager()
    {
        return in_array($this->session->get('admin_role'), ['admin', 'manager']);
    }

    public function login($email, $password)
    {
        $user = $this->userModel->where('email', $email)->first();
        
        // Perbaikan: first() mengembalikan array, bukan object
        if (!$user) {
            return false;
        }

        // Convert to object jika perlu, atau gunakan sebagai array
        if (!$this->userModel->verifyPassword($password, $user['password_hash'])) {
            return false;
        }

        if (!$user['is_active']) {
            return false;
        }

        // Set session
        $this->session->set([
            'admin_logged_in' => true,
            'admin_id' => $user['id'],
            'admin_email' => $user['email'],
            'admin_name' => $user['first_name'] . ' ' . $user['last_name'],
            'admin_role' => $user['role']
        ]);

        // Update last login
        $this->userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);

        return true;
    }

    public function logout()
    {
        $this->session->destroy();
    }

    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->userModel->find($this->session->get('admin_id'));
    }
}