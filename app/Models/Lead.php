<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
        protected $fillable = [
        'name',
        'email',
        'phone',
        'product_service',
        'lead_source',
        'communication_method',
        'requirements',
        'status',
        'assigned_to',
        'follow_up_date',
        'priority',
        'follow_up_reminder_sent_at',
        'admin_escalation_sent_at',
        'converted_at',
        'conversion_value',
        'conversion_notes',
    ];

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'follow_up_date' => 'date',
            'follow_up_reminder_sent_at' => 'datetime',
            'admin_escalation_sent_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }
}