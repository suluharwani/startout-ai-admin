<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientTypeModel extends Model
{
    protected $table = 'client_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $returnType = 'array';

    public $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'description' => 'permit_empty|max_length[500]'
    ];

    public function getActiveTypes()
    {
        return $this->where('is_active', 1)->findAll();
    }
}