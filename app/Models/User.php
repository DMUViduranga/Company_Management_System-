<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isWithinAllowedTime(): bool
    {
    
        if ($this->isAdmin()) {
            return true;
        }

     
        if (!$this->allowed_from || !$this->allowed_to) {
            return true;
        }

        $now = Carbon::now('Asia/Colombo');
        

        $allowedFrom = Carbon::createFromTimeString($this->allowed_from, 'Asia/Colombo');
        $allowedTo = Carbon::createFromTimeString($this->allowed_to, 'Asia/Colombo');

 
        return $now->between($allowedFrom, $allowedTo);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'allowed_from',
        'allowed_to',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
