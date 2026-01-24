<?php
// app/Models/Property.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'property_type',
        'title',
        'description',
        'city',
        'district',
        'area',
        'price',
        'currency',
        'bedrooms',
        'bathrooms',
        'size_sqm',
        'is_furnished',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size_sqm' => 'decimal:2',
        'is_furnished' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function landlord()
    {
        return $this->belongsTo(LandlordProfile::class, 'landlord_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    public function amenities()
    {
        return $this->hasMany(PropertyAmenity::class);
    }

    public function listing()
    {
        return $this->hasOne(Listing::class);
    }

    public function boosts()
    {
        return $this->hasMany(PropertyBoost::class);
    }

    public function activeBoost()
    {
        return $this->hasOne(PropertyBoost::class)
            ->where('is_active', true)
            ->where('end_date', '>=', now());
    }

    public function inquiries()
    {
        return $this->hasManyThrough(Inquiry::class, Listing::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isBoosted(): bool
    {
        return $this->activeBoost()->exists();
    }

    public function incrementViews()
    {
        if ($this->listing) {
            $this->listing->increment('view_count');
        }
    }
}