<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageBackgroundColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'color_name',
        'color_hex',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    /**
     * Get the user that owns the color
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the social post that this color belongs to
     */
    public function socialPost(): BelongsTo
    {
        return $this->belongsTo(SocialPost::class, 'post_id');
    }

    /**
     * Scope to get default color for a user
     */
    public function scopeDefaultForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->where('is_default', true);
    }

    /**
     * Scope to get colors for a user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->orderBy('is_default', 'desc')->orderBy('created_at', 'desc');
    }
}
