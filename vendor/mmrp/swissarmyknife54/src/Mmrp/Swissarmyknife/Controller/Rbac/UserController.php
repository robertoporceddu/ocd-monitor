<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 28/06/17
 * Time: 14:27
 */

namespace Mmrp\Swissarmyknife\Controller\Rbac;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;
use Mmrp\Swissarmyknife\Mail\RegisterUser;
use Mmrp\Swissarmyknife\Models\Rbac\Permission;
use Mmrp\Swissarmyknife\Models\Rbac\User;

class UserController extends BaseCrudController
{
    protected $action = NULL;
    protected $resource = NULL;
    protected $views_folder = NULL;
    protected $title = NULL;
    protected $model = NULL;
    protected $batch_import_model = NULL;
    protected $batch_import_log = NULL;
    protected $validation_rules = NULL;
    protected $breadcrumbs = NULL;

    protected $current_action = NULL;
    protected $password = NULL;
    protected $roles = NULL;

    public function __construct(Request $request)
    {
        $this->action = 'Management\User\UserController';
        $this->resource = 'user';
        $this->views_folder = 'crud';
        $this->model = new User();
        $this->validation_rules = [
            'name' => ['required', 'string'],
        'email' => ['required', 'email', Rule::unique('users')->ignore($request->route()->parameter('id'))],
            'profile_image' => ['image'],
            'roles' => ['required'],
        ];

        $this->breadcrumbs = [];
        $this->title = '';

        $this->fields_types = [
            'profile_image' => [ makeFieldProfileImage() ],
            'password' => [makeFieldHidden()],
            'roles' => [ makeFieldTypeRelation('roles', 'name', action('Management\User\RoleController@async'), 'belongToMany', TRUE)]
        ];

        parent::__construct($request);

    }

    protected function prepareIndex(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('user.users');
        $this->breadcrumbs = [
            ['link' => '#', 'title' => trans('user.users'), 'active' => TRUE],
        ];
        $this->fields_types['profile_image'] = [makeFieldHidden()];
    }

    protected function prepareGet(Request $request, $id)
    {

        $this->model = $this->model->findOrFail($id);

        $this->preparePermissions();
        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('user.user') . ':' . $this->model->email;

        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('user.users')],
            ['link' => '#', 'title' => $this->model->email, 'active' => TRUE],
        ];
    }

    protected function prepareInsert(Request $request)
    {
        $this->fields = array_merge($this->fields, ['password']);
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('user.user');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('user.users')],
            ['link' => '#', 'title' => trans('messages.button.new'), 'active' => TRUE],
        ];

    }

    protected function prepareEdit(Request $request, $id)
    {
        $this->fields = array_merge($this->fields, ['password']);
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('user.user');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('user.users')],
            ['link' => action($this->action . '@get', ['id' => $this->model->id]), 'title' => $this->model->email,],
            ['link' => '#', 'title' => trans('messages.button.edit'), 'active' => TRUE],
        ];
    }

    protected function prepareTrash(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-trash"></i> ' . trans('messages.trash') . ' ' . trans('user.users');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('user.users')],
            ['link' => '#', 'title' => trans('messages.trash'), 'active' => TRUE],
        ];
    }

    protected function beforeSave(Request $request, $id = NULL)
    {

        if (is_null($id)) {
            $this->password = str_random(32);
            $this->input['password'] = bcrypt($this->password);
            $this->current_action = 'insert';
        }

        unset($this->input['password_confirmation']);


        if (!empty($this->input['client_api_token'])) {
            $this->input['client_api_token_last_update'] = Carbon::now();
        }

        if (!empty($this->input['profile_image'])) {
            $this->input['profile_image'] = 'data:' . $this->input['profile_image']->getMimeType() . ';base64,' . base64_encode(file_get_contents($this->input['profile_image']));
        }

        $this->roles = $this->input['roles'];
        unset($this->input['roles']);


    }

    protected function afterSave(Request $request, $id = NULL)
    {
        $this->model->roles()->sync($this->roles);

        if($this->current_action == 'insert') {
            $user = new \stdClass();
            $user->name = $this->model->name;
            $user->email = $this->model->email;
            $user->password = $this->password;

            Mail::to($this->model->email)->send(new RegisterUser($user));
        }
    }

    public function attachPermission(Request $request, $id, $permission_id)
    {
        try{
            $this->model = $this->model->findOrFail($id);

            $this->model->attachPermissions($permission_id);

            return response()->json([
                'code' => 200
            ]);
        }
        catch (\Exception $e){
            //LOG
            return redirect()->back();
        }
    }

    public function detachPermission(Request $request, $id, $permission_id)
    {
        try{
            $this->model = $this->model->findOrFail($id);

            $this->model->detachPermissions($permission_id);

            return response()->json([
                'code' => 200
            ]);
        }
        catch (\Exception $e){
            //LOG
            return redirect()->back();
        }
    }

    private function preparePermissions()
    {
        $role_permissions = $this->model->allPermissions();
        $permission_matrix = [];
        $actions = [];

        foreach (Permission::all(['id', 'name'])->toArray() as $permission) {
            list($controller, $action) = explode('@', $permission['name']);

            if (!isset($actions[$action])) {
                $actions[$action] = TRUE;
            }

            if (!isset($permission_matrix[$controller])) {
                $permission_matrix[$controller] = [];
            }

            $permission_matrix[$controller][$action] = [
                'id' => $permission['id'],
                'enabled' => (isset($role_permissions[$controller . '@' . $action]) and $role_permissions[$controller . '@' . $action]) ? TRUE : FALSE,
            ];
        }

        $this->additional_data['permissions_matrix'] = $permission_matrix;
        $this->additional_data['actions_list'] = $actions;
    }
}