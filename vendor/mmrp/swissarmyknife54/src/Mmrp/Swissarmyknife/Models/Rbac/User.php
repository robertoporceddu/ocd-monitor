<?php

namespace Mmrp\Swissarmyknife\Models\Rbac;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $fillable = [
        'profile_image','name','password','email', 'roles'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\Role');
    }

    public function permissions()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\Permission');
    }

    public function attachRoles($roles)
    {
        return $this->roles()->attach($roles);
    }

    public function detachRoles($roles)
    {
        if($roles == 'all'){
            return $this->roles()->detach();
        }

        return $this->roles()->detach($roles);
    }

    public function syncRoles($roles)
    {
        return $this->roles()->sync($roles);
    }


    public function allPermissions()
    {
        $permissions = $not_granted = [];

        //Roles Permissions Granted
        foreach ($this->roles as $role) {
            foreach ($role->permissions()->where('granted',1)->pluck('model')->toArray() as $permission) {
                $permissions[$permission] = TRUE;
            }
        }

        //Users Permissions Granted
        foreach ($this->permissions()->where('granted',1)->pluck('model') as $permission) {
            $permissions[$permission] = TRUE;
        }

        //Users Permissions Not Granted
        foreach ($this->permissions()->where('granted',0)->pluck('model') as $permission) {
            $not_granted[$permission] = FALSE;
        }

        return array_merge($permissions, $not_granted);
    }

    public function attachPermissions($permissions)
    {
        if($this->roles()->find($permissions)) {
            return $this->permissions()->detach($permissions);
        } else {
            return $this->permissions()->attach($permissions);
        }
    }

    public function detachPermissions($permissions)
    {
//        if($permissions == 'all'){
//            return $this->permissions()->detach();
//        }

        if($this->roles()->find($permissions)){
            return $this->permissions()->sync([$permissions => ['granted' => false]]);
        } else {
            return $this->permissions()->detach($permissions);
        }
    }

    public function syncPermissions($permissions)
    {
        return $this->permissions()->sync($permissions);
    }

    public function hasPermission($permission)
    {
        $permissions = $this->allPermissions();

        if(isset($permissions[$permission])){
            return TRUE;
        }

        return FALSE;
    }
}
