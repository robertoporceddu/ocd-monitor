<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbxQueueMiddlewareLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx_queue_middleware_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_method');
            $table->string('from_user');
            $table->string('from_ip');
            $table->string('callerid');
            $table->string('fromid');
            $table->string('extension')->nullable();
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pbx_queue_middleware_logs');
    }
}
