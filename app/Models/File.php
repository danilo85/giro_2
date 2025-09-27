<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'category_id',
        'original_name',
        'stored_name',
        'mime_type',
        'file_size',
        'is_temporary',
        'expires_at',
        'description'
    ];
    
    protected $casts = [
        'is_temporary' => 'boolean',
        'file_size' => 'integer',
        'expires_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'category_id');
    }
    
    public function sharedLinks()
    {
        return $this->hasMany(SharedLink::class);
    }
    
    public function activityLogs()
    {
        return $this->hasMany(FileActivityLog::class);
    }
    
    public function tempFileExtensions()
    {
        return $this->hasMany(TempFileExtension::class);
    }
    
    public function tempFileNotifications()
    {
        return $this->hasMany(TempFileNotification::class);
    }
    
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    public function getFilePathAttribute()
    {
        return 'files/' . $this->stored_name;
    }
    
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
    
    /**
     * Check if the file is expired
     */
    public function isExpired()
    {
        return $this->is_temporary && $this->expires_at && $this->expires_at->isPast();
    }
    
    /**
     * Check if the file is expiring soon (within 24 hours)
     */
    public function isExpiringSoon()
    {
        return $this->is_temporary && $this->expires_at && $this->expires_at->diffInHours(now()) <= 24;
    }
    
    /**
     * Get the time remaining until expiration
     */
    public function getTimeUntilExpirationAttribute()
    {
        if (!$this->is_temporary || !$this->expires_at) {
            return null;
        }
        
        return $this->expires_at->diffForHumans();
    }
    
    /**
     * Scope to get only temporary files
     */
    public function scopeTemporary($query)
    {
        return $query->where('is_temporary', true);
    }
    
    /**
     * Scope to get only expired files
     */
    public function scopeExpired($query)
    {
        return $query->where('is_temporary', true)
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '<', now());
    }
    
    /**
     * Scope to get files expiring soon
     */
    public function scopeExpiringSoon($query, $hours = 24)
    {
        return $query->where('is_temporary', true)
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '>', now())
                    ->where('expires_at', '<=', now()->addHours($hours));
    }
}
