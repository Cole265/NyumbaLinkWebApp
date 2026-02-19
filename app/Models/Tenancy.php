<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'landlord_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord()
    {
        return $this->belongsTo(LandlordProfile::class, 'landlord_id');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function markAsVacated()
    {
        $this->update([
            'status' => 'vacated',
            'end_date' => now(),
        ]);
    }
}
