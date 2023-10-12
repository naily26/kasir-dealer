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
        'role',
        'status'
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
    ];

    /**
     * Get the pegawai that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(pegawai::class, 'id', 'user_id');
    }

    public function isAdmin()
    {
        if($this->role == 'admin')
        { 
            return true; 
        } 
        else 
        { 
            return false; 
        }
    }

    public function isUser()
    {
        if($this->role == 'user')
        { 
            return true; 
        } 
        else 
        { 
            return false; 
        }
    }

    public function hasRole($role) {
        switch ($role) {
            case 'admin': return \Auth::user()->isAdmin();
            case 'user': return \Auth::user()->isUser();
        }
        return false;
    }
}
