<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogPostModel;
use App\Models\BlogCategoryModel;
use App\Models\UserModel;

class Blog extends BaseController
{
    protected $blogPostModel;
    protected $blogCategoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->blogPostModel = new BlogPostModel();
        $this->blogCategoryModel = new BlogCategoryModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $posts = $this->blogPostModel->getPostsWithAuthor();

        $data = [
            'title' => 'Manage Blog Posts',
            'posts' => $posts
        ];

        return view('admin/blog/index', $data);
    }

    public function create()
    {
        $categories = $this->blogCategoryModel->findAll();
        $authors = $this->userModel->where('is_active', 1)->findAll();

        $data = [
            'title' => 'Create New Blog Post',
            'categories' => $categories,
            'authors' => $authors
        ];

        return view('admin/blog/form', $data);
    }

    public function store()
    {
        // Basic validation
        if (!$this->validate($this->blogPostModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->blogPostModel->generateSlug($title);
        }

        // Manual slug uniqueness check
        if (!$this->blogPostModel->isSlugUnique($slug)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        // Handle published_at
        $isPublished = $this->request->getPost('is_published') ? 1 : 0;
        $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;

        $postData = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'featured_image_url' => $this->request->getPost('featured_image_url'),
            'author_id' => $this->request->getPost('author_id') ?: session()->get('admin_id'),
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        if ($this->blogPostModel->save($postData)) {
            $postId = $this->blogPostModel->getInsertID();
            
            // Handle categories
            $categories = $this->request->getPost('categories');
            if ($categories && is_array($categories)) {
                $this->savePostCategories($postId, $categories);
            }

            return redirect()->to('/admin/blog')->with('success', 'Blog post created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create blog post. Please try again.');
        }
    }

    public function edit($id)
    {
        $post = $this->blogPostModel->getPostWithAuthor($id);
        
        if (!$post) {
            return redirect()->to('/admin/blog')->with('error', 'Blog post not found.');
        }

        $categories = $this->blogCategoryModel->findAll();
        $authors = $this->userModel->where('is_active', 1)->findAll();

        // Get selected categories for this post
        $db = db_connect();
        $selectedCategories = $db->table('blog_post_categories')
                                ->where('post_id', $id)
                                ->get()
                                ->getResultArray();
        
        $selectedCategoryIds = array_column($selectedCategories, 'category_id');

        $data = [
            'title' => 'Edit Blog Post',
            'post' => $post,
            'categories' => $categories,
            'authors' => $authors,
            'selectedCategoryIds' => $selectedCategoryIds
        ];

        return view('admin/blog/form', $data);
    }

    public function update($id)
    {
        $post = $this->blogPostModel->find($id);
        
        if (!$post) {
            return redirect()->to('/admin/blog')->with('error', 'Blog post not found.');
        }

        // Basic validation
        if (!$this->validate($this->blogPostModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->blogPostModel->generateSlug($title);
        }

        // Manual slug uniqueness check (excluding current ID)
        if (!$this->blogPostModel->isSlugUnique($slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        // Handle published_at
        $isPublished = $this->request->getPost('is_published') ? 1 : 0;
        $publishedAt = $isPublished ? ($post['published_at'] ?: date('Y-m-d H:i:s')) : null;

        $postData = [
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $this->request->getPost('excerpt'),
            'content' => $this->request->getPost('content'),
            'featured_image_url' => $this->request->getPost('featured_image_url'),
            'author_id' => $this->request->getPost('author_id') ?: session()->get('admin_id'),
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        if ($this->blogPostModel->save($postData)) {
            // Handle categories
            $categories = $this->request->getPost('categories');
            $this->updatePostCategories($id, $categories ?? []);

            return redirect()->to('/admin/blog')->with('success', 'Blog post updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update blog post. Please try again.');
        }
    }

    public function delete($id)
    {
        $post = $this->blogPostModel->find($id);
        
        if (!$post) {
            return redirect()->to('/admin/blog')->with('error', 'Blog post not found.');
        }

        // Delete associated categories first
        $db = db_connect();
        $db->table('blog_post_categories')->where('post_id', $id)->delete();

        if ($this->blogPostModel->delete($id)) {
            return redirect()->to('/admin/blog')->with('success', 'Blog post deleted successfully!');
        } else {
            return redirect()->to('/admin/blog')->with('error', 'Failed to delete blog post. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $post = $this->blogPostModel->find($id);
        
        if (!$post) {
            return redirect()->to('/admin/blog')->with('error', 'Blog post not found.');
        }

        $newStatus = $post['is_published'] ? 0 : 1;
        $statusText = $newStatus ? 'published' : 'unpublished';

        $updateData = [
            'is_published' => $newStatus,
            'published_at' => $newStatus ? date('Y-m-d H:i:s') : null
        ];

        if ($this->blogPostModel->update($id, $updateData)) {
            return redirect()->to('/admin/blog')->with('success', "Blog post {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/blog')->with('error', "Failed to {$statusText} blog post.");
        }
    }

    /**
     * Save post categories
     */
    private function savePostCategories($postId, $categories)
    {
        $db = db_connect();
        $data = [];
        
        foreach ($categories as $categoryId) {
            $data[] = [
                'post_id' => $postId,
                'category_id' => $categoryId
            ];
        }
        
        if (!empty($data)) {
            $db->table('blog_post_categories')->insertBatch($data);
        }
    }

    /**
     * Update post categories
     */
    private function updatePostCategories($postId, $categories)
    {
        $db = db_connect();
        
        // Delete existing categories
        $db->table('blog_post_categories')->where('post_id', $postId)->delete();
        
        // Insert new categories
        $this->savePostCategories($postId, $categories);
    }
}