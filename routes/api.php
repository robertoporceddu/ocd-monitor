<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$this->group(['middleware' => ['api-token','permissions']], function () {
    // $this->group(['prefix' => 'peanut'], function () {
    //     $this->post('open-customer-data', 'PeanutOpenPopupController@apiOpenCustomerData');
    //     $this->get('get-by-extension', 'PeanutOpenPopupController@apiGetCustomerDataByExtension');
    // });
    $this->group(['prefix' => 'pbx'], function () {
        $this->group(['prefix' => 'middleware'], function () {
            $this->group(['prefix' => 'queue'], function () {
                $this->get('get-number', 'PbxQueueMiddlewareController@getQueueNumber');
                $this->post('push-call', 'PbxQueueMiddlewareController@pushCall');
            });
        });
    });
});

