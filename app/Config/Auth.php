<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    public $defaultRole = 'staff';
    public $adminRole = 'admin';
    public $managerRole = 'manager';
    
    public $hashAlgorithm = PASSWORD_DEFAULT;
    public $hashCost = 10;
}