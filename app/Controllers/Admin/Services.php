<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceModel;

class Services extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $services = $this->serviceModel->select('services.*, parent.name as parent_name')
                                      ->join('services as parent', 'services.parent_service_id = parent.id', 'left')
                                      ->orderBy('services.sort_order', 'ASC')
                                      ->orderBy('services.name', 'ASC')
                                      ->findAll();

        $data = [
            'title' => 'Manage Services',
            'services' => $services
        ];

        return view('admin/services/index', $data);
    }

    public function create()
    {
        $parentServices = $this->serviceModel->getParentServices();

        $data = [
            'title' => 'Create New Service',
            'parentServices' => $parentServices
        ];

        return view('admin/services/form', $data);
    }

    public function store()
    {
        // Basic validation tanpa slug uniqueness check di rules
        if (!$this->validate($this->serviceModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->serviceModel->generateSlug($name);
        }

        // Manual slug uniqueness check
        if (!$this->serviceModel->isSlugUnique($slug)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $serviceData = [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'detailed_description' => $this->request->getPost('detailed_description'),
            'icon_class' => $this->request->getPost('icon_class'),
            'image_url' => $this->request->getPost('image_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'parent_service_id' => $this->request->getPost('parent_service_id') ?: null,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        if ($this->serviceModel->save($serviceData)) {
            return redirect()->to('/admin/services')->with('success', 'Service created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create service. Please try again.');
        }
    }

    public function edit($id)
    {
        $service = $this->serviceModel->getServiceWithParent($id);
        
        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Service not found.');
        }

        $parentServices = $this->serviceModel->where('id !=', $id)
                                           ->where('parent_service_id', null)
                                           ->orderBy('name', 'ASC')
                                           ->findAll();

        $data = [
            'title' => 'Edit Service',
            'service' => $service,
            'parentServices' => $parentServices
        ];

        return view('admin/services/form', $data);
    }

    public function update($id)
    {
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Service not found.');
        }

        // Basic validation
        if (!$this->validate($this->serviceModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle slug manually
        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        
        if (empty($slug)) {
            $slug = $this->serviceModel->generateSlug($name);
        }

        // Manual slug uniqueness check (excluding current ID)
        if (!$this->serviceModel->isSlugUnique($slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'The slug is already taken. Please choose a different one.');
        }

        $serviceData = [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'detailed_description' => $this->request->getPost('detailed_description'),
            'icon_class' => $this->request->getPost('icon_class'),
            'image_url' => $this->request->getPost('image_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'parent_service_id' => $this->request->getPost('parent_service_id') ?: null,
            'meta_title' => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description')
        ];

        if ($this->serviceModel->save($serviceData)) {
            return redirect()->to('/admin/services')->with('success', 'Service updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update service. Please try again.');
        }
    }

    public function delete($id)
    {
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Service not found.');
        }

        // Check if this service has child services
        $childServices = $this->serviceModel->where('parent_service_id', $id)->countAllResults();
        if ($childServices > 0) {
            return redirect()->to('/admin/services')->with('error', 'Cannot delete service that has child services. Please delete or reassign child services first.');
        }

        if ($this->serviceModel->delete($id)) {
            return redirect()->to('/admin/services')->with('success', 'Service deleted successfully!');
        } else {
            return redirect()->to('/admin/services')->with('error', 'Failed to delete service. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $service = $this->serviceModel->find($id);
        
        if (!$service) {
            return redirect()->to('/admin/services')->with('error', 'Service not found.');
        }

        $newStatus = $service['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'activated' : 'deactivated';

        if ($this->serviceModel->update($id, ['is_active' => $newStatus])) {
            return redirect()->to('/admin/services')->with('success', "Service {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/services')->with('error', "Failed to {$statusText} service.");
        }
    }

    public function updateSortOrder()
    {
        $sortOrders = $this->request->getPost('sort_order');
        
        if ($sortOrders && is_array($sortOrders)) {
            foreach ($sortOrders as $id => $order) {
                $this->serviceModel->update($id, ['sort_order' => (int)$order]);
            }
            
            return $this->response->setJSON(['success' => true, 'message' => 'Sort order updated successfully!']);
        }
        
        return $this->response->setJSON(['success' => false, 'message' => 'No data received.']);
    }
}