<?php

namespace App\Repositories\Interfaces;

interface RoleRightRepositoryInterface
{
    public function create($fields);
    public function destroy($role_id);        
    public function getByRolename($role_id);
}
