<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AuthService;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = new AuthService();
        
        if (!$auth->isLoggedIn()) {
            return redirect()->to('/')->with('error', 'Please login to access the dashboard');
        }
        
        // Optional: Check if user has admin role
        if (!$auth->isAdmin()) {
            return redirect()->to('/')->with('error', 'Unauthorized access');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}