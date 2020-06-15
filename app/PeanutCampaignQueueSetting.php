<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeanutCampaignQueueSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crm_peanut_campaign_schema',
        'pbx_queue_number',
        'ocm_url',
        'ocm_token',
        'ocm_sap_fallback',
        'ocm_name',
        'ocm_surname',
        'ocm_offer'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getByCampaign($campaign)
    {
        return PeanutCampaignQueueSetting::where('crm_peanut_campaign_schema', $campaign)
                ->get()->first();
    }
}
