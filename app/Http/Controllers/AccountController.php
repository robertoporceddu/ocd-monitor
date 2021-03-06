<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 28/02/17
 * Time: 17:45
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Mmrp\Swissarmyknife\Controller\ValidatePasswordTrait;
use Mmrp\Swissarmyknife\Lib\Log;
use Mmrp\Swissarmyknife\Models\Rbac\User;

class AccountController extends \App\Http\Controllers\Management\User\UserController
{
    use ValidatePasswordTrait;

    protected $action = NULL;
    protected $resource = NULL;
    protected $views_folder = NULL;
    protected $title = NULL;
    protected $model = NULL;
    protected $batch_import_model = NULL;
    protected $batch_import_log = NULL;
    protected $validation_rules = NULL;
    protected $breadcrumbs = NULL;

    protected $index = FALSE;
    protected $insert = FALSE;
    protected $delete = FALSE;
    protected $destroy = FALSE;
    protected $restore = FALSE;
    protected $trash = FALSE;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->action = 'AccountController';
        $this->resource = 'user';
        $this->views_folder = 'crud';
        $this->model = new User();
        $this->validation_rules = [
            'name' => ['required', 'string'],
        'email' => ['required', 'email'/*, Rule::unique('users')->ignore($request->route()->parameter('id'))*/],
            'password' =>[ 'confirmed', env('PASSWORD_REGEX') ],
            'profile_image' => ['image']
        ];

        $this->breadcrumbs = [];
        $this->title = '';

        $this->fields_types = [
            'profile_image' => [ makeFieldProfileImage() ],
            'password' => [makeFieldPassword(TRUE)],
        ];

        $this->fields = ['name','email','profile_image'];
    }

    protected function prepareGet(Request $request, $id)
    {
        $this->model = $this->model->findOrFail($id);

        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('user.user') . ':' . $this->model->email;

        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('user.users')],
            ['link' => '#', 'title' => $this->model->email, 'active' => TRUE],
        ];
    }

   public function get(Request $request, $id)
   {
       if(Auth::user()->id != $id){
           abort(403);
       }

       return parent::get($request, $id); // TODO: Change the autogenerated stub
   }

   public function edit(Request $request, $id)
   {
       if(Auth::user()->id != $id){
           abort(403);
       }

       return parent::edit($request, $id); // TODO: Change the autogenerated stub
   }

    public function changePassword(Request $request, $id)
    {
        try {

            $this->model = $this->model->findOrFail($id);

            $this->prepareEdit($request, $id);

            Log::info(new \Exception('changePassword', 200), $request, [
                    'action' => 'changePassword',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );

            return view('resources.account.password')
                ->with('resource', $this->resource)
                ->with('data', $this->model)
                ->with('title', '<i class="fa fa-fw fa-edit"></i> Aggiorna Password')
                ->with('breadcrumbs', $this->breadcrumbs);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'changePassword',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }

    public function postChangePassword(Request $request, $id)
    {

        $this->_validate($request);

        $this->model = $this->model->findOrFail($id);

        if(env('PASSWORD_POLICY') == 'true') {
            try {
                $this->validateOldPassword($request);
                $this->checkOldNewPassword($request);
                $this->checkDictionary($request);
            } catch (\Exception $e) {
                $error = new MessageBag();
                switch ($e->getMessage()) {
                    case 'invalid_old_password':
                        $error->add('old_password', 'Password non valida');
                        break;

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

        try {
            $this->model = new User();
            $this->model = $this->model->findOrFail($id);
            $this->model->password = bcrypt($request->input('password'));
            $this->model->password_updated_at = Carbon::now();
            $this->model->save();

            Log::info(new \Exception(($id) ? trans('messages.edit.updated') : trans('messages.edit.inserted'), 200), $request,
                [
                    'action' => 'save',
                    'resource' => $this->resource,
                    'resource_id' => ($id) ? $id : $this->model->id
                ]
            );

            return redirect()->action('HomeController@index');

        }
        catch (\Exception $e) {
            Session::flash('flash_message', ($id) ? trans('messages.edit.failed') : trans('messages.edit.failed'));

            Log::info($e, $request, [
                    'action' => 'save',
                    'resource' => $this->resource,
                ]
            );

            return redirect()->back();
        }
    }

    private function _validate(Request $request)
    {
        try{
            $this->validate($request, [
                'old_password' => [ 'required' ],
                'password' =>[ 'required', 'confirmed', env('PASSWORD_REGEX') ],
            ]);
        }
        catch (\Exception $e){
            Log::info($e, $request, [ 'action' => 'save', 'resource' => $this->resource]);
            return redirect()->back();
        }
    }
}