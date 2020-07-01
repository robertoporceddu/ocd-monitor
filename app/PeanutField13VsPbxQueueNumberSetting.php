<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeanutField13VsPbxQueueNumberSetting extends Model
{
    protected $fillable = [
        'pbx_queue_number',
        'crm_peanut_field_13'
    ];

    public static function getByField13($field_13)
    {
        return self::where('crm_peanut_field_13', $field_13)
                ->get()->first();
    }
}
