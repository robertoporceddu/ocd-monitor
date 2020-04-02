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

trait AsyncTrait
{
    /**
     * Return list of retrieved objects
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function async(Request $request)
    {
        try {
            $this->prepareAsync($request);
            $this->model = $this->addFilter($request,$this->model);
            $this->model = $this->model->paginate(($request->input('per_page')) ? $request->input('per_page') : 25);

            Log::info(new \Exception('getAsync'.ucfirst($this->resource), 200), $request,
                [
                    'action' => 'getAsync',
                    'resource' => $this->resource,
                ]
            );

            $empty = new \stdClass();
            $empty->id = "null";
            $empty->text = '--';
            $this->model->push($empty);

            return response()->json($this->model);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'getAsync',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Called by child class, prepare @async environment
     * @param Request $request
     */
    protected function prepareAsync(Request $request)
    {
        $this->model = $this->model->select('id', 'name as text');
        $this->model = $this->filterAndOrder($request, $this->model);
        $this->model = $this->model->orderBy('name');
    }
}