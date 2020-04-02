<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mmrp\Swissarmyknife\Controller\BaseCrudController;

class NotificationController extends BaseCrudController
{
    protected $action = NULL;
    protected $resource = NULL;
    protected $views_folder = NULL;
    protected $title = NULL;
    protected $model = NULL;

    protected $validation_rules = NULL;
    protected $breadcrumbs = NULL;

    public function __construct(Request $request)
    {
        $this->action = 'NotificationController';
        $this->resource = 'notification';
        $this->views_folder = 'crud';
        $this->model = new Notification();
        $this->breadcrumbs = [];
        $this->title = '';
        $this->insert = FALSE;
        $this->edit = FALSE;
        $this->save = FALSE;

        parent::__construct($request);
    }

    protected function prepareIndex(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-envelope-o"></i> ' . trans('notification.notifications');
        $this->fields = ['type','message','from','notify_at','opened_at'];
        $this->breadcrumbs = [
            ['link' => '#', 'title' => trans('notification.notifications'), 'active' => TRUE]
        ];

    }

    protected function prepareGet(Request $request, $id)
    {

        $this->model = $this->model->findOrFail($id);

        $this->title = '<i class="fa fa-fw fa-tag"></i> ' . trans('notification.notification') . ': #' . $this->model->id;

        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('notification.notification')],
            ['link' => '#', 'title' => '#' . $this->model->id, 'active' => TRUE],
        ];
    }

    protected function prepareTrash(Request $request)
    {
        $this->title = '<i class="fa fa-fw fa-trash"></i> ' . trans('messages.trash') . ' ' . trans('notification.notifications');
        $this->breadcrumbs = [
            ['link' => action($this->action . '@index'), 'title' => trans('front_office_result_code.front_office_result_codes')],
            ['link' => '#', 'title' => trans('messages.trash'), 'active' => TRUE],
        ];
    }


    public function toRead(Request $request)
    {
        $this->model = $this->model->where('to', Auth::user()->email)
                ->where('notify_at', '<=', Carbon::now())
                ->whereNull('opened_at')
                ->orderBy('created_at','desc');

        $this->model = $this->model->get();

        $return  = [];
        foreach ($this->model as $notification) {
            $return[] = [
                'id' => $notification->id,
                'from' => (strlen($notification->from) > 21 ) ? substr($notification->from,0,22) . '...' : $notification->from,
                'from_img' => ($notification->from == 'Prezzogiusto') ? asset('/media/ico_fox.png') : $notification->userFrom->profile_image,
                'type' => $notification->type,
                'message' => $notification->message,
                'created' => $notification->created_at . '<br />(' . Carbon::createFromFormat(dateFormat('datetime'), $notification->created_at)->diffForHumans(Carbon::now(), TRUE) . ')'
            ];
        }

        $this->model = new Notification();
        $this->model = $this->model->where('to', Auth::user()->email)
            ->where('notify_at', '<=', Carbon::now())
            ->whereNull('opened_at')
            ->whereNull('chrome_notification_at');

        $chrome_notification = $this->model->count();
        $this->model->update([
                'chrome_notification_at' => Carbon::now()
            ]);

        return response()->json([
            'notifications' => $return,
            'chrome_notifications' => $chrome_notification
        ]);
    }

    public function viewed(Request $request, $id)
    {
        switch ($id){
            case 'all':
                $this->model = $this->model->whereNull('opened_at');
                break;

            default:
                $this->model = $this->model->where('id', $id);
        }

        $this->model->update([
            'opened_at' => Carbon::now()
        ]);
    }

    protected function addFilter(Request $request, $resource)
    {
        return $this->model->where('to',Auth::user()->email);
    }
}
