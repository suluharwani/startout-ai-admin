<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingsModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value', 'setting_type', 'description'];
    protected $useTimestamps = false;

    public function updateSetting($key, $value)
    {
        // Get the setting type first
        $setting = $this->where('setting_key', $key)->first();
        
        if (!$setting) {
            return false;
        }

        // Convert value based on type
        switch ($setting['setting_type']) {
            case 'boolean':
                $value = $value ? '1' : '0';
                break;
            case 'integer':
                $value = (string) intval($value);
                break;
            case 'json':
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                break;
            default:
                $value = (string) $value;
        }

        return $this->where('setting_key', $key)->set('setting_value', $value)->update();
    }

    public function getSetting($key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Convert value based on type
        switch ($setting['setting_type']) {
            case 'boolean':
                return (bool) $setting['setting_value'];
            case 'integer':
                return (int) $setting['setting_value'];
            case 'json':
                return json_decode($setting['setting_value'], true) ?? $default;
            default:
                return $setting['setting_value'];
        }
    }

    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->getSetting($setting['setting_key']);
        }

        return $result;
    }
}