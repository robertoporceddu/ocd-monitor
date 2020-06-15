<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatesInPredictiveMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predictive_match_settings', function (Blueprint $table) {
            $table->string('pbx_audio_announce_welcome')->nullable()->change();
            $table->string('pbx_audio_announce_wait')->nullable()->change();
            $table->string('pbx_audio_announce_fallback')->nullable()->change();
            $table->string('ocm_url')->nullable()->change();
            $table->string('ocm_token')->nullable()->change();
            $table->string('ocm_sap')->nullable()->change();
            $table->string('ocm_sap_fallback')->nullable()->change();
            $table->string('crm_peanut_url')->nullable()->change();
            $table->string('crm_peanut_token')->nullable()->change();
            $table->string('crm_peanut_buyer')->nullable()->change();
            $table->string('crm_peanut_campaign_schema')->nullable()->change();
            $table->string('crm_peanut_outcome_id_vs_interested')->nullable()->change();
            $table->string('ocm_name')->default('xxx')->nullable()->change();
            $table->string('ocm_surname')->default('xxx')->nullable()->change();
            $table->string('ocm_offer')->default('xxx')->nullable()->change();
            $table->string('pbx_dialer_match_type')->default('fallback_only')->nullable()->change();
            $table->string('pbx_dialer_exten_announcement')->default('none')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('predictive_match_settings', function (Blueprint $table) {
            $table->string('pbx_audio_announce_welcome')->change();
            $table->string('pbx_audio_announce_wait')->change();
            $table->string('pbx_audio_announce_fallback')()->change();
            $table->string('ocm_url')->change();
            $table->string('ocm_token')->change();
            $table->string('ocm_sap')->change();
            $table->string('ocm_sap_fallback')->change();
            $table->string('crm_peanut_url')->change();
            $table->string('crm_peanut_token')->change();
            $table->string('crm_peanut_buyer')->change();
            $table->string('crm_peanut_campaign_schema')->change();
            $table->string('crm_peanut_outcome_id_vs_interested')->change();
            $table->string('ocm_name')->default('xxx')->change();
            $table->string('ocm_surname')->default('xxx')->change();
            $table->string('ocm_offer')->default('xxx')->change();
            $table->string('pbx_dialer_match_type')->default('fallback_only')->change();
            $table->string('pbx_dialer_exten_announcement')->default('none')->change();
        });
    }
}
