<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePbxQueueMiddlewareSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pbx_queue_middleware_settings', function (Blueprint $table) {
            $table->string('crm_peanut_sell_buyer')->nullable();
            $table->string('crm_peanut_sell_campaign')->nullable();
            $table->string('crm_peanut_sell_campaign_fallback')->nullable();
            $table->string('crm_peanut_id_outcome_ko')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pbx_queue_middleware_settings', function (Blueprint $table) {
            $table->dropColumn('crm_peanut_sell_buyer');
            $table->dropColumn('crm_peanut_sell_campaign');
            $table->dropColumn('crm_peanut_sell_campaign_fallback');
            $table->dropColumn('crm_peanut_id_outcome_ko');
        });
    }
}
