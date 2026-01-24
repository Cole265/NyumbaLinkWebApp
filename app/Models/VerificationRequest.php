<?php
// app/Models/VerificationRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_document_path',
        'proof_of_ownership',
        'status',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function approve(string $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        // Update landlord profile
        $this->user->landlordProfile->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
        ]);
    }

    public function reject(string $notes)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        $this->user->landlordProfile->update([
            'verification_status' => 'rejected',
        ]);
    }
}