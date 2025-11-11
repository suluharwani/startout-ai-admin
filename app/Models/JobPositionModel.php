<?php

namespace App\Models;

use CodeIgniter\Model;

class JobPositionModel extends Model
{
    protected $table = 'job_positions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'slug', 'department', 'location', 'employment_type',
        'description', 'requirements', 'responsibilities', 'is_remote',
        'is_active', 'application_count'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Validation rules
    public $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|alpha_dash|min_length[3]|max_length[255]',
        'department' => 'required|in_list[engineering,operations,product,sales,marketing,hr]',
        'employment_type' => 'required|in_list[full-time,part-time,contract,internship]',
        'description' => 'required|min_length[50]',
        'requirements' => 'required|min_length[50]',
        'responsibilities' => 'required|min_length[50]'
    ];

    public function getActivePositions()
    {
        return $this->where('is_active', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function generateSlug($title)
    {
        $slug = url_title($title, '-', true);
        $count = 0;
        $originalSlug = $slug;
        
        while ($this->where('slug', $slug)->first()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }
        
        return $slug;
    }

    public function isSlugUnique($slug, $id = null)
    {
        $builder = $this->where('slug', $slug);
        
        if ($id) {
            $builder->where('id !=', $id);
        }
        
        return $builder->countAllResults() === 0;
    }

    public function incrementApplicationCount($id)
    {
        return $this->set('application_count', 'application_count + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function getPositionsWithApplicationCount()
    {
        return $this->select('job_positions.*, COUNT(job_applications.id) as total_applications')
                    ->join('job_applications', 'job_positions.id = job_applications.job_position_id', 'left')
                    ->groupBy('job_positions.id')
                    ->orderBy('job_positions.created_at', 'DESC')
                    ->findAll();
    }
}