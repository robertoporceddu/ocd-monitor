<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Extension extends Controller
{
    public function home(){
        return view('layouts/queued');
    }

}
