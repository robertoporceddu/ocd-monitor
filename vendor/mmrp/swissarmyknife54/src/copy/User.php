<?php

namespace App;

class User extends \Mmrp\Swissarmyknife\Models\Rbac\User
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
