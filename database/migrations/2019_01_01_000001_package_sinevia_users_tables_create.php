<?php

class PackageSineviaUsersTablesCreate extends Illuminate\Database\Migrations\Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        App\Models\Users\Role::tableCreate();
        App\Models\Users\User::tableCreate();
        App\Models\Users\UserRole::tableCreate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        App\Models\Users\Role::tableDelete();
        App\Models\Users\User::tableDelete();
        App\Models\Users\UserRole::tableDelete();
    }

}
