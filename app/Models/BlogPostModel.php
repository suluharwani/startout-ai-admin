<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogPostModel extends Model
{
    protected $table = 'blog_posts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'slug', 'excerpt', 'content', 'featured_image_url', 
        'author_id', 'is_published', 'published_at', 'meta_title', 
        'meta_description', 'view_count'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    // Validation rules
    public $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|alpha_dash|min_length[3]|max_length[255]',
        'excerpt' => 'required|min_length[10]|max_length[500]',
        'content' => 'required|min_length[50]',
        'featured_image_url' => 'permit_empty|valid_url',
        'meta_title' => 'permit_empty|max_length[255]',
        'meta_description' => 'permit_empty|max_length[500]'
    ];

    public function getPublishedPosts()
    {
        return $this->where('is_published', 1)
                    ->where('published_at <=', date('Y-m-d H:i:s'))
                    ->orderBy('published_at', 'DESC')
                    ->findAll();
    }

    public function getPostsWithAuthor()
    {
        return $this->select('blog_posts.*, users.first_name, users.last_name')
                    ->join('users', 'blog_posts.author_id = users.id')
                    ->orderBy('blog_posts.created_at', 'DESC')
                    ->findAll();
    }

    public function getPostWithAuthor($id)
    {
        return $this->select('blog_posts.*, users.first_name, users.last_name')
                    ->join('users', 'blog_posts.author_id = users.id')
                    ->where('blog_posts.id', $id)
                    ->first();
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

    public function incrementViewCount($id)
    {
        return $this->set('view_count', 'view_count + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function getPostsByCategory($categoryId)
    {
        return $this->select('blog_posts.*, users.first_name, users.last_name')
                    ->join('users', 'blog_posts.author_id = users.id')
                    ->join('blog_post_categories', 'blog_posts.id = blog_post_categories.post_id')
                    ->where('blog_post_categories.category_id', $categoryId)
                    ->where('blog_posts.is_published', 1)
                    ->where('blog_posts.published_at <=', date('Y-m-d H:i:s'))
                    ->orderBy('blog_posts.published_at', 'DESC')
                    ->findAll();
    }
}