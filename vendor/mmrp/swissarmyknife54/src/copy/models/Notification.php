<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = ['type','message','from','to','notify_at','opened_at'];

    protected $casts = [
        'notify_at' => 'text'
    ];

    /**
     * Create a new notification type INFO
     */
    public function insertSystemInfoNotification()
    {
        $this->type = 'info';
        $this->from = 'Octopus CM';
        $this->save();
    }

    /**
     * Create a new notification type ERROR
     */
    public function insertSystemErrorNotification()
    {
        $this->type = 'error';
        $this->from = 'Octopus CM';
        $this->save();
    }

    public function userTo()
    {
        return $this->belongsTo('App\Models\User','to','email');
    }

    public function userFrom()
    {
        return $this->belongsTo('App\Models\User','from','email');
    }


    public function getCreatedAtAttribute($value)
    {
        return ($value) ? Carbon::parse($value)->format(dateFormat('datetime')) : $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return ($value) ? Carbon::parse($value)->format(dateFormat('datetime')) : $value;
    }

    public function getDeletedAtAttribute($value)
    {
        return ($value) ? Carbon::parse($value)->format(dateFormat('datetime')) : $value;
    }

    public function getNotifyAtAttribute($value)
    {
        return ($value) ? Carbon::parse($value)->format(dateFormat('datetime')) : $value;
    }

    public function getOpenedAtAttribute($value)
    {
        return ($value) ? Carbon::parse($value)->format(dateFormat('datetime')) : $value;
    }

}

