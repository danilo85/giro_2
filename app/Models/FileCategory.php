<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'color',
        'user_id'
    ];
    
    public function files()
    {
        return $this->hasMany(File::class, 'category_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
