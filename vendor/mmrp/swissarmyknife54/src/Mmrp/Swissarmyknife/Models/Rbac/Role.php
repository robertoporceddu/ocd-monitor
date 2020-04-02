<?php

namespace Mmrp\Swissarmyknife\Models\Rbac;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','slug','description','parent_id'];

    public function users()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\User');

    }

    public function permissions()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\Permission');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Role','parent_id');
    }

    public function ancestors()
    {
        $ancestors = $this->where('id', '=', $this->parent_id)->get();

        while($ancestors->last() && $ancestors->last()->parent_id !== null)
        {
            $parent = $this->where('id', '=', $ancestors->last()->parent_id)->get();
            $ancestors = $ancestors->merge($parent);
        }

        return $ancestors;
    }

    public function children()
    {
        return $this->hasMany('App\Models\Role','parent_id');
    }

    public function descendants()
    {
        $tree = [];

        $descendants = $this->where('parent_id', $this->id)->get();

        foreach($descendants as $descendant) {
            $descendant->children = $descendant->descendants();
            $tree[] = $descendant;
        }

        return $tree;
    }

    public function allPermissions()
    {

    }

    public function attachPermissions($permissions)
    {
        return $this->permissions()->attach($permissions);
    }

    public function detachPermissions($permissions)
    {
        if($permissions == 'all'){
            return $this->permissions()->detach();
        }

        return $this->permissions()->detach($permissions);
    }

    public function attachUsers($users)
    {
        return $this->users()->attach($users);
    }

    public function detachUsers($users)
    {
        if($users == 'all'){
            return $this->users()->detach();
        }

        return $this->users()->detach($users);
    }
}