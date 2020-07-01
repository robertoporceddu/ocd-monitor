<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeanutField13VsPbxQueueNumberSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peanut_field13_vs_pbx_queue_number_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pbx_queue_number');
            $table->string('crm_peanut_field_13');
            $table->timestamps();

            $table->index(['crm_peanut_field_13']);
            $table->unique(['pbx_queue_number', 'crm_peanut_field_13']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peanut_field13_vs_pbx_queue_number_settings');
    }
}
