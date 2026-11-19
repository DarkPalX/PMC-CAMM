<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\User;
use App\Models\UsersPermissions;
use App\Repositories\Interfaces\UserRightRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRightService
{
    protected $repository;
    protected $userService;

    public function __construct(
        UserRightRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }
    public function getUsers()
    {
        $users = $this->repository->getUsers();
        $data = collect();
        foreach ($users as $user) {
            $data->push([
                'id' => $user->id,
                'name' => $user->name,
            ]);
        }

        return $data;
    }
    public function getPermissions()
    {
        $permissions = Permission::where('active', '1')->orderBy('description', 'asc')->get();
        $data = collect();
        foreach ($permissions as $permission) {
            $data->push([
                'id' => $permission->id,
                'description' => $permission->description,
                'module_type' => $permission->module_type,
            ]);
        }

        return $data;
    }
    public function getModule()
    {

        $modules = DB::table('modules')->orderBy('description', 'asc')->get();
        // dd($module);
        $data = collect();
        foreach ($modules as $module) {
            $data->push([
                'id' => $module->id,
                'description' => $module->description,
            ]);
        }

        return $data;
    }
    public function create($fields)
    {
        $userrights = false;   
        UsersPermissions::where('username', '=', $fields->username)->delete();
        $listOfPermissions = explode(',', $fields->userPermissions);
        // dd($listOfPermissions);
        foreach ($listOfPermissions as $permission) {

            $permissionarray = explode("_", $permission);
            if (count($permissionarray) == 4) {
                $data = [
                    'username' => $fields->username,
                    'permission_id' => $permissionarray[0],
                    'app_id' => $permissionarray[1],
                    'module_id' => $permissionarray[2],
                    'action' => $permissionarray[3],
                ];
                $userrights = $this->repository->create($data);
            }
        }

        return $userrights;
    }

    public function destroy($id)
    {
        $user = $this->repository->destroy($id);

        if ($user) {
            return redirect()->back()->with('success', 'User has been removed successfully!');
        } else {
            return redirect()->back()->with('success', 'Failed removing user!');
        }
    }
    public function getById($userid)
    {
        $userpermissions = $this->repository->getById($userid);

        $data = collect();
        foreach ($userpermissions as $userpermission) {
            $data->push([
                'user_id' => $userpermission->user_id,
                'permission_id' => $userpermission->permission_id,
                'module_id' => $userpermission->module_id,
                'action' => $userpermission->action,
            ]);
        }
        return $data;
    }
    public function getByUsername($username,$appid)
    {
        $userpermissions = $this->repository->getByUsername($username,$appid);

        $data = collect();
        foreach ($userpermissions as $userpermission) {
            $data->push([
                'username' => $userpermission->username,
                'application_id' => $userpermission->app_id,
                'permission_id' => $userpermission->permission_id,
                'module_id' => $userpermission->module_id,
                'action' => $userpermission->action,
            ]);
        }
        return $data;
    }
    public function hasPermissions($description)
    {
        $user_permissions = $this->repository->hasPermissions($description);
        $data = collect();
        $access = false;
        $role = auth()->user()->role;
        if ($role == "ADMIN" || $role == "admin") {
            $access = true;
        }
        
        $create = $access;
        $edit = $access;
        $delete = $access;
        $view = $access;
        $print = $access;
        $search = $access;
        $upload = $access;
        $pagination = $access;
        if ($role != "USER") {
        foreach ($user_permissions as $user_permission) {
            if ($user_permission['action'] == "create") {
                $create = true;
            } elseif ($user_permission['action'] == "edit") {
                $edit = true;
            } elseif ($user_permission['action'] == "delete") {
                $delete = true;
            } elseif ($user_permission['action'] == "view") {
                $view = true;
            } elseif ($user_permission['action'] == "print") {
                $print = true;
            } elseif ($user_permission['action'] == "search") {
                $search = true;
            } elseif ($user_permission['action'] == "upload") {
                $upload = true;
            } elseif ($user_permission['action'] == "pagination") {
                $pagination = true;
            }
        }
    }

        $data->push([
            'create' => $create,
            'edit' => $edit,
            'delete' => $delete,
            'view' => $view,
            'print' => $print,
            'search' => $search,
            'upload' => $upload,
            'pagination' => $pagination,
        ]);

        return $data->first();
    }
}
