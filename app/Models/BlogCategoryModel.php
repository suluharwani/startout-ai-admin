<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogCategoryModel extends Model
{
    protected $table = 'blog_categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'description'];
    protected $useTimestamps = true;
    protected $returnType = 'array';

    public $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|alpha_dash|min_length[2]|max_length[100]',
        'description' => 'permit_empty|max_length[500]'
    ];

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

    public function getCategoriesWithPostCount()
{
    return $this->select('blog_categories.*, COUNT(blog_post_categories.post_id) as post_count')
                ->join('blog_post_categories', 'blog_categories.id = blog_post_categories.category_id', 'left')
                ->groupBy('blog_categories.id')
                ->orderBy('blog_categories.name', 'ASC')
                ->findAll();
}
}