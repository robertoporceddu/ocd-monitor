<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 18/01/17
 * Time: 16:14
 */

namespace Mmrp\Swissarmyknife\Lib\BatchImport;


use App\Jobs\DeleteBatchImport;
use App\Jobs\WizardBatchImport;
use Mmrp\Swissarmyknife\Lib\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class BatchImportTrait
 * @package App\Lib\BatchImport
 */
trait BatchImportTrait
{
    use UploadFilesTrait, ExcelTrait;

    /**
     * Namaspace of child Controller (used to create the routes)
     * @var string
     */
    protected $action = NULL;

    /**
     * String that contains the name of the resource used
     * @var string
     */
    protected $resource = NULL;
    /**
     * Model on which to place the imported data
     * @var Model
     */
    protected $model = NULL;

    /**
     * Array which contains batch_model fields
     * @var array
     */
    protected $batch_fields;

    /**
     * Batch Import Log Model on which to place the imported data
     * @var Model
     */
    protected $batch_import_model = NULL;

    /**
     * Contain Batch Import Log row
     * @var Model
     */
    protected $batch_import_log = NULL;

    /**
     * @var boolean null
     */
    protected $create_columns_model = NULL;

    /**
     * Used to create the breadcrumbs
     * @var array
     */
    protected $breadcrumbs = NULL;

    /**
     * @var boolean bool
     */
    protected $add_columns = FALSE;


    /**
     * Initialize BatchImportTrait environment
     */
    public function initBatchImportTrait()
    {
        $this->batch_fields = $this->batch_import_model->getFillable();

        $this->initFileTrait();
        $this->initExcelTrait();
    }

    /**
     * Return views which contains list of all imports
     * @param Request $request
     * @return views
     */
    public function imports(Request $request)
    {
        try {
            $this->batch_import_model = $this->filterAndOrder($request, $this->batch_import_model);
            $this->batch_import_model = $this->addFilter($request,$this->batch_import_model);

            $this->batch_import_model = $this->batch_import_model->orderBy('created_at','desc');
            if($request->input('per_page') == 'all'){
                $this->batch_import_model = $this->batch_import_model->get();
            } else {
                $this->batch_import_model = $this->batch_import_model->paginate(($request->input('per_page')) ? $request->input('per_page') : 25);
            }
            $this->batch_import_model->appends($request->input())->links();

            Log::info(new \Exception('imports', 200), $request,
                [
                    'action' => 'imports',
                    'resource' => $this->resource,
                ]
            );

            $this->prepareImports($request);

            return view('batch_import.index')
                ->with('action', $this->action)
                ->with('parameters',$this->parameters)
                ->with('resource', $this->resource)
                ->with('fields', $this->batch_fields)
                ->with('fields_types', $this->fields_types)
                ->with('title', $this->title)
                ->with('subtitle', trans('batch_import.batch_import_log'))
                ->with('data', $this->batch_import_model)
                ->with('breadcrumbs',
                    array_merge($this->breadcrumbs,
                        [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                    )
                );
        }
        catch (\Exception $e) {
            Log::info($e, $request, [
                    'action' => 'index',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Show uploaded file content
     * @param Request $request
     * @param $file_id
     * @return View
     */
    public function content(Request $request, $file_id)
    {
        $file_id = $request->route()->getParameter('file_id');

        try{
            $this->files = $this->files->findOrFail($file_id);
            $data = $this->getRowsFromExcelFile($this->files,3);

            if(!is_array($data)){
                $data = $data->toArray();
            }

            $columns = array_keys($data[0]);
            $rows = $data;

            if(count($rows[0]) <=1){
                return redirect()->back()->with('warning_message',trans('notification.empty_file'));
            }
            $this->prepareContent($request);

            return view('batch_import.wizard')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('subtitle', trans('batch_import.wizard'))
                 ->with('active_board','show_content')
                ->with('columns', $columns)
                ->with('rows', $rows)
                ->with('file_id', $file_id)
                ->with('breadcrumbs',
                    array_merge($this->breadcrumbs,
                        [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                    )
                );
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'getShowContent',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Show the creation form of new columns for the target table
     * @param Request $request
     * @param $file_id
     * @return view
     */
    public function matching(Request $request, $file_id)
    {
        $file_id = $request->route()->getParameter('file_id');

        if($this->add_columns == FALSE){
            return redirect()->action($this->action . '@mapping', array_merge($this->parameters,['file_id' => $file_id]));
        }

        $this->files = $this->files->findOrFail($file_id);

        if(!json_decode($this->files->parameters)->header){
            return redirect()->action($this->action . '@mapping', array_merge($this->parameters,['file_id' => $file_id]));
        }

        $data = $this->getRowsFromExcelFile($this->files,1);

        $csv_columns = array_keys($data->toArray()[0]);
        $table_columns = $this->model->getFillable();

        $this->prepareMatching($request);

        return view('batch_import.wizard')
            ->with('action', $this->action)
            ->with('parameters', $this->parameters)
            ->with('resource', $this->resource)
            ->with('title', $this->title)
            ->with('subtitle', trans('batch_import.wizard'))
            ->with('active_board','matching')
            ->with('csv_columns', $csv_columns)
            ->with('table_columns', $table_columns)
            ->with('file_id', $file_id)
            ->with('breadcrumbs',
                array_merge($this->breadcrumbs,
                    [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                )
            );
    }

    /**
     * @param Request $request
     * @param $file_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createColumn(Request $request, $file_id)
    {
        $input = $request->all();

        try{
            //create column
            $create_columns_model = $this->create_columns_model;

            DB::transaction(function () use ($input, $create_columns_model){

                foreach ($input['columns'] as $i => $field){
                    $create_columns = $create_columns_model;
                    $model = $create_columns_model;

                    $inserted = $create_columns->insert([
                        'name' => $field,
                        'normalized_name' => normalize_name($field),
                        'type' => 'text',
                        'locked' => '0'
                    ]);

                    if($inserted){
                        $model->addColumn(normalize_name($field),'text');
                    }
                }
            });

            return redirect()->action($this->action . '@mapping', ['file_id' => $file_id]);
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
     * Show the form to map the contents of the uploaded file with the destination table
     * @param Request $request
     * @param $file_id
     * @return View
     */
    public function mapping(Request $request, $file_id)
    {
        $file_id = $request->route()->getParameter('file_id');

        try{
            $this->files = $this->files->findOrFail($file_id);

            $data = $this->getRowsFromExcelFile($this->files,1);

            $csv_columns = array_keys($data->toArray()[0]);
            $table_columns = $this->model->getFillable();

            $this->prepareMapping($request);

            return view('batch_import.wizard')
                ->with('action', $this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('subtitle', trans('batch_import.wizard'))
                ->with('active_board','mapping')
                ->with('csv_columns', $csv_columns)
                ->with('table_columns', $table_columns)
                ->with('file_id', $file_id)
                ->with('breadcrumbs',
                    array_merge($this->breadcrumbs,
                        [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                    )
                );
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'getShowMapping',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * Save the uploaded file content to the destination table
     * @param Request $request
     * @param $file_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batch(Request $request, $file_id)
    {
        $file_id = $request->route()->getParameter('file_id');

        try{
            $this->batch_import_log = $this->batchImportLog($request, $file_id);

            $this->files = $this->files->findOrFail($file_id);
            $this->dispatchJob($request);
            return redirect()->action($this->action . '@completed', array_merge($this->parameters,['file_id',$file_id]));
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'batch',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    /**
     * @param Request $request
     * @param $file_id
     * @return View
     */
    public function completed(Request $request, $file_id){
        $file_id = $request->route()->getParameter('file_id');

        try {
            $this->prepareCompleted($request);

            return view('batch_import.wizard')
                ->with('action',$this->action)
                ->with('parameters', $this->parameters)
                ->with('resource', $this->resource)
                ->with('title', $this->title)
                ->with('subtitle', trans('batch_import.wizard'))
                ->with('active_board', 'save')
                ->with('file_id', $file_id)
                ->with('breadcrumbs',
                    array_merge($this->breadcrumbs,
                        [['link' => "#", 'title' => trans('batch_import.wizard') . ' ' . trans('batch_import.upload'),'active' => TRUE]]
                    )
                );
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'completed',
                    'resource' => $this->resource,
                ]
            );
        }
    }

    public function deleteBatch(Request $request, $id)
    {
        $toJob = new \stdClass();

        $toJob->resource = $this->resource;
        $toJob->model = $this->model;
        $toJob->batch_import_model = $this->batch_import_model;
        $toJob->batch_import_log_id = $id;
        $toJob->to = Auth::user()->email;

        dispatch(new DeleteBatchImport($toJob));

        return redirect()->back();

    }

    /**
     * Starts the Job
     * @param Request $request
     */
    protected function dispatchJob(Request $request)
    {
        $toJob = $this->prepareToJob($request);

        //Start Job
        dispatch(new WizardBatchImport($toJob));
    }

    /**
     * Create a new BatchImportLog
     * @param Request $request
     * @param $file_id
     * @return object
     */
    public function batchImportLog(Request $request, $file_id)
    {
        try {
            $batch_import_log = $this->batch_import_model;
            $batch_import_log->file_id = $file_id;
            $batch_import_log->from = Auth::user()->email;
            $batch_import_log->status = 'created';


            $batch_import_log->save();
            return $batch_import_log;
        }
        catch (\Exception $e){
            Log::info($e, $request, [
                    'action' => 'postSave',
                    'resource' => $this->resource,
                ]
            );
        }
    }


    /**
     * Prepare Job Environment
     * @param Request $request
     * @return \stdClass
     */
    public function prepareToJob(Request $request)
    {
        $toJob = new \stdClass();
        $toJob->resource = $this->resource;
        $toJob->files = $this->files;
        $toJob->batch_import_log_id = $this->batch_import_model->id;
        $toJob->to = Auth::user()->email;

        $toJob->mapping['id'] = 'id'; // For massive updates
        $toJob->mapping = $request->except(['_token','unique_key']);

        $toJob->model = $this->model;
        $toJob->batch_import_model = $this->batch_import_model;

        return $toJob;
    }


    /**
     * Called by child class, prepare @imports environment
     * @param Request $request
     */
    protected function prepareImports(Request $request)
    {

    }

    /**
     * Called by child class, prepare @content environment
     * @param Request $request
     */
    protected function prepareContent(Request $request)
    {

    }

    /**
     * Called by child class, prepare @matching environment
     * @param Request $request
     */
    protected function  prepareMatching(Request $request)
    {

    }

    /**
     * Called by child class, prepare @mapping environment
     * @param Request $request
     */
    protected function  prepareMapping(Request $request)
    {

    }

    /**
     * Called by child class, prepare @completed environment
     * @param Request $request
     */
    protected function prepareCompleted(Request $request)
    {

    }
}