<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'key';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['key', 'value'];

    /**
     * Get settings as associative array
     */
    public function getSettings(): array
    {
        $data = $this->findAll();
        $settings = [];
        foreach ($data as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }

    /**
     * Update multiple settings
     */
    public function updateSettings(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            $this->save([
                'key' => $key,
                'value' => (string)$value
            ]);
        }
        return true;
    }
}
