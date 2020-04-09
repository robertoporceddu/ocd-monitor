<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 28/06/17
 * Time: 15:26
 */

namespace Mmrp\Swissarmyknife\Controller\Rbac;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;
use Mmrp\Swissarmyknife\Models\Rbac\Permission;

class PermissionController extends BaseCrudController
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

        $this->action = 'Management\User\PermissionController';
        $this->resource = 'permission';
        $this->views_folder = 'crud';
        $this->model = new Permission();
        $this->validation_rules = [
        'name' => ['required', 'string', Rule::unique('permissions')->ignore($request->route()->parameter('id'))],
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
        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('permission.permissions');
        $this->breadcrumbs = [
            ['link' => '#', 'title' => trans('permission.permissions'), 'active' => TRUE],
        ];
    }

    protected function prepareGet(Request $request, $id)
    {

        $this->model = $this->model->findOrFail($id);

        $this->title = '<i class="fa fa-fw fa-users"></i> ' . trans('permission.permission') . ':' . $this->model->email;

        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('permission.permissions')],
            ['link' => '#', 'title' => $this->model->name, 'active' => TRUE],
        ];
    }

    protected function prepareInsert(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('permission.permission');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('permission.permissions')],
            ['link' => '#', 'title' => trans('messages.button.new'), 'active' => TRUE],
        ];

    }

    protected function prepareEdit(Request $request, $id)
    {
        $this->title = '<i class="fa fa-fw fa-users"></i>' . trans('permission.permission');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('permission.permissions')],
            ['link' => action($this->action . '@get', ['id' => $this->model->id]), 'title' => $this->model->name,],
            ['link' => '#', 'title' => trans('messages.button.edit'), 'active' => TRUE],
        ];
    }

    protected function prepareTrash(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-trash"></i> ' . trans('messages.trash') . ' ' . trans('permission.permissions');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('permission.permissions')],
            ['link' => '#', 'title' => trans('messages.trash'), 'active' => TRUE],
        ];
    }

    protected function beforeSave(Request $request, $id = NULL)
    {

        $this->input['slug'] = normalize_name($this->input['name']);
    }
}