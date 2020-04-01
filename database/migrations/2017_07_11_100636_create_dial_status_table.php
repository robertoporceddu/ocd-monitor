<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDialStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dial_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schema');
            $table->integer('system_customer_data_id');
            $table->string('phone_cli');
            $table->string('extension')->nullable();
            $table->string('dial_status')->nullable();
            $table->integer('hangup_cause')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('dial_status');
        DB::statement('DROP TABLE dial_status CASCADE;');
    }
}
