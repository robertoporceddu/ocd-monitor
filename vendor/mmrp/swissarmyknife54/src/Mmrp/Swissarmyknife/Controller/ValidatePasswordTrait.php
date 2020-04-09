<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 02/05/17
 * Time: 15:10
 */

namespace Mmrp\Swissarmyknife\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

trait ValidatePasswordTrait
{
    protected $model;

    protected function validateOldPassword(Request $request)
    {
        if(!Hash::check($request->input('old_password'), $this->model->password)){
            throw new Exception('invalid_old_password');
        }
    }

    protected function checkOldNewPassword(Request $request)
    {
        if(Hash::check($request->input('password'), $this->model->password)){
            throw new Exception('old_new_password_same');
        }
    }

    protected function checkDictionary(Request $request)
    {
        $dictionary = explode(' ', $this->model->name);
        $dictionary[] = $this->model->email;
        foreach($dictionary as $i => $word){
            if ($word != "" and preg_match('/\b.*' . $word . '.*\b/i', $request->input('password'))) {
                throw new Exception('dictionary');
            }
        }
    }
}