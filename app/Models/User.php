<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Application;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Contracts\UserResolver;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements AuditableContract, UserResolver
{
    use HasApiTokens, HasFactory, Notifiable;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'password', 'name', 'dept', 'imageFile', 'appRole', 'active', 'first_login', 'role_id', 'role', 'dtpassexpiration', 'remember_token', 'pass',
        'isSync'
    ];
    protected $auditInclude = [
        'username', 'password', 'name', 'dept', 'imageFile', 'appRole', 'active', 'first_login', 'role_id', 'role', 'dtpassexpiration'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // protected $attributes = ['appRoleDesc'];
    protected $appends = ['approle_desc', 'status'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // public static function employee_lookup()
    // {
    //     $employees = file_get_contents(env('API_PATH') . "hris-api.php");


    //     return $employees;
    // }
    public function getAppRoleDescAttribute()
    {
        $appRole = $this->appRole;
        $appRoleNameArr = "";
        $appRoleName = "";
        $role = "";
        $app = "";
        if ($appRole == null) {
            $appRole = "";
        }
        if ($appRole != "") {
            $userAppRoleList = explode("|", $this->appRole);
            foreach ($userAppRoleList as $userAppRole) {

                $appRoleList = explode(",", $userAppRole);

                $app = Application::find($appRoleList[0]);
                $role = Role::where([['app_role_id', $appRoleList[1]], ['app', $app->description]])->first();
                // dd($role);
                $appRoleName = $app->description . ':' . $role->name;
                if ($appRoleNameArr == "") {
                    $appRoleNameArr = $appRoleName;
                } else {
                    $appRoleNameArr = $appRoleNameArr . " | " . $appRoleName;
                }
            }
        }
        return $appRoleNameArr;
    }
    public function getStatusAttribute()
    {
        $status = 'Active';
        if ($this->active != 1) {
            $status  = 'Inactive';
        }
        return $status;
    }
    public static function resolve()
    {
        return Auth::check() ? Auth::user()->getAuthIdentifier() : null;
    }
}
