<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserApprole;

class UserRepository implements UserRepositoryInterface
{
    protected $user;
    protected $userApprole;

    public function __construct(User $user,UserApprole $userApprole)
    {
        $this->user = $user;
        $this->userApprole = $userApprole;
    }

    public function all()
    {
        return $this->user->all();
    }

    public function create($fields)
    {
        return $this->user->create($fields);
    }

    public function update($fields, $id)
    {
        return $this->user->find($id)->update($fields);
    }

    public function destroy($id)
    {
        return $this->user->find($id)->delete();
    }

    public function getById($id)
    {
        return $this->user->find($id);
    }

    public function getUserActions($fields)
    {
        $query = $this->action->orderBy('created_at');

        if ($fields->from) {
            $query->where('created_at', '>=', $fields->from);
        }
        if ($fields->to) {
            $query->where('created_at', '<=', $fields->to);
        }
        if ($fields->username) {
            $query->where('user', $fields->username);
        }
        if ($fields->from && $fields->to && $fields->username) {
            $query->whereBetween('created_at', [$fields->from, $fields->to])->where('user', $fields->username);
        }

        return $query->get();
    }

    public function GetRoleName($role_id)
    {
        try{
            $roles = DB::table('roles')
                ->select(
                    'roles.name  as role_name')
                ->where('roles.id','=',$role_id)
                ->first();
            return $roles;
        }catch (QueryException $e) {
            return null;
        }
    }    
    public function saveAppRole($fields)
    {
        return $this->userApprole->create($fields);
    }
}