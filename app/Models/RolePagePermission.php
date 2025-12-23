<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePagePermission extends Model
{
    use HasFactory;

    protected $table = 'role_page_permission';

    protected $fillable = [
        'role_id',
        'page_id',
        'permission_id',
    ];

    /**
     * Relationship with Role model
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship with Page model
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Relationship with Permission model
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
