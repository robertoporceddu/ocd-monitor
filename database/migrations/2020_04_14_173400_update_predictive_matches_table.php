<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePredictiveMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predictive_match_settings', function (Blueprint $table) {
            $table->string('ocm_name')->default('xxx');
            $table->string('ocm_surname')->default('xxx');
            $table->string('ocm_offer')->default('xxx');
            $table->string('pbx_dialer_match_type')->default('fallback_only');
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
            $table->dropColumn('ocm_name');
            $table->dropColumn('ocm_surname');
            $table->dropColumn('ocm_offer');
        });
    }
}
