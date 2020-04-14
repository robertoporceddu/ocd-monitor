<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PredictiveMatchSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pbx_from_caller_id',
        'pbx_audio_announce_welcome',
        'pbx_audio_announce_wait',
        'pbx_audio_announce_fallback',
        'pbx_dialer_match_type',
        'ocm_url',
        'ocm_token',
        'ocm_sap',
        'ocm_sap_fallback',
        'ocm_name',
        'ocm_surname',
        'ocm_offer',
        'crm_peanut_url',
        'crm_peanut_buyer',
        'crm_peanut_token',
        'crm_peanut_campaign_schema',
        'crm_peanut_outcome_id_vs_interested',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
