<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'shared_link_id',
        'ip_address',
        'user_agent',
        'action',
        'accessed_at'
    ];
    
    protected $casts = [
        'accessed_at' => 'datetime'
    ];
    
    public $timestamps = false;
    
    public function sharedLink()
    {
        return $this->belongsTo(SharedLink::class);
    }
}
