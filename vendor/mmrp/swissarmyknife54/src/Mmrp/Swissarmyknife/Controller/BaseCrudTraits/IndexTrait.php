<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 11:57
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;

use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Lib\Log;

trait IndexTrait
{
    /**
     * Used to enable/disable index() method
     * @var bool
     */
    protected $index = TRUE;

    /**
     * Return Index View
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if(!$this->index){
            abort(501);
        }

        try {
            $this->model = $this->filterAndOrder($request, $this->model);
            $this->model = $this->addFilter($request,$this->model);

            if($request->input('per_page') == 'all'){
                $this->model = $this->model->get();
            } else {
                $this->model = $this->model->paginate(($request->input('per_page')) ? $request->input('per_page') : 25);
                $this->model->appends($request->input())->links();
            }

            $this->prepareIndex($request);

            Log::info(new \Exception('getIndex', 200), $request,
                [
                    'action' => 'getIndex',
                    'resource' => $this->resource,
                ]
            );

            return view(preg_match('/index/',$this->views_folder) ? $this->views_folder : $this->views_folder . '.index')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('fields', $this->fields)
                ->with('translate_fields', $this->translate_fields)
                ->with('fields_types', $this->fields_types)
                ->with('data', $this->model)
                ->with('additional_data',$this->additional_data)
                ->with('breadcrumbs',$this->breadcrumbs)
                ->with('available_action', $this->prepareAction())
                ->with('custom_actions', $this->custom_action)
                ->with('batch_import', method_exists($this,'initBatchImportTrait'));
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'getIndex',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Called by child class, prepare @index environment
     * @param Request $request
     */
    protected function prepareIndex(Request $request)
    {

    }

}