<?php

use SteelAnts\LaravelTenant\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Schema::getTables() as $table) {
            if (!Schema::hasColumn($table['name'], 'tenant_id')) {
                continue;
            }

            if (Schema::hasIndex($table['name'], ['tenant_id'])) {
                continue;
            }

            Schema::table($table['name'], function ($table) {
                $table->index('tenant_id');
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
            if (!Schema::hasColumn($table['name'], 'tenant_id')) {
                continue;
            }

            if (!Schema::hasIndex($table['name'], ['tenant_id'])) {
                continue;
            }

            Schema::table('users', function ($table) {
                $table->dropIndex(['tenant_id']);
            });
        }
    }
};
