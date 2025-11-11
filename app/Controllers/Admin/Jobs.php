<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JobPositionModel;

class Jobs extends BaseController
{
    protected $jobPositionModel;

    public function __construct()
    {
        $this->jobPositionModel = new JobPositionModel();
    }

    public function index()
    {
        $jobs = $this->jobPositionModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Manage Job Positions',
            'jobs' => $jobs
        ];

        return view('admin/jobs/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create New Job Position'
        ];

        return view('admin/jobs/form', $data);
    }

    public function store()
    {
        // Basic validation
        if (!$this->validate($this->jobPositionModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->jobPositionModel->generateSlug($title);
        }

        // Manual slug uniqueness check
        if (!$this->jobPositionModel->isSlugUnique($slug)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $jobData = [
            'title' => $title,
            'slug' => $slug,
            'department' => $this->request->getPost('department'),
            'location' => $this->request->getPost('location'),
            'employment_type' => $this->request->getPost('employment_type'),
            'description' => $this->request->getPost('description'),
            'requirements' => $this->request->getPost('requirements'),
            'responsibilities' => $this->request->getPost('responsibilities'),
            'is_remote' => $this->request->getPost('is_remote') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->jobPositionModel->save($jobData)) {
            return redirect()->to('/admin/jobs')->with('success', 'Job position created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create job position. Please try again.');
        }
    }

    public function edit($id)
    {
        $job = $this->jobPositionModel->find($id);
        
        if (!$job) {
            return redirect()->to('/admin/jobs')->with('error', 'Job position not found.');
        }

        $data = [
            'title' => 'Edit Job Position',
            'job' => $job
        ];

        return view('admin/jobs/form', $data);
    }

    public function update($id)
    {
        $job = $this->jobPositionModel->find($id);
        
        if (!$job) {
            return redirect()->to('/admin/jobs')->with('error', 'Job position not found.');
        }

        // Basic validation
        if (!$this->validate($this->jobPositionModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $title = $this->request->getPost('title');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->jobPositionModel->generateSlug($title);
        }

        // Manual slug uniqueness check (excluding current ID)
        if (!$this->jobPositionModel->isSlugUnique($slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $jobData = [
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'department' => $this->request->getPost('department'),
            'location' => $this->request->getPost('location'),
            'employment_type' => $this->request->getPost('employment_type'),
            'description' => $this->request->getPost('description'),
            'requirements' => $this->request->getPost('requirements'),
            'responsibilities' => $this->request->getPost('responsibilities'),
            'is_remote' => $this->request->getPost('is_remote') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        if ($this->jobPositionModel->save($jobData)) {
            return redirect()->to('/admin/jobs')->with('success', 'Job position updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update job position. Please try again.');
        }
    }

    public function delete($id)
    {
        $job = $this->jobPositionModel->find($id);
        
        if (!$job) {
            return redirect()->to('/admin/jobs')->with('error', 'Job position not found.');
        }

        // Check if there are applications for this job
        $db = db_connect();
        $applicationCount = $db->table('job_applications')
                              ->where('job_position_id', $id)
                              ->countAllResults();
        
        if ($applicationCount > 0) {
            return redirect()->to('/admin/jobs')->with('error', 'Cannot delete job position that has applications. Please delete the applications first.');
        }

        if ($this->jobPositionModel->delete($id)) {
            return redirect()->to('/admin/jobs')->with('success', 'Job position deleted successfully!');
        } else {
            return redirect()->to('/admin/jobs')->with('error', 'Failed to delete job position. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $job = $this->jobPositionModel->find($id);
        
        if (!$job) {
            return redirect()->to('/admin/jobs')->with('error', 'Job position not found.');
        }

        $newStatus = $job['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'activated' : 'deactivated';

        if ($this->jobPositionModel->update($id, ['is_active' => $newStatus])) {
            return redirect()->to('/admin/jobs')->with('success', "Job position {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/jobs')->with('error', "Failed to {$statusText} job position.");
        }
    }
}