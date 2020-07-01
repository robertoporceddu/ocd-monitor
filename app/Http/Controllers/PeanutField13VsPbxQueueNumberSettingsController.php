<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\PeanutField13VsPbxQueueNumberSetting;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;

class PeanutField13VsPbxQueueNumberSettingsController extends BaseCrudController
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
    protected $restore = FALSE;
    protected $trash = FALSE;

    public function __construct(Request $request)
    {
        $this->action = 'PeanutField13VsPbxQueueNumberSettingsController';
        $this->resource = 'peanut_field_13_vs_pbx_queue_settings';
        $this->views_folder = 'crud';
        $this->model = new PeanutField13VsPbxQueueNumberSetting();
        $this->validation_rules = [
          'crm_peanut_field_13' => [ 'required', Rule::unique('peanut_field13_vs_pbx_queue_number_settings')->ignore($request->route()->parameter('id')) ],
          'pbx_queue_number' => [ 'required', 'numeric', Rule::unique('peanut_field13_vs_pbx_queue_number_settings')->ignore($request->route()->parameter('id')) ],
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
        'crm_peanut_field_13',
        'pbx_queue_number'
     ];
   }

}
