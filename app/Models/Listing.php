<?php
// app/Models/Listing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'start_date',
        'expiry_date',
        'is_active',
        'view_count',
        'inquiry_count',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    // Helper methods
    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function expire()
    {
        $this->update(['is_active' => false]);
        $this->property->update(['status' => 'expired']);
    }
}