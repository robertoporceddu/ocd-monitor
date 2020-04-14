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
            $table->string('ocm_name')->nullable();
            $table->string('ocm_surname')->nullable();
            $table->string('ocm_offer')->nullable();
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
