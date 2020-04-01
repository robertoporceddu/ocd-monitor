<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDialStatusLog5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dial_status_log_5', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schema');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->integer('busy');
            $table->integer('failed');
            $table->integer('noanswer');
            $table->integer('chanunavail');
            $table->integer('answer');
            $table->integer('drop');
            $table->integer('undefined');
            $table->integer('total');
            $table->integer('subscriber_absent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dial_status_log_5');
    }
}
