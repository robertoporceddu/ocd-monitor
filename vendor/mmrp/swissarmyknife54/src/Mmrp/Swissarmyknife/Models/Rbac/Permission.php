<?php

namespace Mmrp\Swissarmyknife\Models\Rbac;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'model'];

    public function roles()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\Role');
    }

    public function users()
    {
        return $this->belongsToMany('Mmrp\Swissarmyknife\Models\Rbac\User');

    }
}
