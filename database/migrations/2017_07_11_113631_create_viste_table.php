<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE  OR REPLACE VIEW daily_hangupcauses_detailed AS 
                            SELECT DISTINCT ON ((date_trunc('day'::text, dial_status.created_at))) date_trunc('day'::text, dial_status.created_at) AS date_trunc,
                                sum(
                                    CASE
                                        WHEN ((dial_status.hangup_cause IS NULL) OR (dial_status.hangup_cause = ANY (ARRAY[0, 16]))) THEN 1
                                        ELSE 0
                                    END) AS success,
                                sum(
                                    CASE
                                        WHEN (dial_status.hangup_cause = 17) THEN 1
                                        ELSE 0
                                    END) AS busy,
                                sum(
                                    CASE
                                        WHEN ((dial_status.hangup_cause IS NOT NULL) AND (dial_status.hangup_cause <> ALL (ARRAY[0, 16, 17]))) THEN 1
                                        ELSE 0
                                    END) AS failed,
                            count(*) AS total
                            FROM dial_status
                            GROUP BY (date_trunc('day'::text, dial_status.created_at))
                            ORDER BY (date_trunc('day'::text, dial_status.created_at)) DESC;"
        );

        DB::statement("CREATE  OR REPLACE VIEW daily_hangupcauses_summary AS 
                        SELECT DISTINCT ON ((date_trunc('day'::text, dial_status.created_at)), dial_status.hangup_cause) date_trunc('day'::text, dial_status.created_at) AS date_trunc,
                            dial_status.hangup_cause,
                        count(*) AS count
                        FROM dial_status
                        GROUP BY (date_trunc('day'::text, dial_status.created_at)), dial_status.hangup_cause
                        ORDER BY (date_trunc('day'::text, dial_status.created_at)) DESC, dial_status.hangup_cause;"
        );

        DB::statement("CREATE OR REPLACE VIEW extension_status AS 
                        SELECT extension_status_log.extension_status_log_id,
                            extension_status_log.extension,
                            extension_status_log.action,
                            extension_status_log.created_at,
                            extension_status_log.last_at,
                            extension_status_log.username,
                            extension_status_log.schema,
                            extension_status_log.is_last,
                            (now() - (extension_status_log.created_at)::timestamp with time zone) AS elapsed_time
                        FROM extension_status_log
                        WHERE (extension_status_log.is_last = true)
                        ORDER BY extension_status_log.action, (now() - (extension_status_log.created_at)::timestamp with time zone) DESC;"
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop view daily_hangupcauses_detailed');
        DB::statement('drop view daily_hangupcauses_summary');
        DB::statement('drop view extension_status');
    }
}
