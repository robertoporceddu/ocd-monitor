<?php
/**
 * Created by PhpStorm.
 * User: Matteo Meloni
 * Date: 04/08/2016
 * Time: 10:49
 */

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;
use Mmrp\Swissarmyknife\Models\Logs;

class LogController extends BaseCrudController
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

    protected $insert = false;
    protected $edit = false;

    public function __construct(Request $request)
    {
        $this->action = 'Management\LogController';
        $this->resource = 'log';
        $this->views_folder = 'crud';
        $this->model = new Logs();

        $this->fields_types = [
          'user_id' => [ makeFieldTypeRelation('user','name',action('Management\User\UserController@async'))]
        ];

        $this->breadcrumbs = [];
        $this->title = '';

        parent::__construct($request);
    }

    protected function prepareIndex(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-archive"></i> ' . trans('log.logs');
        $this->breadcrumbs = [
            ['link' => '#', 'title' => trans('log.logs'), 'active' => TRUE],
        ];
        $this->fields = ['type', 'action', 'user_id', 'resource','resource_id','code','created_at'];
    }

    protected function prepareGet(Request $request, $id)
    {
        $this->fields = ['type', 'action', 'resource','resource_id','code','message','created_at'];
        $this->title = '<i class="fa fa-fw fa-archive"></i> ' . trans('log.log') . ':' . $this->model->id;
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('log.logs')],
            ['link' => '#', 'title' => $this->model->id, 'active' => TRUE],
        ];
    }

    protected function prepareTrash(Request $request)
    {
        $this->fields = ['type', 'action', 'resource','resource_id','code','message','created_at'];
        $this->title = '<i class="fa fa-fw fa-trash"></i> ' . trans('messages.trash') . ' ' . trans('log.logs');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('log.logs')],
            ['link' => '#', 'title' => trans('messages.trash'), 'active' => TRUE],
        ];
    }

    protected function addFilter(Request $request, $resource)
    {
       return $this->model->orderBy('created_at','desc');
    }
}