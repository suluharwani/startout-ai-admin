<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'client_id', 'service_id', 'cooperation_type_id', 'start_date', 'end_date',
        'billing_cycle', 'amount', 'currency', 'status', 'auto_renew', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    public $validationRules = [
        'client_id' => 'required|integer',
        'service_id' => 'required|integer',
        'cooperation_type_id' => 'required|integer',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'amount' => 'required|decimal'
    ];

    public function getSubscriptionsWithDetails()
    {
        return $this->select('subscriptions.*, c.company_name, s.name as service_name, ct.name as cooperation_type, u.first_name, u.last_name')
                    ->join('clients c', 'subscriptions.client_id = c.id')
                    ->join('services s', 'subscriptions.service_id = s.id')
                    ->join('cooperation_types ct', 'subscriptions.cooperation_type_id = ct.id')
                    ->join('users u', 'subscriptions.created_by = u.id', 'left')
                    ->orderBy('subscriptions.created_at', 'DESC')
                    ->findAll();
    }

    public function getActiveSubscriptions()
    {
        return $this->where('status', 'active')
                    ->where('end_date >=', date('Y-m-d'))
                    ->findAll();
    }

    public function getSubscriptionsForDropdown()
    {
        $subscriptions = $this->select('subscriptions.id, c.company_name, subscriptions.amount, subscriptions.currency')
                             ->join('clients c', 'subscriptions.client_id = c.id')
                             ->where('subscriptions.status', 'active')
                             ->orderBy('c.company_name', 'ASC')
                             ->findAll();
        
        return $subscriptions;
    }
}