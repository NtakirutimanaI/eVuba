<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationship: A page has many role-permissions
     */
    public function rolePermissions()
    {
        return $this->hasMany(RolePagePermission::class);
    }

    /**
     * Relationship: A page belongs to many roles via pivot table
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_page_permission')
                    ->withPivot('permission_id')
                    ->withTimestamps();
    }
}
