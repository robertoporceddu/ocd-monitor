<?php

namespace App\Http\Controllers\Management\User;
use Illuminate\Http\Request;

class UserController extends \Mmrp\Swissarmyknife\Controller\Rbac\UserController
{
    protected function beforeSave(Request $request, $id = NULL)
    {
        if (is_null($id)) {
            $this->password = str_random(32);
            $this->input['password'] = bcrypt($this->password);
            $this->current_action = 'insert';
        } else {
            if(isset($this->input['password'])) {
                $this->input['password'] = bcrypt($this->input['password']);
            }
        }

        unset($this->input['password_confirmation']);

        if (!empty($this->input['profile_image'])) {
            $this->input['profile_image'] = 'data:' . $this->input['profile_image']->getMimeType() . ';base64,' . base64_encode(file_get_contents($this->input['profile_image']));
        }

        if(isset($this->input['roles'])) {
            $this->roles = $this->input['roles'];
            unset($this->input['roles']);
        }
    }
}