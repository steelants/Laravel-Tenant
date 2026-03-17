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

    private function shouldSkip(array $table): bool
    {
        if (in_array($table['name'], $this->skipTables)) {
            return true;
        }

        if (DB::connection()->getDriverName() === 'pgsql' && isset($table['schema']) && $table['schema'] !== 'public') {
            return true;
        }

        return false;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Schema::getTables() as $table) {
            if ($this->shouldSkip($table)) {
                continue;
            }

            if (Schema::hasColumn($table['name'], 'tenant_id')) {
                continue;
            }

            Schema::table($table['name'], function ($table) {
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
        foreach (Schema::getTables() as $table) {
            if ($this->shouldSkip($table)) {
                continue;
            }

            if (!Schema::hasColumn($table['name'], 'tenant_id')) {
                continue;
            }

            Schema::table($table['name'], function ($table) {
                $table->dropForeign([$table . '_tenant_tenant_id_foreign']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
