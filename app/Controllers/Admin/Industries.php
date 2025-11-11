<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IndustryModel;

class Industries extends BaseController
{
    protected $industryModel;

    public function __construct()
    {
        $this->industryModel = new IndustryModel();
    }

    public function index()
    {
        $industries = $this->industryModel->orderBy('sort_order', 'ASC')->findAll();

        $data = [
            'title' => 'Manage Industries',
            'industries' => $industries
        ];

        return view('admin/industries/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create New Industry'
        ];

        return view('admin/industries/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->industryModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->industryModel->generateSlug($name);
        }

        if (!$this->industryModel->isSlugUnique($slug)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $industryData = [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'icon_class' => $this->request->getPost('icon_class'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0
        ];

        if ($this->industryModel->save($industryData)) {
            return redirect()->to('/admin/industries')->with('success', 'Industry created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create industry. Please try again.');
        }
    }
}