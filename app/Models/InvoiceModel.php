<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'invoice_number', 'subscription_id', 'client_id', 'issue_date', 'due_date',
        'amount', 'tax_amount', 'total_amount', 'currency', 'status',
        'payment_method', 'paid_date', 'notes', 'created_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    public $validationRules = [
        'invoice_number' => 'required|min_length[3]|max_length[50]',
        'client_id' => 'required|integer',
        'issue_date' => 'required|valid_date',
        'due_date' => 'required|valid_date',
        'amount' => 'required|decimal'
    ];

    public function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ym') . '-';
        $lastInvoice = $this->like('invoice_number', $prefix)
                           ->orderBy('id', 'DESC')
                           ->first();

        if ($lastInvoice) {
            $lastNumber = intval(str_replace($prefix, '', $lastInvoice['invoice_number']));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getInvoicesWithDetails()
    {
        return $this->select('invoices.*, c.company_name, c.contact_person, c.email, c.phone, 
                             s.name as service_name, ct.name as cooperation_type, 
                             u.first_name, u.last_name')
                    ->join('clients c', 'invoices.client_id = c.id')
                    ->join('subscriptions sub', 'invoices.subscription_id = sub.id', 'left')
                    ->join('services s', 'sub.service_id = s.id', 'left')
                    ->join('cooperation_types ct', 'sub.cooperation_type_id = ct.id', 'left')
                    ->join('users u', 'invoices.created_by = u.id', 'left')
                    ->orderBy('invoices.created_at', 'DESC')
                    ->findAll();
    }

    public function getInvoiceWithDetails($id)
    {
        return $this->select('invoices.*, c.company_name, c.contact_person, c.email, c.phone, c.address, c.city, c.country,
                             s.name as service_name, ct.name as cooperation_type, sub.amount as subscription_amount,
                             u.first_name, u.last_name')
                    ->join('clients c', 'invoices.client_id = c.id')
                    ->join('subscriptions sub', 'invoices.subscription_id = sub.id', 'left')
                    ->join('services s', 'sub.service_id = s.id', 'left')
                    ->join('cooperation_types ct', 'sub.cooperation_type_id = ct.id', 'left')
                    ->join('users u', 'invoices.created_by = u.id', 'left')
                    ->where('invoices.id', $id)
                    ->first();
    }

    public function getInvoiceStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'draft' => $this->where('status', 'draft')->countAllResults(),
            'sent' => $this->where('status', 'sent')->countAllResults(),
            'paid' => $this->where('status', 'paid')->countAllResults(),
            'overdue' => $this->where('status', 'overdue')->countAllResults(),
            'total_amount' => $this->selectSum('total_amount')->get()->getRow()->total_amount ?? 0
        ];

        return $stats;
    }
}