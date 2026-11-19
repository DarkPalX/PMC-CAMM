<?php

namespace App\Services;

use App\Permission;
use App\Models\Role;
use App\Models\RolesPermissions;
use App\Models\Application;
use App\Repositories\Interfaces\RoleRightRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RoleRightService
{
    protected $repository;

    public function __construct(
        RoleRightRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    public function create($fields)
    {
        // $role = Role::where('name', $fields->role_id)->first();
        // $app = Application::where('description', "CAMM")->first();

        $rolerights = false;
        RolesPermissions::where('role_id',$fields->role_id)->delete();
        $listOfPermissions = explode(',', $fields->rolePermissions);
        foreach ($listOfPermissions as $permission) 
        {
            $permissionarray = explode("_", $permission);
            if (count($permissionarray) == 4) {
                $data = [
                    'role_id' => $fields->role_id,
                    'permission_id' => $permissionarray[0],
                    'app_id' => $fields->app_id,
                    'module_id' => $permissionarray[2],
                    'action' => $permissionarray[3],
                ];
            
                $rolerights = $this->repository->create($data);
            }
        }

        return $rolerights;
    }

    public function getByRolename($roleid)
    {        
        $rolepermissions = $this->repository->getByRolename($roleid);

        $data = collect();
        foreach ($rolepermissions as $rolepermission) {
            $data->push([
                'role_id' => $rolepermission->role_id,
                'application_id' => $rolepermission->app_id,
                'permission_id' => $rolepermission->permission_id,
                'module_id' => $rolepermission->module_id,
                'action' => $rolepermission->action,
            ]);
        }
        return $data;
    }

}