<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RoleRightRepositoryInterface;
use App;
use App\Models\Permission;
use App\Models\RolesPermissions;
use App\Services\RoleRightService;
use Illuminate\Support\Facades\DB;

class RoleRightRepository implements RoleRightRepositoryInterface
{
    public function __construct(RolesPermissions $rolesPermissions) 
    {
        $this->rolespermissions = $rolesPermissions;
    }

    public function create($fields)
    {

        return $this->rolespermissions->create($fields);
    }    

    public function destroy($id)
    {
        return $this->rolespermissions->find($id)->delete();
    }    

    public function getByRolename($roleid)
    {
        return RolesPermissions::where('role_id', $roleid)->get();     
    }

}