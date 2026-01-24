<?php
// app/Models/Rating.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'tenant_id',
        'property_id',
        'communication_rating',
        'accuracy_rating',
        'cleanliness_rating',
        'professionalism_rating',
        'fairness_rating',
        'overall_rating',
        'review',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:2',
    ];

    // Relationships
    public function landlord()
    {
        return $this->belongsTo(LandlordProfile::class, 'landlord_id');
    }

    public function tenant()
    {
        return $this->belongsTo(TenantProfile::class, 'tenant_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Auto-calculate overall rating
    protected static function booted()
    {
        static::saving(function ($rating) {
            $rating->overall_rating = (
                $rating->communication_rating +
                $rating->accuracy_rating +
                $rating->cleanliness_rating +
                $rating->professionalism_rating +
                $rating->fairness_rating
            ) / 5;
        });

        static::saved(function ($rating) {
            $rating->landlord->updateRating();
        });
    }
}