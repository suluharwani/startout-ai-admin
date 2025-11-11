<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiteSettingsModel;

class Settings extends BaseController
{
    protected $siteSettingsModel;

    public function __construct()
    {
        $this->siteSettingsModel = new SiteSettingsModel();
    }

    public function index()
    {
        $settings = $this->siteSettingsModel->findAll();
        $groupedSettings = [];

        // Group settings by category
        foreach ($settings as $setting) {
            $category = $this->getSettingCategory($setting['setting_key']);
            $groupedSettings[$category][] = $setting;
        }

        $data = [
            'title' => 'Site Settings',
            'groupedSettings' => $groupedSettings
        ];

        return view('admin/settings/index', $data);
    }

    public function update()
    {
        $settings = $this->request->getPost('settings');

        if ($settings && is_array($settings)) {
            $successCount = 0;
            
            foreach ($settings as $key => $value) {
                if ($this->siteSettingsModel->updateSetting($key, $value)) {
                    $successCount++;
                }
            }

            if ($successCount > 0) {
                return redirect()->to('/admin/settings')->with('success', "{$successCount} settings updated successfully!");
            } else {
                return redirect()->to('/admin/settings')->with('error', 'Failed to update settings.');
            }
        }

        return redirect()->to('/admin/settings')->with('error', 'No settings data received.');
    }

    public function updateSingle()
    {
        $key = $this->request->getPost('key');
        $value = $this->request->getPost('value');

        if ($key && $this->siteSettingsModel->updateSetting($key, $value)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Setting updated successfully!']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to update setting.']);
    }

    /**
     * Categorize settings based on their keys
     */
    private function getSettingCategory($key)
    {
        if (strpos($key, 'company_') === 0) {
            return 'company_info';
        } elseif (strpos($key, 'email_') === 0 || strpos($key, 'phone_') === 0) {
            return 'contact_info';
        } elseif (strpos($key, 'social_') === 0) {
            return 'social_media';
        } elseif (strpos($key, 'site_') === 0) {
            return 'site_info';
        } else {
            return 'general';
        }
    }
}