<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleAccessRightRequest;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Role;
use Illuminate\Support\Facades\Session;
use App\Services\RoleRightService;
use App\Services\UserRightService;

class RoleAccessRightController extends Controller
{
    public function __construct(RoleRightService $rolerightService,UserRightService $userrightService) 
    {
        $this->userrightService = $userrightService;
        $this->rolerightService = $rolerightService;
    }
   

    public function index()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Roles Access Rights");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $application = Application::where([['active', 1],['description','CAMM']])->first();
        return view('roleaccessright.index', compact('application'));

    }

    public function store(RoleAccessRightRequest $roleaccessrightRequest)
    {
        // $request->validate([
        //     'id' => 'required',
        // ]);        
        
        $accessRights = $this->rolerightService->create($roleaccessrightRequest);        
        return response()->json(['message' => 'success']);
    }    

    public function getAccessRights(Request $request)
    {
        $roles_permissions = $this->rolerightService->getByRolename($request->roleid);
        return $roles_permissions;
    }

}
