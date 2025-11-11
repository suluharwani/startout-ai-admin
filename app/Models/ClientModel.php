<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'company_name', 'contact_person', 'email', 'phone', 'address',
        'city', 'country', 'website', 'client_type_id', 'industry_id',
        'status', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    public $validationRules = [
        'company_name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email',
        'contact_person' => 'permit_empty|max_length[255]',
        'phone' => 'permit_empty|max_length[20]',
        'website' => 'permit_empty|valid_url'
    ];

    public function getClientsWithDetails()
    {
        return $this->select('clients.*, ct.name as client_type, i.name as industry, u.first_name, u.last_name')
                    ->join('client_types ct', 'clients.client_type_id = ct.id', 'left')
                    ->join('industries i', 'clients.industry_id = i.id', 'left')
                    ->join('users u', 'clients.created_by = u.id', 'left')
                    ->orderBy('clients.created_at', 'DESC')
                    ->findAll();
    }

    public function getClientWithDetails($id)
    {
        return $this->select('clients.*, ct.name as client_type, i.name as industry, u.first_name, u.last_name')
                    ->join('client_types ct', 'clients.client_type_id = ct.id', 'left')
                    ->join('industries i', 'clients.industry_id = i.id', 'left')
                    ->join('users u', 'clients.created_by = u.id', 'left')
                    ->where('clients.id', $id)
                    ->first();
    }

    public function getClientStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'active' => $this->where('status', 'active')->countAllResults(),
            'prospect' => $this->where('status', 'prospect')->countAllResults(),
            'inactive' => $this->where('status', 'inactive')->countAllResults()
        ];

        return $stats;
    }

    public function getActiveClients()
    {
        return $this->select('clients.id, clients.company_name, clients.contact_person, clients.email')
                    ->where('status', 'active')
                    ->orderBy('company_name', 'ASC')
                    ->findAll();
    }

    public function getClientsForDropdown()
    {
        $clients = $this->where('status', 'active')
                       ->orderBy('company_name', 'ASC')
                       ->findAll();
        
        $dropdown = [];
        foreach ($clients as $client) {
            $dropdown[$client['id']] = $client['company_name'] . ' - ' . $client['contact_person'];
        }
        
        return $dropdown;
    }
}