<?php

namespace Sinevia\Users\Models;

class Role extends \AdvancedModel {

    protected $table = 'snv_users_role';
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
                    $table->string('Title', 255)->index();
                    $table->text('Description', 255);
                });
    }

    public static function tableDelete() {
        $o = new Role;
        return \Schema::connection($o->connection)->dropIfExists($o->table);
    }

}
