<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manage Users',
            'users' => $this->userModel->orderBy('created_at', 'DESC')->findAll()
        ];

        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create New User'
        ];

        return view('admin/users/form', $data);
    }

    public function store()
    {
        // Manual validation
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'role' => 'required|in_list[admin,manager,staff]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Manual password strength validation
        $password = $this->request->getPost('password');
        $passwordErrors = $this->userModel->validatePasswordStrength($password);
        
        if (!empty($passwordErrors)) {
            return redirect()->back()->withInput()->with('errors', $passwordErrors);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => $this->userModel->hashPassword($password),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->userModel->save($userData)) {
            return redirect()->to('/admin/users')->with('success', 'User created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];

        return view('admin/users/form', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'role' => 'required|in_list[admin,manager,staff]'
        ];

        // Add password rules only if password is provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[8]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', \Config\Services::validation()->getErrors());
        }

        // Manual password strength validation if password is provided
        if (!empty($password)) {
            $passwordErrors = $this->userModel->validatePasswordStrength($password);
            if (!empty($passwordErrors)) {
                return redirect()->back()->withInput()->with('errors', $passwordErrors);
            }
        }

        $userData = [
            'id' => $id,
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Update password only if provided
        if (!empty($password)) {
            $userData['password_hash'] = $this->userModel->hashPassword($password);
        }

        if ($this->userModel->save($userData)) {
            return redirect()->to('/admin/users')->with('success', 'User updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function delete($id)
    {
        // Prevent deleting yourself
        if ($id == session()->get('admin_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot delete your own account.');
        }

        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->to('/admin/users')->with('error', 'Failed to delete user. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }

        // Prevent deactivating yourself
        if ($id == session()->get('admin_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->is_active ? 0 : 1;
        $statusText = $newStatus ? 'activated' : 'deactivated';

        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            return redirect()->to('/admin/users')->with('success', "User {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/users')->with('error', "Failed to {$statusText} user.");
        }
    }
}