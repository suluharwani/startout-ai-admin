<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AuthService;

class Auth extends BaseController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->authService->isLoggedIn()) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - Startout AI Admin',
            'validation' => \Config\Services::validation()
        ];

        return view('admin/auth/login', $data);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($this->authService->login($email, $password)) {
            return redirect()->to('/dashboard')->with('success', 'Welcome back!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password');
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/')->with('success', 'You have been logged out successfully');
    }
}