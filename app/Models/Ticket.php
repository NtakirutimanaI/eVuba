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
