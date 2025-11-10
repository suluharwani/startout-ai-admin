<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogCategoryModel;

class BlogCategories extends BaseController
{
    protected $blogCategoryModel;

    public function __construct()
    {
        $this->blogCategoryModel = new BlogCategoryModel();
    }

    public function index()
    {
        $categories = $this->blogCategoryModel->getCategoriesWithPostCount();

        $data = [
            'title' => 'Manage Blog Categories',
            'categories' => $categories
        ];

        return view('admin/blog/categories/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create New Category'
        ];

        return view('admin/blog/categories/form', $data);
    }

    public function store()
    {
        // Basic validation
        if (!$this->validate($this->blogCategoryModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->blogCategoryModel->generateSlug($name);
        }

        // Manual slug uniqueness check
        if (!$this->blogCategoryModel->isSlugUnique($slug)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $categoryData = [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description')
        ];

        if ($this->blogCategoryModel->save($categoryData)) {
            return redirect()->to('/admin/blog/categories')->with('success', 'Category created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit($id)
    {
        $category = $this->blogCategoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/blog/categories')->with('error', 'Category not found.');
        }

        $data = [
            'title' => 'Edit Category',
            'category' => $category
        ];

        return view('admin/blog/categories/form', $data);
    }

    public function update($id)
    {
        $category = $this->blogCategoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/blog/categories')->with('error', 'Category not found.');
        }

        // Basic validation
        if (!$this->validate($this->blogCategoryModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->blogCategoryModel->generateSlug($name);
        }

        // Manual slug uniqueness check (excluding current ID)
        if (!$this->blogCategoryModel->isSlugUnique($slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $categoryData = [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description')
        ];

        if ($this->blogCategoryModel->save($categoryData)) {
            return redirect()->to('/admin/blog/categories')->with('success', 'Category updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function delete($id)
    {
        $category = $this->blogCategoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/admin/blog/categories')->with('error', 'Category not found.');
        }

        // Check if this category has posts
        $db = db_connect();
        $postCount = $db->table('blog_post_categories')
                       ->where('category_id', $id)
                       ->countAllResults();
        
        if ($postCount > 0) {
            return redirect()->to('/admin/blog/categories')->with('error', 'Cannot delete category that has blog posts. Please reassign or delete the posts first.');
        }

        if ($this->blogCategoryModel->delete($id)) {
            return redirect()->to('/admin/blog/categories')->with('success', 'Category deleted successfully!');
        } else {
            return redirect()->to('/admin/blog/categories')->with('error', 'Failed to delete category. Please try again.');
        }
    }
}