<?php
// app/Models/LandlordProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandlordProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'national_id',
        'business_name',
        'address',
        'city',
        'avg_rating',
        'total_ratings',
        'verification_status',
        'verified_at',
    ];

    protected $casts = [
        'avg_rating' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'landlord_id');
    }

    // Helper methods
    public function isVerified(): bool
    {
        return $this->verification_status === 'approved';
    }

    public function updateRating()
    {
        $this->avg_rating = $this->ratings()->avg('overall_rating');
        $this->total_ratings = $this->ratings()->count();
        $this->save();
    }
}