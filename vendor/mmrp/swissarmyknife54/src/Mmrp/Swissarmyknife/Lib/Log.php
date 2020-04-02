<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 11/04/17
 * Time: 14:55
 */

namespace Mmrp\Swissarmyknife\Lib;

use Mmrp\Swissarmyknife\Models\Logs;
use Illuminate\Support\Facades\Log as LaravelLog;

class Log
{
    public static function __callStatic($name, $arguments)
    {
        switch ($name){
            case 'emergengy':
                $arguments[2]['type'] = 'emergency';
                LaravelLog::emergency($arguments[2]);
                break;
            case 'alert':
                $arguments[2]['type'] = 'alert';
                LaravelLog::alert($arguments[2]);
                break;
            case 'critical':
                $arguments[2]['type'] = 'critical';
                LaravelLog::critical($arguments[2]);
                break;
            case 'error':
                $arguments[2]['type'] = 'error';
                LaravelLog::error($arguments[2]);
                break;
            case 'warning':
                $arguments[2]['type'] = 'warning';
                LaravelLog::warning($arguments[2]);
                break;
            case 'notice':
                $arguments[2]['type'] = 'notice';
                LaravelLog::notice($arguments[2]);
                break;
            case 'info':
                $arguments[2]['type'] = 'info';
                LaravelLog::info($arguments[2]);
                break;
            case 'debug':
                $arguments[2]['type'] = 'debug';
                LaravelLog::debug($arguments[2]);
                break;
        }

        Logs::newLine($arguments[0], $arguments[1], $arguments[2]);
    }
}