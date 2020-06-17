<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\PbxQueueMiddlewareSetting;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;

class PbxQueueMiddlewareSettingsController extends BaseCrudController
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
        $this->action = 'PbxQueueMiddlewareSettingsController';
        $this->resource = 'pbx_queue_middleware_settings';
        $this->views_folder = 'crud';
        $this->model = new PbxQueueMiddlewareSetting();
        $this->validation_rules = [
          'pbx_from_caller_id' => [ 'required', 'regex:(^[0]\d{6,10}\*?$)', Rule::unique('pbx_queue_middleware_settings')->ignore($request->route()->parameter('id')) ],
          'ocm_url' => [ 'required', 'url' ],
          'ocm_token' => [ 'required' ],
          'ocm_sap' => [ 'required' ],
          'ocm_sap_fallback' => [ 'required' ],
          'ocm_name' => [ 'required' ],
          'ocm_surname' => [ 'required' ],
          'ocm_offer' => [ 'required' ],
        //   'crm_peanut_url' => [ 'required', 'url' ],
        //   'crm_peanut_token' => [ 'required' ],
          'type' => [ 'required' ],
          'pbx_queue_number' => [ 'required', 'numeric' ],
        ];

        $this->breadcrumbs = [];
        $this->title = 'Queue Middleware Settings';

        $this->fields_types = [
          'crm_peanut_url' => [ makeFieldHidden() ],
          'crm_peanut_token' => [ makeFieldHidden() ],
          'type'  => [ makeFieldSelect(['inbound','click2call']) ],
        ];

        parent::__construct($request);
    }

   /**
    * Prepare @index environment
    * @param Request $request
    */
   protected function prepareIndex(Request $request)
   {
     $this->fields = [
        'pbx_from_caller_id',
        'type',
        'ocm_sap',
        'pbx_queue_number',
        'updated_at'
     ];
   }


/**
   * Called by child class, prepare @save environment
   * @param Request $request
   * @param null $id
   */
   protected function beforeSave(Request $request, $id = NULL)
   {
        $this->input['crm_peanut_url'] = env('CRM_PEANUT_URL');
        $this->input['crm_peanut_token'] = env('CRM_PEANUT_TOKEN');
   }

   /**
    * Prepare @trash environment
    * @param Request $request
    */
   protected function prepareTrash(Request $request)
   {
        $this->fields = [
          'pbx_from_caller_id',
          'type',
          'ocm_sap',
          'pbx_queue_number',
          'updated_at'
        ];
   }
}
