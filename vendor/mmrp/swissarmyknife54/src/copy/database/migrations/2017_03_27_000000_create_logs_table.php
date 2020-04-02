<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('action');
            $table->text('resource')->nullable();
            $table->text('resource_id')->nullable();
            $table->string('code');
            $table->longText('message');

            $table->integer('user_id')->nullable();
            $table->text('request')->nullable();

            $table->text('file')->nullable();
            $table->text('line')->nullable();
            $table->text('trace')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
