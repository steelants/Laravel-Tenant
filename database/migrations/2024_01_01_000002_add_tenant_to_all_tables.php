<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use SteelAnts\LaravelTenant\Models\Tenant;

return new class extends Migration{
    private $skipTables = ['jobs', 'failed_jobs', 'users', 'migrations', 'password_reset_tokens' , 'tenants'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = DB::select('SHOW TABLES');
        $db = "Tables_in_".DB::connection()->getDatabaseName();

        foreach ($tables as $table) {
            if (in_array($table->{$db}, $this->skipTables)) {
                continue;
            }
            Schema::table($table->{$db}, function ($table) {
                $table->foreignIdFor(Tenant::class)->default(1)->constrained();

            });
            Schema::table($table->{$db}, function ($table) {

            $table->foreignIdFor(Tenant::class)->default(null)->change();
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
        $tables = DB::select('SHOW TABLES');
        $db = "Tables_in_".DB::connection()->getDatabaseName();

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
