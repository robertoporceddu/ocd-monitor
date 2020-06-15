<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePbxQueueMiddlewaresettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pbx_queue_middleware_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pbx_from_caller_id')->unique();
            $table->string('ocm_url');
            $table->string('ocm_token');
            $table->string('ocm_sap');
            $table->string('ocm_sap_fallback');
            $table->string('ocm_name');
            $table->string('ocm_surname');
            $table->string('ocm_offer');
            $table->string('crm_peanut_url');
            $table->string('crm_peanut_token');
            $table->string('type');
            $table->string('pbx_queue_number');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pbx_from_caller_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pbx_queue_middleware_settings');
    }
}
