<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 11:43
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;

use Illuminate\Http\Request;
use Mmrp\Swissarmyknife\Lib\Log;

trait SearchTrait
{
    /**
     * Used to enable/disable search() method
     * @var bool
     */
    protected $search = FALSE;

    /**
     * Return Search View
     * @param Request $request
     * @return View
     */
    public function search(Request $request)
    {
        if(!$this->search){
            abort(501);
        }

        $this->insert = FALSE;
        $this->edit = FALSE;
        $this->trash = FALSE;
        $this->delete = FALSE;
        $this->destroy = FALSE;

        $needle = $request->input('q');

        try {
            foreach ($this->fields as $field){
                $this->model = $this->model->orWhere($field,'like','%' . $needle .'%');
            }

            $this->model = $this->addFilter($request,$this->model);
            $this->model = $this->model->paginate(($request->input('per_page')) ? $request->input('per_page') : 25);

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
                ->with('breadcrumbs',$this->breadcrumbs)
                ->with('available_action', $this->prepareAction())
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




}