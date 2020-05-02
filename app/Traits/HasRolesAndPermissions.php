<?php
namespace App\Traits;

use App\Role;
use App\Permission;

trait HasRolesAndPermissions
{

    /**
     * @return mixed
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        if ($this->role) {
            return $this->role->permissions();
        } else {
            return null;
        }
    }

    /**
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(... $roles)
    {
        if ($this->role) {
            foreach ($roles as $role) {
                if ($this->role->slug === $role) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        if ($this->permissions()) {
            return $this->permissions->contains('slug', $permissionSlug);
        } else {
            return false;
        }
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function givePermissionsTo(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function deletePermissions(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return HasRolesAndPermissions
     */
    public function refreshPermissions(... $permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }
}
