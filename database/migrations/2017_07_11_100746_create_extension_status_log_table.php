<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_status_log', function (Blueprint $table) {
            $table->increments('extension_status_log_id');
            $table->string('extension');
            $table->string('action');
            $table->dateTime('created_at');
            $table->dateTime('last_at')->nullable();
            $table->string('username');
            $table->string('schema');
            $table->boolean('is_last');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('extension_status_logs');
        DB::statement('DROP TABLE extension_status_log CASCADE;');

    }
}
