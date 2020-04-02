<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 12/01/17
 * Time: 09:39
 */

namespace Mmrp\Swissarmyknife\Lib\BatchImport;

use App\Lib\Log;
use App\Models\File;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class UploadFilesTrait
 * @package App\Lib\BatchImport
 */
trait UploadFilesTrait
{
    /**
     * @var Model
     */
    protected $files = NULL;
    /**
     * @var array
     */
    protected $files_validation_rules = NULL;
    /**
     * @var string
     */
    protected $files_trait_resource = NULL;

    /**
     * Initialize UploadFilesTrait environment
     */
    public function initFileTrait()
    {
        $this->trait_resource = 'upload_file';
        $this->files = new File();
        $this->files_validation_rules = [
            'name' => ['string'],
            'description' => ['string'],
            'file' => ['file','required'],
        ];
    }

    /**
     * Create upload form
     * @param Request $request
     * @param null $file_id
     * @return View
     */
    public function formUpload(Request $request, $file_id = NULL)
    {
        $file_id = $request->route()->getParameter('file_id');
        try {
            if($file_id){
                $this->files = $this->files->findOrFail($file_id);
            }

            $this->prepareFormUpload($request, $file_id);

            return view('batch_import.wizard')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('subtitle', '<i class="fa fa-fw fa-cloud-upload"></i> ' . trans('batch_import.wizard'))
                ->with('active_board','upload')
                ->with('file',$this->files)
                ->with('breadcrumbs',
                    array_merge($this->breadcrumbs,
                        [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                    )
                );
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'formUpload',
                    'resource' => $this->files_trait_resource,
                ]
            );
        }
    }

    /**
     * Save upload file
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $input = $request->all();

        try {

            $this->validate($request, $this->files_validation_rules);
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'postUpload - Validation',
                    'resource' => $this->files_trait_resource,
                ]
            );

            return redirect()->back();
        }

        $this->prepareUpload($request);

        try{
            $input['name'] = ($input['name']) ? $input['name']  : $request->file('file')->getClientOriginalName();
            $file = $request->file('file')->move(storage_path() . '/excel/', $request->file('file')->getClientOriginalName());
            $input['mime'] = $file->getMimeType();
            $input['path'] = storage_path() . '/excel/' . $request->file('file')->getClientOriginalName();
            $input['parameters'] = json_encode([
                'header' => ($request->input('header')) ? TRUE : FALSE,
                'delimiter' => ($request->input('delimiter')) ? $request->input('delimiter') : FALSE
            ]);

            $this->files = $this->files->create($input);

            return redirect()->action($this->action . '@content',array_merge($this->parameters,['file_id' => $this->files->id]));
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'upload - Insert to DB',
                    'resource' => $this->files_trait_resource,
                ]
            );
        }
    }


    /**
     * Called by child class, prepare @formUpload environment
     * @param Request $request
     * @param null $file_id
     */
    protected function prepareFormUpload(Request $request, $file_id = NULL)
    {

    }

    /**
     * Called by child class, prepare @prepareUpload environment
     * @param Request $request
     */
    protected function prepareUpload(Request $request)
    {

    }
}


