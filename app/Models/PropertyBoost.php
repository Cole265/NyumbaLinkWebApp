<?php
// app/Models/PropertyBoost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBoost extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'boost_type',
        'start_date',
        'end_date',
        'amount_paid',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active && $this->end_date >= now();
    }
}