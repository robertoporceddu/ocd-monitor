<?php

namespace App\Http\Controllers\Management\User;
use Illuminate\Http\Request;

class UserController extends \Mmrp\Swissarmyknife\Controller\Rbac\UserController
{
    protected function prepareIndex(Request $request)
    {
        array_push($this->fields,'client_api_token');

        parent::prepareIndex($request);
    }

    protected function prepareGet(Request $request, $id)
    {
        array_push($this->fields,'client_api_token');

        parent::prepareGet($request, $id);
    }

    protected function beforeSave(Request $request, $id = NULL)
    {
        if(isset($this->input['client_api_token'])) {
            unset($this->input['client_api_token']);
        }

        if (is_null($id)) {
            $this->password = str_random(32);
            $this->input['password'] = bcrypt($this->password);
            $this->input['client_api_token'] = str_random(64);
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