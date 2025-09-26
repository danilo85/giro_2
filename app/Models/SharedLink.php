<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SharedLink extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_id',
        'token',
        'expires_at',
        'download_limit',
        'download_count',
        'password_hash',
        'is_active'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'download_limit' => 'integer',
        'download_count' => 'integer',
        'is_active' => 'boolean'
    ];
    
    protected $hidden = [
        'password_hash'
    ];
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
    
    public static function generateUniqueToken()
    {
        do {
            $token = Str::random(32);
        } while (self::where('token', $token)->exists());
        
        return $token;
    }
    
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function isDownloadLimitReached()
    {
        return $this->download_limit && $this->download_count >= $this->download_limit;
    }
    
    public function canAccess($password = null)
    {
        if (!$this->is_active) {
            return false;
        }
        
        if ($this->isExpired()) {
            return false;
        }
        
        if ($this->isDownloadLimitReached()) {
            return false;
        }
        
        if ($this->password_hash && !password_verify($password, $this->password_hash)) {
            return false;
        }
        
        return true;
    }
    
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }
}
