<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileActivityLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'file_id',
        'action',
        'description',
        'created_at'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'created_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
    public static function log($userId, $fileId, $action, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'file_id' => $fileId,
            'action' => $action,
            'description' => $description,
            'created_at' => now()
        ]);
    }
}
