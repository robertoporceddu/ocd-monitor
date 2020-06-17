<?php

namespace App\Http\Controllers;

use App\PbxQueueMiddlewareLog;
use Illuminate\Http\Request;

use Mmrp\Swissarmyknife\Controller\BaseCrudController;

class PbxQueueMiddlewareLogsController extends BaseCrudController
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

    protected $index = TRUE;
    protected $show = TRUE;
    protected $delete = TRUE;

    protected $insert = FALSE;
    protected $edit = FALSE;
    protected $save = FALSE;
    protected $destroy = FALSE;
    protected $restore = FALSE;
    protected $trash = FALSE;

    public function __construct(Request $request)
    {
        $this->action = 'PbxQueueMiddlewareLogsController';
        $this->resource = 'pbx_queue_middleware_logs';
        $this->views_folder = 'crud';
        $this->model = new PbxQueueMiddlewareLog();
        $this->validation_rules = [ ];

        $this->breadcrumbs = [];
        $this->title = 'Queue Middleware Logs';

        $this->fields_types = [];

        parent::__construct($request);
    }

   /**
    * Prepare @index environment
    * @param Request $request
    */
   protected function prepareIndex(Request $request)
   {
     $this->fields = [
      'api_method',
      'from_user',
      'from_ip',
      'callerid',
      'fromid',
      'extension',
      'error_code',
      'created_at'
    ];
   }
}
