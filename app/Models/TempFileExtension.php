<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFileExtension extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_id',
        'old_expires_at',
        'new_expires_at',
        'reason',
        'extended_by'
    ];
    
    protected $casts = [
        'old_expires_at' => 'datetime',
        'new_expires_at' => 'datetime'
    ];
    
    public $timestamps = false;
    
    protected $dates = ['created_at'];
    
    /**
     * Get the file that was extended
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
    /**
     * Get the user who extended the file
     */
    public function extendedBy()
    {
        return $this->belongsTo(User::class, 'extended_by');
    }
    
    /**
     * Get the extension duration in hours
     */
    public function getExtensionDurationAttribute()
    {
        return $this->new_expires_at->diffInHours($this->old_expires_at);
    }
}
