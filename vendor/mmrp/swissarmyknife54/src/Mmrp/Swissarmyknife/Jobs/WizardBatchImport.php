<?php

namespace Mmrp\Swissarmyknife\Lib\Jobs;

use Mmrp\Swissarmyknife\Lib\BatchImport\ExcelTrait;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class WizardBatchImport implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, ExcelTrait;

    protected $toJob = NULL;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($toJob)
    {
        $this->toJob = $toJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //update Resource BatchImportLog Status
        $this->batchImportLog($this->toJob->batch_import_log_id,$this->toJob->batch_import_model,'importing');

        $inserted_rows = 0;

        /** show App\Lib\BatchImport\BatchImportController@prepareToJob */
        list($resource, $model, $batch_import_model, $batch_import_log_id, $file, $mapping, $to) = $this->parseToJobObject();

        $inputs = $this->prepareInsertsFromExcelFile($file, $mapping, $batch_import_log_id);

        $inserted_rows = DB::transaction(function () use ($inputs, $model){
            $inserted = 0;
//            $chunks = array_chunk($inputs,100);
//
//            foreach ($chunks as $chunk){
//                $model->insert($chunk);
//                $inserted = $inserted + count($chunk);
//            }
            foreach ($inputs as $input) {
                if(!empty($input['id'])){
                    $model->where('id',$input['id'])->update($input);
                } else {
                    $model->insert($input);
                }

                $inserted = $inserted + 1;
            }
            return $inserted;
        });

        //notify success
        $this->createNotification('completed',$resource);

        //update Resource BatchImportLog Status
        $this->batchImportLog($batch_import_log_id, $batch_import_model,'completed',NULL, count($inputs), $inserted_rows);
    }

    public function failed(\Exception $exception)
    {
        /** show App\Lib\BatchImport\BatchImportController@prepareToJob */
        list($resource, $model, $batch_import_model, $batch_import_log_id, $file, $mapping) = $this->parseToJobObject();

        //notify error
        $this->createNotification('error',$resource);

        //update Resource BatchImportLog Status
        $this->batchImportLog($this->toJob->batch_import_log_id,$this->toJob->batch_import_model,'error', $exception);
    }

    private function batchImportLog($batch_import_log_id, $batch_import_model, $status, \Exception $exception = NULL, $rows_count = NULL, $rows_inserted = NULL)
    {
        $batch_import_log = $batch_import_model;
        $batch_import_log = $batch_import_log->findOrFail($batch_import_log_id);
        $batch_import_log->status = $status;
        $batch_import_log->completed_at = Carbon::now();

        if($rows_count){
            $batch_import_log->rows_count = $rows_count;
        }

        if($rows_inserted){
            $batch_import_log->rows_inserted = $rows_inserted;
         }

        if($status == 'error'){
            $batch_import_log->note = $exception->getMessage();
        }

        $batch_import_log->save();
    }

    private function createNotification($type,$resource)
    {
        $notification = new Notification();
        $notification->message = $this->toJob->files->name . '<br />' . trans('notification.' . $resource . '.' . $type);
        $notification->to = $this->toJob->to;
        $notification->notify_at = Carbon::now();

        switch ($type){
            case 'completed':
                $notification->insertSystemInfoNotification();
                break;
            case 'error':
                $notification->insertSystemErrorNotification();
                break;
        }
    }

    private function parseToJobObject()
    {
        return [
            $this->toJob->resource,
            $this->toJob->model,
            $this->toJob->batch_import_model,
            $this->toJob->batch_import_log_id,
            $this->toJob->files,
            $this->toJob->mapping,
            $this->toJob->to,
        ];
    }
}
