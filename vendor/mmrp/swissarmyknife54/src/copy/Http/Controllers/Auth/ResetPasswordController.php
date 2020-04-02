<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Mmrp\Swissarmyknife\Controller\ValidatePasswordTrait;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    use ValidatePasswordTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' =>[ 'required', 'confirmed', env('PASSWORD_REGEX') ],

        ];
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
            'password_updated_at' => Carbon::now()
        ])->save();

        $this->guard()->login($user);
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        if(env('PASSWORD_POLICY') == 'true') {
            try {
                $this->checkOldNewPassword($request);
                $this->checkDictionary($request);
            } catch (\Exception $e) {
                $error = new MessageBag();
                switch ($e->getMessage()) {
                    case 'old_new_password_same':
                        $error->add('password', 'La nuova password deve esser diversa dalla precedente');
                        break;

                    case 'dictionary':
                        $error->add('password', 'La password contiene parole non ammesse');
                        break;
                }
                return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag())->put('default', $error));
            }
        }
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }
}
