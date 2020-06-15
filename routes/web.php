<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 12/04/17
 * Time: 15:13
 */



/*
$this->get('/cpu', 'ServerController@home');
$this->get('/c', function(){
    return exec('top -b -n1 | grep \'Cpu(s):\' | awk \'{print $2}\'');
});
$this->get('/ram', function(){
    return shell_exec('free -tm | awk \'{if ($1 == "Mem:") print ($3*100)/$2}\'');
});
$this->get('/swap', function(){
    return shell_exec('free -tm | awk \'{if ($1 == "Swap:") print ($3*100)/$2}\'');
});
$this->get('/reload-log', function() {
    chdir('/var/log/');
    $logFile = file('octopus-dialer.log');
    $logCollection = [];
    // Loop through an array, show HTML source as HTML source; and line numbers too.
    foreach ($logFile as $line_num => $line) {
        $logCollection[] = array('line'=> $line_num, 'content'=> htmlspecialchars($line));
    }
    return $logCollection;
});

$this->get('/reload-reboot', 'ServerController@serverReboot');
$this->get('/generale', function(){
    return view('layouts/generale');
});
$this->get('/extensionshome','Extension@home');
$this->get('/extensions_group', function(){
    return App\Extension_status_log::all()->groupBy('schema');

});
$this->get('/extensions', function(){
    return App\Extension_status_log::all()->where('is_last', TRUE);

});

$this->get('/queued', function(){
    return App\Extension_status_log::where('action', 'queued')->orderBy('created_at', 'desc')->get();
});


$this->get('/work', function(){
    return App\Extension_status_log::where('action', 'work')->orderBy('created_at', 'desc')->get();
});

$this->get('/paused', function(){
    return App\Extension_status_log::where('action', 'paused')->orderBy('created_at', 'desc')->get();
});


$this->get('/availablegenerale',function(){
    return view('layouts/available_extensions');
});


$this->get('/available', function(){
    return App\Available_extension::all();

});


$this->get('/dial_5', function(){
    return view('layouts/dial_status_log_5');
});

$this->get('/dial_status_log_5', function(){
    return App\Dial_status_log_5::all()->groupBy('schema');
});

$this->get('/campaigns', function(){
    return App\Dial_status_log_5::all();
});

$this->get('/drag', function(){
    return view('layouts/drag');
});
*/

// Authentication Routes...
//$this->get('login', 'Auth\LoginController@showLoginForm');
$this->get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);

$this->get('logout', 'Auth\LoginController@logout');
$this->post('login', 'Auth\LoginController@login');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->get('password/reset/{token?}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

$this->group(['middleware' => ['auth', 'permissions']], function () {


    $this->group(['prefix' => '/account'], function() {
        createCrudRoute($this, 'AccountController', NULL);
        $this->get('{id}/change-password', 'AccountController@changePassword');
        $this->post('{id}/change-password/save', 'AccountController@postChangePassword');
    });

    $this->group(['middleware' => ['check-password']], function() {
        $this->get('/', 'HomeController@index');

        $this->group(['prefix' => 'predictive-match-settings'], function () {
            createCrudRoute($this, 'PredictiveMatchSettingsController', 'predictive-match-settings');
        });

        $this->group(['prefix' => 'peanut-campaign-queue-settings'], function () {
            createCrudRoute($this, 'PeanutCampaignQueueSettingsController', 'peanut-campaign-queue-settings');
        });

        $this->group(['prefix' => 'pbx-queue-middleware-settings'], function () {
            createCrudRoute($this, 'PbxQueueMiddlewareSettingsController', 'pbx-queue-middleware-settings');
        });

        $this->group(['prefix' => 'notifications'], function () {
            createCrudRoute($this, 'NotificationController', 'notification');
            $this->get('/to-read', 'NotificationController@toRead');
            $this->post('/{id}/viewed', 'NotificationController@viewed');

        });

        $this->group(['prefix' => '/account'], function () {
            createCrudRoute($this, 'AccountController', NULL);
        });

        $this->group(['namespace' => 'Management', 'prefix' => 'management'], function () {

            $this->group(['prefix' => 'logs'], function () {
                createCrudRoute($this, 'LogController', 'log');
            });

            $this->group(['namespace' => 'User'], function () {

                $this->group(['prefix' => 'users'], function () {
                    createCrudRoute($this, 'UserController', 'user');
                });

                $this->group(['prefix' => 'roles'], function () {
                    createCrudRoute($this, 'RoleController', 'role');
                    $this->group(['prefix' => 'role'], function (){
                        $this->get('{id}/attach-permission/{permission_id}','RoleController@attachPermission');
                        $this->get('{id}/detach-permission/{permission_id}','RoleController@detachPermission');

                    });
                });

                $this->group(['prefix' => 'permissions'], function () {
                    createCrudRoute($this, 'PermissionController', 'permission');
                });
            });

        });
    });

});
