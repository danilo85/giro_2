<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFileSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'default_expiry_days',
        'max_expiry_days',
        'auto_cleanup_enabled',
        'cleanup_time'
    ];
    
    protected $casts = [
        'default_expiry_days' => 'integer',
        'max_expiry_days' => 'integer',
        'auto_cleanup_enabled' => 'boolean',
        'cleanup_time' => 'datetime'
    ];
    
    /**
     * Get the singleton settings instance
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'default_expiry_days' => 7,
            'max_expiry_days' => 30,
            'auto_cleanup_enabled' => true,
            'cleanup_time' => now()->setTime(2, 0, 0) // 2:00 AM
        ]);
    }
    
    /**
     * Update settings
     */
    public static function updateSettings(array $data)
    {
        $settings = static::getSettings();
        $settings->update($data);
        return $settings;
    }
    
    /**
     * Get default expiry date for new temporary files
     */
    public function getDefaultExpiryDate()
    {
        return now()->addDays($this->default_expiry_days);
    }
    
    /**
     * Get maximum allowed expiry date
     */
    public function getMaxExpiryDate()
    {
        return now()->addDays($this->max_expiry_days);
    }
    
    /**
     * Check if a given expiry date is valid
     */
    public function isValidExpiryDate($date, $baseDate = null)
    {
        $baseDate = $baseDate ?? now();
        $maxDate = $baseDate->copy()->addDays($this->max_expiry_days);
        return $date <= $maxDate && $date > now();
    }
}
