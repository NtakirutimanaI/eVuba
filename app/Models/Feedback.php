<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'customer_id',
        'feedbackable_id',
        'feedbackable_type',
        'rating',
        'response_time_rating',
        'resolution_quality_rating',
        'communication_rating',
        'comments',
        'attachment',
        'sla_compliant',
        'actual_completion_time',
        'sla_threshold',
        'status',
    ];

    /**
     * Get the parent feedbackable model (Ticket or Appointment).
     */
    public function feedbackable()
    {
        return $this->morphTo();
    }

    /**
     * Get the customer that owns the feedback.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
