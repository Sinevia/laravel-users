<?php

namespace Sinevia\Users\Models;

class User extends \AdvancedModel {

    protected $table = 'snv_users_user';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    public $useUniqueId = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['Password'];
    public static $statusList = [
        'Active' => 'Active',
        'Inactive' => 'Inactive',
        'Banned' => 'Banned',
        'Deleted' => 'Deleted',
    ];

    public static function findByEmail($email) {
        return User::where('Email', '=', $email)->first();
    }

    public function getImageUrl() {
        $gender = $this->Gender;
        if ($gender == '') {
            $gender = 'male';
        }
        $image = $this->Image;

        if ($image == '') {
            $image = 'default_' . strtolower($gender) . '.gif';
        }
        return '/data/user/image/' . $image;
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Users\Role', 'snv_users_user_role', 'UserId', 'RoleId');
    }

    public function getClient() {
        $clientUser = \App\Models\Clients\ClientUser::where('UserId', '=', $this->Id)->first();
        if ($clientUser == null) {
            return null;
        }
        return \App\Models\Clients\Client::where('Id', '=', $clientUser->ClientId)->first();
    }

    public function addRole($role) {
        $roleId = is_object($role) ? $role->Id : $role;

        /* Is it title? Get role id? */
        if (is_numeric($roleId) == false) {
            $role = $this->roles()->where('Title', '=', $roleId)->first();
            if ($role == null) {
                return false;
            }
            $roleId = $role->Id;
        }

        $userrole = new UserRole();
        $userrole->Id = \Sinevia\Uid::microUid();
        $userrole->UserId = $this->Id;
        $userrole->RoleId = $roleId;
        $isSaved = $userrole->save();

        if ($isSaved == true) {
            return true;
        }

        return false;
    }

    public function removeRole($role) {
        $roleId = is_object($role) ? $role->Id : $role;

        /* Is it title? Get role id? */
        if (is_numeric($roleId) == false) {
            $role = $this->roles()->where('Title', '=', $roleId)->first();
            if ($role == null) {
                return false;
            }
            $roleId = $role->Id;
        }

        $isDeleted = UserRole::where('UserId', '=', $this->Id)->where('RoleId', '=', $roleId)->delete();

        if ($isDeleted === false) {
            return false;
        }

        return true;
    }

    /**
     * Accepts Role, Role Id, or Role Title
     * @param type $role
     * @return boolean
     */
    public function hasRole($role) {
        $roleId = is_object($role) ? $role->Id : $role;

        if (is_numeric($roleId)) {
            //$result = $this->roles()->where('Id', '=', $roleId)->first();
            $result = UserRole::where('UserId', '=', $this->Id)->where('RoleId', '=', $roleId)->first();
        } else {
            $result = $this->roles()->where('Title', '=', $roleId)->first();
        }

        return $result != null ? true : false;
    }
    
    

    public function hasRoleTitle($roleTitle) {
        $role = Role::where('Title', '=', $roleTitle)->first();

        if ($role == null) {
            return false;
        }

        $userRole = UserRole::where('UserId', '=', $this->Id)->where('RoleId', '=', $role->Id)->first();

        if ($userRole != null) {
            return true;
        }

        return false;
    }

    public static function tableCreate() {
        $o = new static;

        if (\Schema::connection($o->connection)->hasTable($o->table) == true) {
            return true;
        }

        return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o) {
                    $table->engine = 'InnoDB';
                    $table->string($o->primaryKey, 40)->primary();
                    $table->string('LibreId', 40)->default('');
                    $table->string('Status', 50)->default('Inactive');
                    $table->string('FirstName', 255);
                    $table->string('LastName', 255);
                    $table->string('Email', 255)->unique()->index();
                    $table->string('Password', 255)->default('');
                    $table->string('Country', 50)->default('');
                    $table->string('Province', 50)->default('');
                    $table->string('City', 50)->default('');
                    $table->string('Address1', 255)->default('');
                    $table->string('Address2', 255)->default('');
                    $table->string('Postcode', 50)->default('');
                    $table->string('PhoneLandline', 50)->default('');
                    $table->string('PhoneMobile', 50)->default('');
                    $table->string('Fax', 50)->default('');
                    $table->string('PictureUrl', 255)->default('');
                    $table->date('Birthday')->nullable();
                    $table->string('Gender', 6)->default('');
                    $table->datetime('CreatedAt')->nullable();
                    $table->datetime('UpdatedAt')->nullable();
                    $table->datetime('LastLoginAt')->nullable();
                });
    }

    public static function tableDelete() {
        $o = new static;
        return \Schema::connection($o->connection)->drop($o->table);
    }

    public function wpUser() {
        return $this->hasOne('App\Models\WpUser', 'WordpressId');
    }

}
