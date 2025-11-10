<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    // ... existing service methods ...

    /**
     * The Validation factory.
     * Hapus method ini atau kembalikan ke default
     * CodeIgniter sudah menyediakan validation service default
     */
    
    // HAPUS method validation() yang menyebabkan infinite loop
    // atau gunakan ini jika ingin custom:
    
    public static function validation($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('validation');
        }

        $config = config('Validation');
        
        // Gunakan cara yang benar tanpa recursive call
        return new \CodeIgniter\Validation\Validation($config, service('renderer'));
    }
}