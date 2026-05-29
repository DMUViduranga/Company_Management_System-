<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_token',
        'browser_name',
        'ip_address',
        'is_approved',
    ];

    
    protected $casts = [
        'is_approved' => 'boolean',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
