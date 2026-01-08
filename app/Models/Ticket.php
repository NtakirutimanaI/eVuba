<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_no',
        'customer_id',
        'category_id',
        'subject',
        'description',
        'attachment',
        'status',
        'assigned_to', // assigned staff
        'priority',    // optional priority
    ];

    /**
     * Automatically generate ticket number if not provided
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_no)) {
                $ticket->ticket_no = 'TCK-' . strtoupper(uniqid());
            }
        });
    }

    // Relationships

    /** Ticket owner (customer) */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** Ticket category */
    public function category()
    {
        return $this->belongsTo(SupportCategory::class, 'category_id');
    }

    /**
     * Fallback relationship to User model for tickets created by non-customers (e.g. Admins/Employees)
     * Maps 'customer_id' to 'id' on users table.
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /** Replies for this ticket */
    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('id', 'DESC');
    }

    /** Ticket history logs */
    public function logs()
    {
        return $this->hasMany(TicketLog::class)->orderBy('id', 'DESC');
    }

    /** Assigned staff user */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Alias for convenience
    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
