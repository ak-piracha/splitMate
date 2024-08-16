<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndPrincipalTenantIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the 'role' column if it's already present
            if (!Schema::hasColumn('users', 'principal_tenant_id')) {
                $table->unsignedBigInteger('principal_tenant_id')->nullable();

                $table->foreign('principal_tenant_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['principal_tenant_id']);
            $table->dropColumn('principal_tenant_id');
        });
    }
}
