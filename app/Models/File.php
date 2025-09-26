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
        'description'
    ];
    
    protected $casts = [
        'is_temporary' => 'boolean',
        'file_size' => 'integer'
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
}
