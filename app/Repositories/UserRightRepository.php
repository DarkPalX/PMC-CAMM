<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRightRepositoryInterface;
use App;
use App\Models\User;
use App\Models\Permission;
// use App\RolesPermissions;
use App\Models\UsersPermissions;
use App\Models\RolesPermissions;
use App\Services\UserRightService;
use Illuminate\Support\Facades\DB;

class UserRightRepository implements UserRightRepositoryInterface
{
    protected $user;
    protected $action;

    public function __construct(
        UsersPermissions $usersPermissions
    ) {
        $this->userspermissions = $usersPermissions;
    }

    public function getById($userid)
    {
        $userPermissions = UsersPermissions::where('user_id', $userid)->get();
        return UsersPermissions::where('user_id', $userid)->get();

        // if (count($userPermissions) > 0) {
        //     return UsersPermissions::where('user_id', $userid)->get();
        // } else {
        //     $user = User::where('id', $userid)->first();
        //     $roleid = $user['role_id'];
        //     return RolesPermissions::where('role_id', $roleid)->get();
        // }
    }

    public function getByUserName($username,$appid)
    {
        // $userPermissions = UsersPermissions::where('user_id', $username)->get();\\
        // dd($username.' - ' .$appid);
        // dd(UsersPermissions::where([['username', $username],['app_id',$appid]])->get());
        return UsersPermissions::where([['username', $username],['app_id',$appid]])->get();

        // if (count($userPermissions) > 0) {
        //     return UsersPermissions::where('user_id', $userid)->get();
        // } else {
        //     $user = User::where('id', $userid)->first();
        //     $roleid = $user['role_id'];
        //     return RolesPermissions::where('role_id', $roleid)->get();
        // }
    }

    public function getUsers()
    {
        return User::where('active', '1')->get();
    }
    public function getPermissions()
    {
        $permissions = Permission::where('active', '1')->get();

        $data = collect();
        foreach ($permissions as $permission) {
            $data->push([
                'id' => $permission->id,
                'name' => $permission->name,
            ]);
        }

        return $data;
    }
    // public function getModule()
    // {
    //     $modules = DB::table('modules')->get();
    //     // dd($module);
    //     $data = collect();
    //     foreach ($modules as $module) {
    //         $data->push([
    //             'id' => $module->id,
    //             'description' => $module->description,
    //         ]);
    //     }

    //     return $data;
    // }
    public function create($fields)
    {

        return $this->userspermissions->create($fields);
    }

    public function destroy($id)
    {
        return $this->userspermissions->find($id)->delete();
    }
    public function hasPermissions($description)
    {
        $username = auth()->user()->username;
        $userPermissions = UsersPermissions::where('username', $username)->get();

        $permission = Permission::where([['active', '1'], ['description', $description]])->first()['id'];
        if (count($userPermissions) > 0) {
            return UsersPermissions::where([['username', $username],['permission_id', $permission],['app_id',6]])->get();
        } else {

            $role = auth()->user()['role_id'];
            return RolesPermissions::where([['role_id', $role ],['permission_id', $permission],['app_id',6]])->get();
        }
    }
}
