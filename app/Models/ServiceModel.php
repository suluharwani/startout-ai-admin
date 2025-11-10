<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'slug', 'description', 'detailed_description', 'icon_class',
        'image_url', 'is_active', 'sort_order', 'parent_service_id',
        'meta_title', 'meta_description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Simple validation rules - tanpa placeholder
    public $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]|max_length[500]',
        'icon_class' => 'permit_empty|max_length[100]',
        'sort_order' => 'permit_empty|integer'
    ];

    public function getActiveServices()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getParentServices()
    {
        return $this->where('parent_service_id', null)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
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

    public function getServiceWithParent($id)
    {
        return $this->select('services.*, parent.name as parent_name')
                    ->join('services as parent', 'services.parent_service_id = parent.id', 'left')
                    ->where('services.id', $id)
                    ->first();
    }

    /**
     * Check if slug is unique (excluding current ID for updates)
     */
    public function isSlugUnique($slug, $id = null)
    {
        $builder = $this->where('slug', $slug);
        
        if ($id) {
            $builder->where('id !=', $id);
        }
        
        return $builder->countAllResults() === 0;
    }
}