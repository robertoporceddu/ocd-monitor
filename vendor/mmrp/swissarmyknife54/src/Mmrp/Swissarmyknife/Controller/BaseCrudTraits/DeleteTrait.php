<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 12:02
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mmrp\Swissarmyknife\Lib\Log;

trait DeleteTrait
{
    /**
     * Used to enable/disable delete() method
     * @var bool
     */
    protected $delete = TRUE;

    /**
     * Delete the specified line
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        if(!$this->delete){
            abort(501);
        }

        $id = $request->route()->parameter('id');

        if($id == 'multiple' and empty($request->input('rows_id'))){
            return redirect()->back();
        }

        try{
            $this->beforeDelete($request, $id);
            if($id == 'multiple'){
                $this->model->whereIn('id',$request->input('rows_id'))->delete();
            } else {
                $this->model->where('id',$id)->first()->delete();
            }

            $this->afterDelete($request, $id);

            Session::flash('flash_message', trans('messages.edit.deleted'));

            if(!is_null($this->redirect_to)){
                return redirect()->to($this->redirect_to);
            } else {
                return redirect()->action($this->action . '@index', $this->parameters);
            }
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'delete',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }

    /**
     * Called by child class, execute your code before $this->model->delete()
     * @param Request $request
     * @param $id
     */
    protected function beforeDelete(Request $request, $id)
    {

    }

    /**
     * Called by child class, executed after $this->model->delete()
     * @param Request $request
     * @param $id
     */
    protected function afterDelete(Request $request, $id)
    {

    }
}