<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The app timezone changed from UTC to Asia/Kolkata (config/app.php). Dates already stored were
 * written as UTC wall-clock times, so move them forward 5:30 to keep them pointing at the same moment.
 * India has no daylight saving, so the offset is fixed. Fresh databases have nothing to convert.
 */
return new class extends Migration
{
    private const OFFSET_MINUTES = 330;

    public function up(): void
    {
        $this->shift(self::OFFSET_MINUTES);
    }

    public function down(): void
    {
        $this->shift(-self::OFFSET_MINUTES);
    }

    private function shift(int $minutes): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $columns = collect(DB::select(
            "SELECT TABLE_NAME AS t, COLUMN_NAME AS c FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND DATA_TYPE IN ('timestamp', 'datetime') AND TABLE_NAME <> 'migrations'"
        ))->groupBy('t');

        foreach ($columns as $table => $cols) {
            // One statement per table, every column set explicitly (NULL stays NULL).
            $set = $cols->map(fn ($col) => "`{$col->c}` = `{$col->c}` + INTERVAL {$minutes} MINUTE")->implode(', ');

            DB::statement("UPDATE `{$table}` SET {$set}");
        }
    }
};
