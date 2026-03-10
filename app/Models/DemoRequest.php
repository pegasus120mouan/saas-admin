<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
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
}
