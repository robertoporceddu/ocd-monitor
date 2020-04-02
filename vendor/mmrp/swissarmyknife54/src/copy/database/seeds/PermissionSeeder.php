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
        DB::table('permissions')->insert([
            'name' => 'HomeController@index',
            'slug' => normalize_name('HomeController@index'),
            'description' => '',
            'model' => 'HomeController@index',
            'created_at' => \Carbon\Carbon::now()
        ]);

        $this->rbac();
        $this->log();
        $this->notification();
        $this->account();
    }

    private function rbac()
    {
        $permissions = [];
        $actions = ['index','search','async','insert','get','edit','delete','destroy','restore','download','trash','save'];
        $controllers = [
            'Management\User\UserController',
            'Management\User\RoleController',
            'Management\User\PermissionController',
        ];

        foreach ($controllers as $controller){
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => $controller . '@' . $action,
                    'slug' => normalize_name($controller . '@' . $action),
                    'description' => '',
                    'model' => $controller . '@' . $action,
                    'created_at' => \Carbon\Carbon::now()
                ];
            }
        }

        DB::table('permissions')->insert($permissions);

        DB::table('permissions')->insert([
            'name' => 'Management\User\UserController@attachPermission',
            'slug' => normalize_name('Management\User\UserController@attachPermission'),
            'description' => '',
            'model' => 'Management\User\UserController@attachPermission',
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'Management\User\UserController@detachPermission',
            'slug' => normalize_name('Management\User\UserController@detachPermission'),
            'description' => '',
            'model' => 'Management\User\UserController@detachPermission',
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'Management\User\RoleController@attachPermission',
            'slug' => normalize_name('Management\User\RoleController@attachPermission'),
            'description' => '',
            'model' => 'Management\User\RoleController@attachPermission',
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'Management\User\RoleController@detachPermission',
            'slug' => normalize_name('Management\User\RoleController@detachPermission'),
            'description' => '',
            'model' => 'Management\User\RoleController@detachPermission',
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'Management\User\RoleController@attachUser',
            'slug' => normalize_name('Management\User\RoleController@attachUser'),
            'description' => '',
            'model' => 'Management\User\RoleController@attachUser',
            'created_at' => \Carbon\Carbon::now()
        ]);

        DB::table('permissions')->insert([
            'name' => 'Management\User\RoleController@detachUser',
            'slug' => normalize_name('Management\User\RoleController@detachUser'),
            'description' => '',
            'model' => 'Management\User\RoleController@detachUser',
            'created_at' => \Carbon\Carbon::now()
        ]);
    }

    private function log()
    {
        $permissions = [];
        $actions = ['index','search','async','get','delete','destroy','restore','download','trash'];
        $controllers = [
            'Management\LogController',
        ];

        foreach ($controllers as $controller){
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => $controller . '@' . $action,
                    'slug' => normalize_name($controller . '@' . $action),
                    'description' => '',
                    'model' => $controller . '@' . $action,
                    'created_at' => \Carbon\Carbon::now()
                ];
            }
        }

        DB::table('permissions')->insert($permissions);

    }

    private function notification(){
        $permissions = [];
        $actions = ['index','search','async','get','delete','destroy','restore','download','trash','toRead','viewed'];
        $controllers = [
            'NotificationController'
        ];

        foreach ($controllers as $controller){
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => $controller . '@' . $action,
                    'slug' => normalize_name($controller . '@' . $action),
                    'description' => '',
                    'model' => $controller . '@' . $action,
                    'created_at' => \Carbon\Carbon::now()
                ];
            }
        }

        DB::table('permissions')->insert($permissions);
    }

    private function account()
    {
        $permissions = [];
        $actions = ['get','edit','save'];
        $controllers = [
            'AccountController'
        ];

        foreach ($controllers as $controller){
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => $controller . '@' . $action,
                    'slug' => normalize_name($controller . '@' . $action),
                    'description' => '',
                    'model' => $controller . '@' . $action,
                    'created_at' => \Carbon\Carbon::now()
                ];
            }
        }

        DB::table('permissions')->insert($permissions);
    }
}
