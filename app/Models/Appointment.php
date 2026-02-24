<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Fillable fields for mass assignment
     */
    protected $fillable = [
        'user_id',        // Customer who booked the appointment
        'manager_id',     // Manager responsible
        'employee_id',    // Assigned employee (optional)
        'title',
        'description',
        'status',         // e.g., pending, completed, cancelled
        'scheduled_at',   // Appointment date and time
    ];

    /**
     * Casts
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($appointment) {
            if ($appointment->isDirty('status') && strtolower($appointment->status) === 'completed') {
                $appointment->generateFeedbackRequest();
            }
        });
    }

    /**
     * The customer who created the appointment (Business Entity)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Explicit link to Customer profile
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    /**
     * The manager responsible for the appointment
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * The employee assigned to this appointment
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Scope: Upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now())->orderBy('scheduled_at');
    }

    /**
     * Scope: Completed appointments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')->orderBy('scheduled_at', 'desc');
    }

    /**
     * Scope: Pending appointments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')->orderBy('scheduled_at');
    }

    /**
     * Scope: Appointments assigned to a specific manager
     */
    public function scopeForManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Scope: Appointments assigned to a specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /** Feedback for this appointment */
    public function feedback()
    {
        return $this->morphOne(Feedback::class, 'feedbackable');
    }

    public function generateFeedbackRequest()
    {
        if (!$this->feedback) {
            $actualTime = $this->scheduled_at ? $this->scheduled_at->diffInMinutes($this->updated_at) : 0;
            $slaThreshold = 60; // 1 hour threshold

            $this->feedback()->create([
                'customer_id' => $this->user_id,
                'status' => 'pending',
                'actual_completion_time' => $actualTime,
                'sla_threshold' => $slaThreshold,
                'sla_compliant' => $actualTime <= $slaThreshold,
            ]);
        }
    }
}
