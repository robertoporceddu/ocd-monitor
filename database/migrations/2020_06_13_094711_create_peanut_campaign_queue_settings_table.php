<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeanutCampaignQueueSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peanut_campaign_queue_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('crm_peanut_campaign_schema')->unique();
            $table->string('pbx_queue_number');
            $table->string('ocm_url');
            $table->string('ocm_token');
            $table->string('ocm_sap_fallback');
            $table->string('ocm_name');
            $table->string('ocm_surname');
            $table->string('ocm_offer');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['crm_peanut_campaign_schema']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peanut_campaign_queue_settings');
    }
}
