<?php
// app/Models/TenantProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'occupation',
        'preferences',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'tenant_id');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'tenant_id');
    }
}