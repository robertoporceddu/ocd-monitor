<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 13/06/17
 * Time: 11:45
 */

namespace Mmrp\Swissarmyknife\Controller\BaseCrudTraits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mmrp\Swissarmyknife\Lib\Log;

trait EditTrait
{
    /**
     * Used to enable/disable insert() method
     * @var bool
     */
    protected $insert = TRUE;

    /**
     * Used to enable/disable edit() method
     * @var bool
     */
    protected $edit = TRUE;

    /**
     * Used to enable/disable save() method
     * @var bool
     */
    protected $save = TRUE;

    /**
     * Return Insert Form View
     * @param Request $request
     * @return View
     */
    public function insert(Request $request)
    {
        if(!$this->insert){
            abort(501);
        }

        try {
            $this->prepareInsert($request);

            return view(preg_match('/edit/',$this->views_folder) ? $this->views_folder : $this->views_folder . '.edit')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('fields', $this->fields)
                ->with('fields_types', $this->fields_types)
                ->with('translate_fields', $this->translate_fields)
                ->with('available_action', $this->prepareAction())
                ->with('additional_data',$this->additional_data)
                ->with('breadcrumbs', $this->breadcrumbs);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'insert',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Return Edit Form View
     * @param Request $request
     * @param $id
     * @return View
     */
    public function edit(Request $request, $id )
    {
        if(!$this->edit){
            abort(501);
        }

        $id = $request->route()->parameter('id');

        if($id == 'multiple' or (Session::get('rows_id'))){
            $rows_id = (Session::get('rows_id')) ? Session::get('rows_id') : $request->input('rows_id');
            $id = array_shift($rows_id);
            Session::put('rows_id',$rows_id);
        }

        try {
            $this->model = $this->model->findOrFail($id);

            $this->prepareEdit($request, $id);

            Log::info(new \Exception('edit', 200), $request, [
                    'action' => 'edit',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );

            return view(preg_match('/edit/',$this->views_folder) ? $this->views_folder : $this->views_folder . '.edit')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('fields', $this->fields)
                ->with('fields_types', $this->fields_types)
                ->with('translate_fields', $this->translate_fields)
                ->with('data', $this->model)
                ->with('additional_data',$this->additional_data)
                ->with('available_action', $this->prepareAction())
                ->with('breadcrumbs', $this->breadcrumbs);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'getEdit',
                    'resource' => $this->resource,
                    'resource_id' => $id
                ]
            );
        }
    }


    /**
     * Creates a new row or Fill specified row
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, $id = NULL)
    {
        $id = $request->route()->parameter('id');

        if(!$this->save){
            abort(501);
        }

        $this->input = $request->except('_token');

        $request->replace($this->input);

        try {
            if($id != 'multiple') {
                $this->validate($request, $this->validation_rules);
            }
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'save',
                    'resource' => $this->resource,
                ]
            );

            return redirect()->back();
        }

        foreach ($this->input as $key => $value) {
            if($value == 'null'){
                $this->input[$key] = NULL;
            }
        }

        $this->beforeSave($request, $id);

        try {

            if($id == 'multiple') {
                $this->_multiple_save($this->input['rows_id']);
            } else {
                $this->_save($id);
            }

            $this->afterSave($request, $id);

            Session::flash('flash_message', ($id) ? trans('messages.edit.updated') : trans('messages.edit.inserted'));

            Log::info(new \Exception(($id) ? trans('messages.edit.updated') : trans('messages.edit.inserted'), 200), $request,
                [
                    'action' => 'save',
                    'resource' => $this->resource,
                    'resource_id' => ($id) ? $id : $this->model->id
                ]
            );


            if(!is_null($this->redirect_to)){
                return redirect()->to($this->redirect_to);
            }
            else if(Session::get('rows_id')){
                return redirect()->action($this->action . '@edit', array_merge($this->parameters, [ 'id' => 'multiple']));
            }
            else {
                return redirect()->action($this->action . '@get', array_merge($this->parameters, ['id' => $this->model->id]));
            }

        }
        catch (\Exception $e) {
            Session::flash('flash_message', ($id) ? trans('messages.edit.failed') : trans('messages.edit.failed'));

            Log::info($e, $request, [
                    'action' => 'save',
                    'resource' => $this->resource,
                ]
            );

            return redirect()->back();
        }
    }



    /**
     * Save/Fill Model Object with $this->input values
     * @param $id
     */
    protected function _save($id)
    {
        if ($id) {
            $this->model = $this->model->findOrFail($id);
            $this->model->forceFill($this->input)->save();
        } else {
            foreach ($this->input as $field => $value){
                $this->model->$field = $value;
            }

            $this->model->save();
        }
    }

    protected function _multiple_save($rows_id)
    {
        unset($this->input['rows_id']);
        foreach ($this->input as $field => $value){
            if(empty($value)){
                unset($this->input[$field]);
            }
        }
        $this->model->whereIn('id',$rows_id)->update($this->input);
        $this->redirect_to = action($this->action . '@index', $this->parameters);

    }

    /**
     * Called by child class, prepare @insert environment
     * @param Request $request
     */
    protected function prepareInsert(Request $request)
    {

    }

    /**
     * Called by child class, prepare @edit environment
     * @param Request $request
     * @param $id
     */
    protected function prepareEdit(Request $request, $id)
    {

    }

    /**
     * Called by child class, prepare @save environment
     * @param Request $request
     * @param null $id
     */
    protected function beforeSave(Request $request, $id = NULL)
    {

    }

    /**
     * Called by child class, execute operation after save
     * @param Request $request
     * @param null $id
     */
    protected function afterSave(Request $request, $id = NULL)
    {

    }
}