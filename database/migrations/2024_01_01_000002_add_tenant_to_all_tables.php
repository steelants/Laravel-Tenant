<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use SteelAnts\LaravelTenant\Models\Tenant;

return new class extends Migration
{
    private $skipTables = [
        'jobs',
        'job_batches',
        'failed_jobs',
        'users',
        'migrations',
        'password_resets',
        'password_reset_tokens',
        'tenants',
        'cache',
        'cache_locks',
        'sessions',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') == 'sqlite') {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
        } else {
            $tables = DB::select('SHOW TABLES');
        }
        $db = "Tables_in_" . DB::connection()->getDatabaseName();

        foreach ($tables as $table) {
            if (in_array($table->{$db}, $this->skipTables)) {
                continue;
            }
            Schema::table($table->{$db}, function ($table) {
                $table->foreignIdFor(Tenant::class)->nullable()->constrained();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.default') == 'sqlite') {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
        } else {
            $tables = DB::select('SHOW TABLES');
        }
        $db = "Tables_in_" . DB::connection()->getDatabaseName();

        foreach ($tables as $table) {
            if (in_array($table->{$db}, $this->skipTables)) {
                continue;
            }

            Schema::table('users', function ($table) {
                $table->dropForeign([$table . '_tenant_tenant_id_foreign']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
