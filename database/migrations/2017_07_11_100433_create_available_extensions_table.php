<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_extensions', function (Blueprint $table) {
            $table->increments('available_extension_id');
            $table->text('extension');
            $table->text('schema');
            $table->text('buyer');
            $table->dateTime('created_at');
            $table->dateTime('queued_at')->nullable();
            $table->dateTime('served_at')->nullable();
            $table->integer('served_with_system_customer_data_id')->nullable();
            $table->dateTime('polled_at');
            $table->string('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_extensions');
    }
}
