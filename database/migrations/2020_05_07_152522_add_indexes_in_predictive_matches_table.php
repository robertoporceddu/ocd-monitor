<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesInPredictiveMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('predictive_match_settings', function (Blueprint $table) {
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
        Schema::table('predictive_match_settings', function (Blueprint $table) {
            $table->dropIndex(['pbx_from_caller_id']);
        });
    }
}
