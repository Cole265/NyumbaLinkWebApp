<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'transaction_type',
        'amount',
        'payment_method',
        'reference_number',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Auto-generate reference number
    protected static function booted()
    {
        static::creating(function ($transaction) {
            if (!$transaction->reference_number) {
                $transaction->reference_number = 'NL-' . strtoupper(Str::random(10));
            }
        });
    }

    // Helper methods
    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }
}