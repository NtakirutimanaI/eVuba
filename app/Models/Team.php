<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Team has many members
     */
    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Convenience: Get all users in the team
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
                    ->withPivot('role', 'position', 'department', 'email', 'phone')
                    ->withTimestamps();
    }
}
