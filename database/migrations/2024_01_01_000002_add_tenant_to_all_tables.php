<?php

use Illuminate\Database\Migrations\Migration;
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
        foreach (Schema::getTables() as $table) {
            if (in_array($table['name'], $this->skipTables)) {
                continue;
            }
            Schema::table($table['name'], function ($table) {
                $table->foreignIdFor(Tenant::class)->nullable()->index()->constrained();
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
            if (in_array($table['name'], $this->skipTables)) {
                continue;
            }

            Schema::table('users', function ($table) {
                $table->dropForeign([$table . '_tenant_tenant_id_foreign']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
