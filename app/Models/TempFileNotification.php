<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempFileNotification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_id',
        'notification_type',
        'sent_at',
        'is_read'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean'
    ];
    
    public $timestamps = false;
    
    protected $dates = ['created_at'];
    
    const TYPE_24H_WARNING = '24h_warning';
    const TYPE_1H_WARNING = '1h_warning';
    const TYPE_EXPIRED = 'expired';
    
    /**
     * Get the file associated with this notification
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
    
    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    /**
     * Scope to get notifications by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('notification_type', $type);
    }
    
    /**
     * Get notification message based on type
     */
    public function getMessageAttribute()
    {
        switch ($this->notification_type) {
            case self::TYPE_24H_WARNING:
                return 'Your file "' . $this->file->original_name . '" will expire in 24 hours.';
            case self::TYPE_1H_WARNING:
                return 'Your file "' . $this->file->original_name . '" will expire in 1 hour.';
            case self::TYPE_EXPIRED:
                return 'Your file "' . $this->file->original_name . '" has expired and will be deleted.';
            default:
                return 'File notification';
        }
    }
}
