<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerController extends Controller
{

    public function home(){

        chdir('../../../../../');
        $log = file_get_contents('var/log/mysql/error.log');
        $memory_usage = shell_exec('free -tm | awk \'{if ($1 == "Mem:") print ($3*100)/$2}\'');
        $memory_swap = shell_exec('free -tm | awk \'{if ($1 == "Swap:") print ($3*100)/$2}\'');
        $cpu = exec('top -b -n1 | grep \'Cpu(s):\' | awk \'{print $2}\'');


        if($cpu==0) $cpu=0.01;
        if($memory_swap==0) $memory_swap=0.01;
        return view('layouts/cpu', ["cpu" => $cpu, "ram" => (float)$memory_usage, "swap" => $memory_swap, "log" => $log]);
    }

    public function serverReboot(){
        shell_exec('chmod 777 plugins/bash/script.sh');
        $log_reboot = shell_exec('plugins/bash/script.sh');
        $error= shell_exec('plugins/bash/script.sh 2>&1');

/*
        return view('layouts/partials/reboot' , ["error" => $error , "log_reboot" => $log_reboot]);*/
    }

    public function ocdLog(){
        chdir('../../../../../');
        $log = file_get_contents('var/log/mysql/error.log');
            return $log;
    }

    public function getCpu(){
        $memory_usage = shell_exec('free -tm | awk \'{if ($1 == "Mem:") print ($3*100)/$2}\'');
        $memory_swap = shell_exec('free -tm | awk \'{if ($1 == "Swap:") print ($3*100)/$2}\'');
        $cpu = exec('top -b -n1 | grep \'Cpu(s):\' | awk \'{print $2}\'');
        if($cpu==0) $cpu=0.01;
        if($memory_swap==0) $memory_swap=0.01;
        /*return view('layouts/partials/cpu', ['cpu'=> $cpu, 'ram' => (float)$memory_usage, 'swap' => $memory_swap]);*/
        return $cpu;
    }

}

