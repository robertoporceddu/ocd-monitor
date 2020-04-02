<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 11:59
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;

use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Lib\Log;

trait GetTrait
{

    /**
     * Used to enable/disable get() method
     * @var bool
     */
    protected $get = TRUE;

    /**
     * Return View that contains a specified row
     * @param Request $request
     * @param $id
     * @return View
     */
    public function get(Request $request, $id)
    {
        if(!$this->get){
            abort(501);
        }

        $id = $request->route()->parameter('id');

        try {
            $this->model = $this->model->findOrFail($id);

            $this->prepareGet($request, $id);

            Log::info(new \Exception('get',200), $request, [
                    'action' => 'get',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );

            return view(preg_match('/show/',$this->views_folder) ? $this->views_folder : $this->views_folder . '.show')
                ->with('action',$this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('fields', $this->fields)
                ->with('translated_fields', $this->translate_fields)
                ->with('fields_types', $this->fields_types)
                ->with('data', $this->model)
                ->with('additional_data',$this->additional_data)
                ->with('available_action', $this->prepareAction())
                ->with('custom_actions', $this->custom_action)
                ->with('breadcrumbs', $this->breadcrumbs);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'get',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }

    /**
     * Called by child class, prepare @get environment
     * @param Request $request
     * @param $id
     */
    protected function prepareGet(Request $request, $id)
    {

    }
}