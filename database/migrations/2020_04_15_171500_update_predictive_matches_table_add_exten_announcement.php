<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePredictiveMatchesTableAddExtenAnnouncement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predictive_match_settings', function (Blueprint $table) {
            $table->string('pbx_dialer_exten_announcement')->default('none');
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
            $table->dropColumn('pbx_dialer_exten_announcement');
        });
    }
}
