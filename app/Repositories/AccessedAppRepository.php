<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\AccessedApp;
use App\Repositories\Interfaces\AccessedAppRepositoryInterface;

class AccessedAppRepository implements AccessedAppRepositoryInterface
{
    protected $user;
    protected $userApprole;

    public function __construct(AccessedApp $accessedApp)
    {
        $this->accessedApp = $accessedApp;
    }

    public function all()
    {
        return $this->accessedApp->where('isActive',1)->get();
    }

    public function create($fields)
    {
        return $this->accessedApp->create($fields);
    }

    // public function update($fields, $id)
    // {
    //     return $this->user->find($id)->update($fields);
    // }

    // public function getById($id)
    // {
    //     return $this->user->find($id);
    // }

}