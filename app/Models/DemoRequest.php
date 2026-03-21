<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'verification_token',
        'email_verified_at',
        'company',
        'phone',
        'employees',
        'modules',
        'status',
        'notes',
        'contacted_at',
        'scheduled_at',
        'assigned_to',
        'tenant_id',
        'provisioned_at',
    ];

    protected $casts = [
        'modules' => 'array',
        'contacted_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'provisioned_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function isProvisioned(): bool
    {
        return $this->tenant_id !== null;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
        ]);
    }

    public function schedule(\DateTime $date): void
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_at' => $date,
        ]);
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function generateVerificationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update(['verification_token' => $token]);
        return $token;
    }

    public function verifyEmail(): void
    {
        $this->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);
    }
}
