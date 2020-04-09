<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 12:03
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mmrp\Swissarmyknife\Lib\Log;

trait DestroyTrait
{
    /**
     * Used to enable/disable destroy() method
     * @var bool
     */
    protected $destroy = TRUE;

    /**
     * Destroy the specified line
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if(!$this->destroy){
            abort(501);
        }

        $id = $request->route()->parameter('id');

        if($id == 'multiple' and empty($request->input('rows_id'))){
            return redirect()->back();
        }

        try {
            $this->beforeDestroy($request, $id);

            if($id == 'multiple'){
                $this->model->withTrashed()->whereIn('id',$request->input('rows_id'))->forceDelete();
            } else {
                $this->model->withTrashed()->where('id', $id)->forceDelete();
            }

            $this->afterDestroy($request,$id);

            Session::flash('flash_message', trans('messages.edit.destroyed'));

            if(!is_null($this->redirect_to)){
                return redirect()->to($this->redirect_to);
            } else {
                return redirect()->action($this->action . '@trash', $this->parameters);
            }

        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'destroy',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }

    /**
     * Called by child class, executed before $this->model->destroy()
     * @param Request $request
     * @param $id
     */
    protected function beforeDestroy(Request $request, $id)
    {

    }

    /**
     * Called by child class, executed before $this->model->destroy()
     * @param Request $request
     * @param $id
     */
    protected function afterDestroy(Request $request, $id)
    {

    }

}