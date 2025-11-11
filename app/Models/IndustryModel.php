<?php

namespace App\Models;

use CodeIgniter\Model;

class IndustryModel extends Model
{
    protected $table = 'industries';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'description', 'icon_class', 'is_active', 'sort_order'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'slug' => 'required|alpha_dash|min_length[2]|max_length[255]',
        'description' => 'permit_empty|max_length[500]'
    ];

    public function getActiveIndustries()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function generateSlug($name)
    {
        $slug = url_title($name, '-', true);
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
}