<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Employee;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Task;
use App\Models\Report;
use App\Models\Team;
use App\Models\TeamMember;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    // ================= Mass Assignment =================

    protected $fillable = [
        'manager_id',
        'name',
        'email',
        'password',
        'photo',
        'role',
        'role_id',

        // profile
        'phone',
        'address',
        'gender',

        // status & activity
        'status',
        'last_login_at',
        'last_seen_at',

        // notifications
        'notification_enabled',
        'email_notifications',
    ];

    // ================= Hidden =================

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ================= Casts =================

    protected $casts = [
        'email_verified_at'      => 'datetime',
        'last_login_at'          => 'datetime',
        'last_seen_at'           => 'datetime',
        'notification_enabled'   => 'boolean',
        'email_notifications'    => 'boolean',
        'password'               => 'hashed',
    ];

    // ================= Relationships =================

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function assignedAppointments()
    {
        return $this->hasMany(Appointment::class, 'employee_id');
    }

    public function managedAppointments()
    {
        return $this->hasMany(Appointment::class, 'manager_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Manager → Team Members
    public function teamMembers()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    // Employee relation (self reference – kept as requested)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Teams (pivot table)
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
                    ->withPivot('role', 'position', 'department', 'email', 'phone')
                    ->withTimestamps();
    }

    public function teamMember()
    {
        return $this->hasOne(TeamMember::class);
    }

    // ================= Tickets =================
    
    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'user_announcements');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'assigned_to');
    }

    // ================= Permissions =================

    public function getMenuPermissions(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    // ================= Performance Metrics =================

    public function performanceMetrics(): array
    {
        return [
            'tasks_assigned'   => $this->tasks()->count(),
            'tasks_completed'  => $this->tasks()->where('status', 'completed')->count(),
            'tickets_handled'  => $this->tickets()->count(),
            'tickets_resolved' => $this->tickets()->where('status', 'resolved')->count(),
        ];
    }

    // ================= Booted Model Events =================

    protected static function booted()
    {
        // ----------- CREATE -----------
        static::created(function ($user) {

            // Sync Employee (admin / manager / employee)
            if (in_array($user->role, ['admin', 'manager', 'employee'])) {
                Employee::updateOrCreate(
                    ['id' => $user->id],
                    [
                        'name'           => $user->name,
                        'email'          => $user->email,
                        'phone'          => $user->phone,
                        'position'       => ucfirst($user->role),
                        'specialization' => null,
                        'department'     => null,
                        'created_at'     => $user->created_at,
                        'updated_at'     => $user->updated_at,
                    ]
                );
            }

            // Sync Customer
            if ($user->role === 'customer') {
                Customer::updateOrCreate(
                    ['id' => $user->id],
                    [
                        'name'       => $user->name,
                        'email'      => $user->email,
                        'phone'      => $user->phone,
                        'address'    => $user->address,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                );
            }
        });

        // ----------- UPDATE -----------
        static::updated(function ($user) {

            // Employee sync
            if (in_array($user->role, ['admin', 'manager', 'employee'])) {
                $employee = Employee::find($user->id);
                if ($employee) {
                    $employee->update([
                        'name'  => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'position' => $employee->position ?? ucfirst($user->role),
                    ]);
                } else {
                    Employee::create([
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'position' => ucfirst($user->role),
                    ]);
                }
            } else {
                Employee::where('id', $user->id)->delete();
            }

            // Customer sync
            if ($user->role === 'customer') {
                Customer::updateOrCreate(
                    ['id' => $user->id],
                    [
                        'name'       => $user->name,
                        'email'      => $user->email,
                        'phone'      => $user->phone,
                        'address'    => $user->address,
                        'updated_at' => now(),
                    ]
                );
            } else {
                Customer::where('id', $user->id)->delete();
            }
        });

        // ----------- DELETE (Soft or Force) -----------
        static::deleted(function ($user) {
            Employee::where('id', $user->id)->delete();
            Customer::where('id', $user->id)->delete();
        });
    }
}
