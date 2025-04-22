<?php

use App\Enum\UserRoleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $allowedRoleValues = UserRoleEnum::names();
        $allowedRoleNames = UserRoleEnum::names();
        $allowedValuesString = "'" . implode("', '", $allowedRoleValues) . "'";
        $allowedNamesString = "'" . implode("', '", $allowedRoleNames) . "'";

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('value')->nullable(false);
        });

        DB::statement("ALTER TABLE roles ADD CONSTRAINT chk_role_value CHECK (value IN ($allowedValuesString))");
        DB::statement("ALTER TABLE roles ADD CONSTRAINT chk_role_name CHECK (value IN ($allowedNamesString))");
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            DB::statement("ALTER TABLE roles DROP CONSTRAINT chk_role_value");
            DB::statement("ALTER TABLE roles DROP CONSTRAINT chk_role_name");

            $table->string('name')->change();
            $table->dropColumn('value');
        });
    }
};
