<?php

namespace App;

use Illuminate\Support\Facades\DB;
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
        $settings = PbxQueueMiddlewareSetting::where('pbx_from_caller_id', 'not like', '%*%')
                        ->where('pbx_from_caller_id', $from_caller_id)->get()->first();

        if(!$settings) {
            $query = PbxQueueMiddlewareSetting::where('pbx_from_caller_id', 'like', '%*%');

            switch (env('DB_CONNECTION')) {
                case 'pgsql': {
                    $query = $query->whereRaw("'". $from_caller_id . "' ~* pbx_from_caller_id");
                    break;
                }
            }

            if($query = $query->get()->first()) {
                $settings = $query;
            }
        }

        if(!$settings) {
            throw new \Exception('fromid not found', 404);
        }

        return $settings;
    }
}
