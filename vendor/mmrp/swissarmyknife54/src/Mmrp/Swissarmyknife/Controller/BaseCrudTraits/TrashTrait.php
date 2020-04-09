<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 12:05
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;


use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Lib\Log;

trait TrashTrait
{
    /**
     * Used to enable/disable trash() method
     * @var bool
     */
    protected $trash = TRUE;

    /**
     * Return Trash View
     * @param Request $request
     * @return View
     */
    public function trash(Request $request)
    {
        if(!$this->trash){
            abort(501);
        }

        try {
            $this->model = $this->filterAndOrder($request, $this->model);
            $this->model = $this->addFilter($request,$this->model);
            $this->model = $this->model->orderBy('id')->onlyTrashed();

            if($request->input('per_page') == 'all'){
                $this->model = $this->model->get();
            } else {
                $this->model = $this->model->paginate(($request->input('per_page')) ? $request->input('per_page') : 25);
                $this->model->appends($request->input())->links();
            }

            $this->prepareTrash($request);

            Log::info(new \Exception('trash', 200), $request,
                [
                    'action' => 'trash',
                    'resource' => $this->resource,
                ]
            );
            return view(preg_match('/index/',$this->views_folder) ? $this->views_folder : $this->views_folder . '.index')
                ->with('trash', TRUE)
                ->with('action',$this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('fields', $this->fields)
                ->with('translate_fields', $this->translate_fields)
                ->with('fields_types', $this->fields_types)
                ->with('data', $this->model)
                ->with('additional_data',$this->additional_data)
                ->with('available_action', $this->prepareAction())
                ->with('breadcrumbs',$this->breadcrumbs);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'trash',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Called by child class, prepare @trash environment
     * @param Request $request
     */
    protected function prepareTrash(Request $request)
    {

    }

}