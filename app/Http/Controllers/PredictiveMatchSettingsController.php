<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;
use App\PredictiveMatchSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;

class PredictiveMatchSettingsController extends BaseCrudController
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
    protected $audio_library_path = 'ocd_predictive_audio_library';
    protected $audio_library = NULL;

    public function __construct(Request $request)
    {
        $this->action = 'PredictiveMatchSettingsController';
        $this->resource = 'predictive_match_settings';
        $this->views_folder = 'crud';
        $this->model = new PredictiveMatchSetting();
        $this->validation_rules = [
          'pbx_from_caller_id' => [ 'required', 'regex:(^[0]\d{6,10}$)' ],
          'pbx_audio_announce_welcome' => [ 'required' ],
          'pbx_audio_announce_wait' => [ 'required' ],
          'pbx_audio_announce_fallback'  => [ 'required' ],
          'ocm_url' => [ 'required', 'url' ],
          'ocm_token' => [ 'required' ],
          'ocm_sap' => [ 'required' ],
          'ocm_sap_fallback' => [ 'required' ],
          'crm_peanut_url' => [ 'required', 'url' ],
          'crm_peanut_buyer' => [ 'required' ],
          'crm_peanut_campaign_schema' => [ 'required' ],
          'crm_peanut_outcome_id_vs_interested' => [ 'required', 'integer' ],
        ];

        $this->breadcrumbs = [];
        $this->title = 'Predictive Match Settings';

        if(!File::exists($this->audio_library_path)) {
            Storage::makeDirectory($this->audio_library_path);
        }
        $this->audio_library = Storage::files($this->audio_library_path);

        $this->fields_types = [
            'pbx_audio_announce_welcome' => [ makeFieldSelect($this->audio_library) ],
            'pbx_audio_announce_wait' => [ makeFieldSelect($this->audio_library) ],
            'pbx_audio_announce_fallback'  => [ makeFieldSelect($this->audio_library) ],
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
        'ocm_url',
        'ocm_sap',
        'ocm_sap_fallback',
        'crm_peanut_campaign_schema',
        'created_at',
        'updated_at'
     ];
   }

   /**
    * Prepare @trash environment
    * @param Request $request
    */
   protected function prepareTrash(Request $request)
   {
        $this->fields = [
            'pbx_from_caller_id',
            'ocm_url',
            'ocm_sap',
            'ocm_sap_fallback',
            'crm_peanut_campaign_schema',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
   }
}
