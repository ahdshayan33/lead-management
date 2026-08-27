<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'communication_method',
        'notes',
        'follow_up_date',
    ];

    /**
     * The lead this activity belongs to.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * The user who recorded this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}