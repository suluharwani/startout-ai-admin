<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\ClientTypeModel;
use App\Models\IndustryModel;

class Clients extends BaseController
{
    protected $clientModel;
    protected $clientTypeModel;
    protected $industryModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->clientTypeModel = new ClientTypeModel();
        $this->industryModel = new IndustryModel();
    }

    public function index()
    {
        $clients = $this->clientModel->getClientsWithDetails();
        $stats = $this->clientModel->getClientStats();

        $data = [
            'title' => 'Manage Clients',
            'clients' => $clients,
            'stats' => $stats
        ];

        return view('admin/clients/index', $data);
    }

    public function create()
    {
        $clientTypes = $this->clientTypeModel->getActiveTypes();
        $industries = $this->industryModel->getActiveIndustries();

        $data = [
            'title' => 'Create New Client',
            'clientTypes' => $clientTypes,
            'industries' => $industries
        ];

        return view('admin/clients/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->clientModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $clientData = [
            'company_name' => $this->request->getPost('company_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'country' => $this->request->getPost('country'),
            'website' => $this->request->getPost('website'),
            'client_type_id' => $this->request->getPost('client_type_id'),
            'industry_id' => $this->request->getPost('industry_id'),
            'status' => $this->request->getPost('status'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => session()->get('admin_id')
        ];

        if ($this->clientModel->save($clientData)) {
            return redirect()->to('/admin/clients')->with('success', 'Client created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create client. Please try again.');
        }
    }

    public function edit($id)
    {
        $client = $this->clientModel->getClientWithDetails($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')->with('error', 'Client not found.');
        }

        $clientTypes = $this->clientTypeModel->getActiveTypes();
        $industries = $this->industryModel->getActiveIndustries();

        $data = [
            'title' => 'Edit Client',
            'client' => $client,
            'clientTypes' => $clientTypes,
            'industries' => $industries
        ];

        return view('admin/clients/form', $data);
    }

    public function update($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')->with('error', 'Client not found.');
        }

        if (!$this->validate($this->clientModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $clientData = [
            'id' => $id,
            'company_name' => $this->request->getPost('company_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'country' => $this->request->getPost('country'),
            'website' => $this->request->getPost('website'),
            'client_type_id' => $this->request->getPost('client_type_id'),
            'industry_id' => $this->request->getPost('industry_id'),
            'status' => $this->request->getPost('status'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->clientModel->save($clientData)) {
            return redirect()->to('/admin/clients')->with('success', 'Client updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update client. Please try again.');
        }
    }

    public function delete($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')->with('error', 'Client not found.');
        }

        // Check if client has subscriptions
        $db = db_connect();
        $subscriptionCount = $db->table('subscriptions')
                               ->where('client_id', $id)
                               ->countAllResults();
        
        if ($subscriptionCount > 0) {
            return redirect()->to('/admin/clients')->with('error', 'Cannot delete client that has subscriptions. Please delete the subscriptions first.');
        }

        if ($this->clientModel->delete($id)) {
            return redirect()->to('/admin/clients')->with('success', 'Client deleted successfully!');
        } else {
            return redirect()->to('/admin/clients')->with('error', 'Failed to delete client. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')->with('error', 'Client not found.');
        }

        $newStatus = $client['status'] === 'active' ? 'inactive' : 'active';
        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        if ($this->clientModel->update($id, ['status' => $newStatus])) {
            return redirect()->to('/admin/clients')->with('success', "Client {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/clients')->with('error', "Failed to {$statusText} client.");
        }
    }
}