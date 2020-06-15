<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PbxQueueMiddlewareSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pbx_from_caller_id',
        'ocm_url',
        'ocm_token',
        'ocm_sap',
        'ocm_sap_fallback',
        'ocm_name',
        'ocm_surname',
        'ocm_offer',
        'crm_peanut_url',
        'crm_peanut_token',
        'type',
        'pbx_queue_number'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getByFromCallerId($from_caller_id)
    {
        $settings = PbxQueueMiddlewareSetting::where('pbx_from_caller_id', $from_caller_id)->get()->first();
        if(!$settings) {
            throw new \Exception('fromid not found', 404);
        }

        return $settings;
    }
}
