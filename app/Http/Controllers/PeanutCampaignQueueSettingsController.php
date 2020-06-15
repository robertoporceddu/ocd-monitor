<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\PeanutCampaignQueueSetting;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;

class PeanutCampaignQueueSettingsController extends BaseCrudController
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
        $this->action = 'PeanutCampaignQueueSettingsController';
        $this->resource = 'peanut_campaign_queue_settings';
        $this->views_folder = 'crud';
        $this->model = new PeanutCampaignQueueSetting();
        $this->validation_rules = [
          'crm_peanut_campaign_schema' => [ 'required', Rule::unique('peanut_campaign_queue_settings')->ignore($request->route()->parameter('id')) ],
          'pbx_queue_number' => [ 'required', 'numeric' ],
          'ocm_url' => [ 'required', 'url' ],
          'ocm_token' => [ 'required' ],
          'ocm_sap_fallback' => [ 'required' ],
          'ocm_name' => [ 'required' ],
          'ocm_surname' => [ 'required' ],
          'ocm_offer' => [ 'required' ],
        ];

        $this->breadcrumbs = [];
        $this->title = 'Peanut Campaign Queue Settings';

        $this->fields_types = [
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
        'crm_peanut_campaign_schema',
        'pbx_queue_number'
     ];
   }

  /**
    * Prepare @trash environment
    * @param Request $request
    */
    protected function prepareTrash(Request $request)
    {
         $this->fields = [
          'crm_peanut_campaign_schema',
          'pbx_queue_number'
         ];
    }
}
