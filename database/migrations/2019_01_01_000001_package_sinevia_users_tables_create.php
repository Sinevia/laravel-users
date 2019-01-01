<?php

class PackageSineviaUsersTablesCreate extends Illuminate\Database\Migrations\Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Sinevia\Users\Models\Role::tableCreate();
        Sinevia\Users\Models\User::tableCreate();
        Sinevia\Users\Models\UserRole::tableCreate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Sinevia\Users\Models\Role::tableDelete();
        Sinevia\Users\Models\User::tableDelete();
        Sinevia\Users\Models\UserRole::tableDelete();
    }

}
