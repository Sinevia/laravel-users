<?php

namespace Sinevia\Users\Models;

class UserRole extends \AdvancedModel {

    protected $table = 'snv_users_user_role';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    public $useUniqueId = true;

    public static function tableCreate() {
        $o = new static;

        if (\Schema::connection($o->connection)->hasTable($o->table) == true) {
            return true;
        }
        return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                    $table->engine = 'InnoDB';
                    $table->string($o->primaryKey, 40)->primary();
                    $table->string('Status', 50)->default('Inactive')->index();
                    $table->string('UserId', 50)->index();
                    $table->string('RoleId', 255)->index();
                    $table->datetime('CreatedAt')->nullable();
                    $table->datetime('UpdatedAt')->nullable();
                });
    }

    public static function tableDelete() {
        $o = new static;
        return \Schema::connection($user->connection)->dropIfExists($o->table);
    }

}
