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
          'pbx_from_caller_id' => [ 'required', 'regex:(^[0]\d{6,10}$)', Rule::unique('predictive_match_settings')->ignore($request->route()->parameter('id')) ],
          'pbx_dialer_match_type' => [ 'required' ],
          'pbx_audio_announce_welcome' => [ 'required' ],
          'pbx_audio_announce_wait' => [ 'required' ],
          'pbx_audio_announce_fallback'  => [ 'required' ],
          'ocm_url' => [ 'required', 'url' ],
          'ocm_token' => [ 'required' ],
          'ocm_sap' => [ 'required' ],
          'ocm_sap_fallback' => [ 'required' ],
          'ocm_name' => [ 'required' ],
          'ocm_surname' => [ 'required' ],
          'ocm_offer' => [ 'required' ],
          //'crm_peanut_url' => [ 'required', 'url' ],
          //'crm_peanut_token' => [ 'required' ],
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
        $this->audio_library = array_map(function($i) { return $this->audio_library_path.'/'.pathinfo($i, PATHINFO_FILENAME); }, $this->audio_library);
        $this->audio_library = array_unique($this->audio_library);

        $this->fields_types = [
            'pbx_audio_announce_welcome' => [ makeFieldSelect($this->audio_library) ],
            'pbx_audio_announce_wait' => [ makeFieldSelect($this->audio_library) ],
            'pbx_audio_announce_fallback'  => [ makeFieldSelect($this->audio_library) ],
            'pbx_dialer_match_type'  => [ makeFieldSelect(['fallback_only','to_extension_or_fallback']) ],
            'crm_peanut_url' => [ makeFieldHidden()],
            'crm_peanut_token' => [ makeFieldHidden()]
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
