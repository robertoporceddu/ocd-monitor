<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->home();
        $this->rbac();
        $this->log();
        $this->notification();
        $this->account();
        $this->matchSettings();
        $this->pbxQueueMiddlewareSettings();
        $this->pbxQueueMiddlewareLogs();
        $this->pbxMiddleware();
        $this->peanutCampaignQueueSettings();
    }

    private function flushPermissions($controllers, $actions) {
        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                if(!DB::table('permissions')->where('slug',normalize_name($controller . '@' . $action))->count()) {
                    DB::table('permissions')->insert([
                        'name' => $controller . '@' . $action,
                        'slug' => normalize_name($controller . '@' . $action),
                        'description' => '',
                        'model' => $controller . '@' . $action,
                        'created_at' => \Carbon\Carbon::now()
                    ]);
                    echo "Adding $controller@$action Permission\n";
                }
            }
        }
    }

    private function home()
    {
        $this->flushPermissions(['HomeController'], ['index']);
    }

    private function rbac()
    {
        $actions = ['index','search','async','insert','get','edit','delete','destroy','restore','download','trash','save'];
        $controllers = [
            'Management\User\UserController',
            'Management\User\RoleController',
            'Management\User\PermissionController',
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function log()
    {
        $actions = ['index','search','async','get','delete','destroy','restore','download','trash'];
        $controllers = [
            'Management\LogController',
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function notification(){
        $actions = ['index','search','async','get','delete','destroy','restore','download','trash','toRead','viewed'];
        $controllers = [
            'NotificationController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function account()
    {
        $actions = ['get','edit','save'];
        $controllers = [
            'AccountController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function matchSettings()
    {
        $actions = ['index','search','async','insert','get','edit','delete','destroy','restore','download','trash','save'];
        $controllers = [
            'PredictiveMatchSettingsController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function pbxQueueMiddlewareSettings()
    {
        $actions = ['index','search','async','insert','get','edit','delete','destroy','restore','download','trash','save'];
        $controllers = [
            'PbxQueueMiddlewareSettingsController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function pbxQueueMiddlewareLogs()
    {
        $actions = ['index','search','get','delete','download'];
        $controllers = [
            'PbxQueueMiddlewareLogsController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function pbxMiddleware()
    {
        $actions = ['getQueueNumber', 'pushCall'];
        $controllers = [
            'PbxQueueMiddlewareController'
        ];

        $this->flushPermissions($controllers, $actions);
    }

    private function peanutCampaignQueueSettings()
    {
        $actions = ['index','search','async','insert','get','edit','delete','destroy','restore','download','trash','save'];
        $controllers = [
            'PeanutCampaignQueueSettingsController'
        ];

        $this->flushPermissions($controllers, $actions);
    }
}
