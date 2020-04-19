<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
