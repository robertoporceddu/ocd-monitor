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

trait RestoreTrait
{
    /**
     * Used to enable/disable restore() method
     * @var bool
     */
    protected $restore = TRUE;

    /**
     * Restore the specified line
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {
        if(!$this->restore){
            abort(501);
        }

        $id = $request->route()->parameter('id');

        if($id == 'multiple' and empty($request->input('rows_id'))){
            return redirect()->back();
        }

        try {
            $this->beforeRestore($request, $id);

            if($id == 'multiple'){
                $this->model->withTrashed()->whereIn('id',$request->input('rows_id'))->restore();
            } else {
                $this->model->withTrashed()->where('id', $id)->restore();
            }

            $this->afterRestore($request, $id);

            Session::flash('flash_message', trans('messages.edit.restored'));

            if(!is_null($this->redirect_to)){
                return redirect()->to($this->redirect_to);
            } else {
                return redirect()->action($this->action . '@trash', $this->parameters);
            }

        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'restore',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }

    /**
     * Called by child class, executed before $this->model->restore()
     * @param Request $request
     * @param $id
     */
    protected function beforeRestore(Request $request, $id)
    {

    }

    /**
     * Called by child class, executed before $this->model->restore()
     * @param Request $request
     * @param $id
     */
    protected function afterRestore(Request $request, $id)
    {

    }
}