<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\ClientModel;
use App\Models\ServiceModel;
use App\Models\CooperationTypeModel;

class Subscriptions extends BaseController
{
    protected $subscriptionModel;
    protected $clientModel;
    protected $serviceModel;
    protected $cooperationTypeModel;

    public function __construct()
    {
        $this->subscriptionModel = new SubscriptionModel();
        $this->clientModel = new ClientModel();
        $this->serviceModel = new ServiceModel();
        $this->cooperationTypeModel = new CooperationTypeModel();
    }

    public function index()
    {
        $subscriptions = $this->subscriptionModel->getSubscriptionsWithDetails();

        $data = [
            'title' => 'Manage Subscriptions',
            'subscriptions' => $subscriptions
        ];

        return view('admin/subscriptions/index', $data);
    }

    public function create()
    {
        // Get active clients with proper selection
        $clients = $this->clientModel->getActiveClients();
        
        // If no active clients, redirect to create client first
        if (empty($clients)) {
            return redirect()->to('/admin/clients/create')
                           ->with('error', 'Please create at least one active client before creating a subscription.');
        }

        $services = $this->serviceModel->where('is_active', 1)->findAll();
        $cooperationTypes = $this->cooperationTypeModel->getActiveTypes();

        $data = [
            'title' => 'Create New Subscription',
            'clients' => $clients,
            'services' => $services,
            'cooperationTypes' => $cooperationTypes
        ];

        return view('admin/subscriptions/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->subscriptionModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $subscriptionData = [
            'client_id' => $this->request->getPost('client_id'),
            'service_id' => $this->request->getPost('service_id'),
            'cooperation_type_id' => $this->request->getPost('cooperation_type_id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'billing_cycle' => $this->request->getPost('billing_cycle'),
            'amount' => $this->request->getPost('amount'),
            'currency' => $this->request->getPost('currency'),
            'status' => $this->request->getPost('status'),
            'auto_renew' => $this->request->getPost('auto_renew') ? 1 : 0,
            'notes' => $this->request->getPost('notes'),
            'created_by' => session()->get('admin_id')
        ];

        if ($this->subscriptionModel->save($subscriptionData)) {
            return redirect()->to('/admin/subscriptions')->with('success', 'Subscription created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create subscription. Please try again.');
        }
    }
    public function edit($id)
    {
        $subscription = $this->subscriptionModel->find($id);
        
        if (!$subscription) {
            return redirect()->to('/admin/subscriptions')->with('error', 'Subscription not found.');
        }

        $clients = $this->clientModel->where('status', 'active')->findAll();
        $services = $this->serviceModel->where('is_active', 1)->findAll();
        $cooperationTypes = $this->cooperationTypeModel->getActiveTypes();

        $data = [
            'title' => 'Edit Subscription',
            'subscription' => $subscription,
            'clients' => $clients,
            'services' => $services,
            'cooperationTypes' => $cooperationTypes
        ];

        return view('admin/subscriptions/form', $data);
    }

    public function update($id)
    {
        $subscription = $this->subscriptionModel->find($id);
        
        if (!$subscription) {
            return redirect()->to('/admin/subscriptions')->with('error', 'Subscription not found.');
        }

        if (!$this->validate($this->subscriptionModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $subscriptionData = [
            'id' => $id,
            'client_id' => $this->request->getPost('client_id'),
            'service_id' => $this->request->getPost('service_id'),
            'cooperation_type_id' => $this->request->getPost('cooperation_type_id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'billing_cycle' => $this->request->getPost('billing_cycle'),
            'amount' => $this->request->getPost('amount'),
            'currency' => $this->request->getPost('currency'),
            'status' => $this->request->getPost('status'),
            'auto_renew' => $this->request->getPost('auto_renew') ? 1 : 0,
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->subscriptionModel->save($subscriptionData)) {
            return redirect()->to('/admin/subscriptions')->with('success', 'Subscription updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update subscription. Please try again.');
        }
    }

    public function delete($id)
    {
        $subscription = $this->subscriptionModel->find($id);
        
        if (!$subscription) {
            return redirect()->to('/admin/subscriptions')->with('error', 'Subscription not found.');
        }

        // Check if subscription has invoices
        $db = db_connect();
        $invoiceCount = $db->table('invoices')
                          ->where('subscription_id', $id)
                          ->countAllResults();
        
        if ($invoiceCount > 0) {
            return redirect()->to('/admin/subscriptions')->with('error', 'Cannot delete subscription that has invoices. Please delete the invoices first.');
        }

        if ($this->subscriptionModel->delete($id)) {
            return redirect()->to('/admin/subscriptions')->with('success', 'Subscription deleted successfully!');
        } else {
            return redirect()->to('/admin/subscriptions')->with('error', 'Failed to delete subscription. Please try again.');
        }
    }

    public function toggleStatus($id)
    {
        $subscription = $this->subscriptionModel->find($id);
        
        if (!$subscription) {
            return redirect()->to('/admin/subscriptions')->with('error', 'Subscription not found.');
        }

        $newStatus = $subscription['status'] === 'active' ? 'suspended' : 'active';
        $statusText = $newStatus === 'active' ? 'activated' : 'suspended';

        if ($this->subscriptionModel->update($id, ['status' => $newStatus])) {
            return redirect()->to('/admin/subscriptions')->with('success', "Subscription {$statusText} successfully!");
        } else {
            return redirect()->to('/admin/subscriptions')->with('error', "Failed to {$statusText} subscription.");
        }
    }
}