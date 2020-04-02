<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePredictiveMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predictive_match_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pbx_from_caller_id');
            $table->string('pbx_audio_announce_welcome');
            $table->string('pbx_audio_announce_wait');
            $table->string('pbx_audio_announce_fallback');
            $table->string('ocm_url');
            $table->string('ocm_token');
            $table->string('ocm_sap');
            $table->string('ocm_sap_fallback');
            $table->string('crm_peanut_url');
            $table->string('crm_peanut_buyer');
            $table->string('crm_peanut_campaign_schema');
            $table->string('crm_peanut_outcome_id_vs_interested');
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
        Schema::dropIfExists('predictive_match_settings');
    }
}
