<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 28/06/17
 * Time: 14:49
 */

namespace Mmrp\Swissarmyknife\Controller\Rbac;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;
use Mmrp\Swissarmyknife\Models\Rbac\Permission;
use Mmrp\Swissarmyknife\Models\Rbac\Role;
use Mmrp\Swissarmyknife\Models\Rbac\User;

class RoleController extends BaseCrudController
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

    public function __construct(Request $request)
    {

        $this->action = 'Management\User\RoleController';
        $this->resource = 'role';
        $this->views_folder = 'crud';
        $this->model = new Role();
        $this->validation_rules = [
        'name' => ['required', 'string', Rule::unique('roles')->ignore($request->route()->parameter('id'))],
        ];

        $this->breadcrumbs = [];
        $this->title = '';

        $this->fields_types = [
            'slug' => [ makeFieldHidden() ]
        ];

        parent::__construct($request);

    }

    protected function prepareIndex(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('role.roles');
        $this->breadcrumbs = [
            ['link' => '#', 'title' => trans('role.roles'), 'active' => TRUE],
        ];
    }

    protected function prepareGet(Request $request, $id)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('role.roles');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('role.roles')],
            ['link' => '#', 'title' => $this->model->name, 'active' => TRUE],
        ];

        $this->preparePermissions();
        $this->prepareUsers();
    }

    protected function prepareInsert(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('role.role');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('role.roles')],
            ['link' => '#', 'title' => trans('messages.button.new'), 'active' => TRUE],
        ];

    }

    protected function prepareEdit(Request $request, $id)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('role.role');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('role.roles')],
            ['link' => action($this->action . '@get', ['id' => $this->model->id]), 'title' => $this->model->name],
            ['link' => '#', 'title' => trans('messages.button.edit'), 'active' => TRUE],
        ];
    }

    protected function prepareTrash(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-trash"></i> ' . trans('messages.trash') . ' ' . trans('role.roles');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('role.roles')],
            ['link' => '#', 'title' => trans('messages.trash'), 'active' => TRUE],
        ];
    }

    protected function beforeSave(Request $request, $id = NULL)
    {
        $this->input['slug'] = normalize_name($this->input['name']);
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

    public function attachUser(Request $request, $id, $user_id)
    {
        try{
            $this->model = $this->model->findOrFail($id);

            $this->model->attachUsers($user_id);

            return response()->json([
                'code' => 200
            ]);
        }
        catch (\Exception $e){
            //LOG
            return redirect()->back();
        }
    }

    public function detachUser(Request $request, $id, $user_id)
    {
        try{
            $this->model = $this->model->findOrFail($id);

            $this->model->detachUsers($user_id);

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
        $role_permissions = [];
        $permission_matrix = [];
        $actions = [];

        foreach ($this->model->permissions->pluck('name') as $permission) {
            if (!isset($role_permissions[$permission])) {
                $role_permissions[$permission] = true;
            }
        }

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
                'enabled' => isset($role_permissions[$controller . '@' . $action]) ? TRUE : FALSE,
            ];
        }

        $this->additional_data['permissions_matrix'] = $permission_matrix;
        $this->additional_data['actions_list'] = $actions;
    }

    private function prepareUsers()
    {
        $users = [];
        $users_role = [];

        foreach ($this->model->users->pluck('email')->toArray() as $i => $item) {
            $users_role[$item] = TRUE;
        }


        foreach (User::all('id','name','email') as $item) {
            $users[$item->email] = [
                'enabled' => (isset($users_role[$item->email]) ? TRUE : FALSE),
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
            ];
        }

        $this->additional_data['users'] = $users;
    }
}