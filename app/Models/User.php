<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_admin',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'is_online',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'is_online' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the social accounts for the user.
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the banks for the user.
     */
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    /**
     * Get the credit cards for the user.
     */
    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    /**
     * Get the categories for the user.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Generate default avatar using initials
        $initials = collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');
            
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=3B82F6&background=EBF4FF&size=128';
    }

    /**
     * Get the user's role display name.
     */
    public function getRoleAttribute()
    {
        return $this->is_admin ? 'admin' : 'standard';
    }

    /**
     * Update user's last activity.
     */
    public function updateLastActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'is_online' => true,
        ]);
    }
}
