<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getDashboardStats()
    {
        $stats = [];
        
        // Total Users
        $builder = $this->db->table('users');
        $stats['total_users'] = $builder->countAll();
        
        // Total Services
        $builder = $this->db->table('services');
        $stats['total_services'] = $builder->countAll();
        
        // Total Blog Posts
        $builder = $this->db->table('blog_posts');
        $stats['total_posts'] = $builder->countAll();
        
        // Total Job Applications
        $builder = $this->db->table('job_applications');
        $stats['total_applications'] = $builder->countAll();
        
        // Recent Activities
        $stats['recent_users'] = $this->db->table('users')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResult();
            
        return $stats;
    }
}