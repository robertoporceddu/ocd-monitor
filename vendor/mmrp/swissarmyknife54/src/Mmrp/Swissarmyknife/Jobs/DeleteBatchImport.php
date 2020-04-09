<?php

namespace Mmrp\Swissarmyknife\Lib\Jobs;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class DeleteBatchImport implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

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
        $resource = $this->toJob->resource;
        $model = $this->toJob->model;
        $batch_import_model = $this->toJob->batch_import_model;
        $batch_import_log_id = $this->toJob->batch_import_log_id;

        DB::transaction(function() use($model, $batch_import_model, $batch_import_log_id){
            $batch_import = $batch_import_model->findOrFail($batch_import_log_id);

            $model->where('batch_import_id',$batch_import->id)->forceDelete();

            $batch_import->forceDelete();
        });

        $this->createNotification('completed',$resource);
    }

    public function failed(\Exception $exception)
    {
        //notify error
        $this->createNotification('error', $this->toJob->resource);
    }

    private function createNotification($type,$resource)
    {
        $notification = new Notification();
        $notification->message = $resource . ' ' . trans('notification.batch_import.deleted.' . $type);
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
}
