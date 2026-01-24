<?php
// app/Models/PropertyAmenity.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'amenity',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}