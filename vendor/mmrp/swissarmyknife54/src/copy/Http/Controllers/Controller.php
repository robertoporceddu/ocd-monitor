<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function filterAndOrder(Request $request,$resource)
    {
        if (count($request->input())) {
            foreach ($request->input() as $filter => $value) {
                if ($value and preg_match('/^s_/',$filter)) {
                    $resource = $resource->where(substr($filter,2), 'like', '%' . str_replace(' ', '%', addslashes($value)) . '%');
                }

                if ($value and preg_match('/^o_/',$filter)) {
                    $resource = $resource->orderBy(substr($filter,2), $value );
                }
            }
        }

        return $resource;
    }


    protected function addFilter(Request $request,$resource)
    {
        return $resource;
    }
}
