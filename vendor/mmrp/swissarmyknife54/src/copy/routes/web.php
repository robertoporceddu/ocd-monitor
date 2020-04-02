<?php
/**
 * Created by PhpStorm.
 * User: MatteoMeloni
 * Date: 12/04/17
 * Time: 15:13
 */


$this->get('/', function () {
    return view('welcome');
});

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

$this->group(['middleware' => ['auth','permissions']], function () {
    $this->group(['prefix' => '/account'], function() {
        createCrudRoute($this, 'AccountController', NULL);
        $this->get('{id}/change-password', 'AccountController@changePassword');
        $this->post('{id}/change-password/save', 'AccountController@postChangePassword');
    });

    $this->group(['middleware' => ['check-password']], function() {
        $this->get('/', 'HomeController@index');

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
                    $this->get('{id}/attach-permission/{permission_id}','UserController@attachPermission');
                    $this->get('{id}/detach-permission/{permission_id}','UserController@detachPermission');
                });

                $this->group(['prefix' => 'roles'], function () {
                    createCrudRoute($this, 'RoleController', 'role');
                    $this->group(['prefix' => 'role'], function (){
                        $this->get('{id}/attach-permission/{permission_id}','RoleController@attachPermission');
                        $this->get('{id}/detach-permission/{permission_id}','RoleController@detachPermission');
                        $this->get('{id}/attach-user/{user_id}','RoleController@attachUser');
                        $this->get('{id}/detach-user/{user_id}','RoleController@detachUser');

                    });
                });

                $this->group(['prefix' => 'permissions'], function () {
                    createCrudRoute($this, 'PermissionController', 'permission');
                });
            });

        });
    });

});
