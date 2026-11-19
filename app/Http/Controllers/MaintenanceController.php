<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class MaintenanceController extends Controller
{
    public function syncRoles()
    {
        $streamContext = stream_context_create([
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false
            ]
        ]);
        $created_at = \Carbon\Carbon::now();
        $roles = file_get_contents(config('app.api_path') . "roles-api.php", false, $streamContext);
        $appRoleArr = explode(',', str_replace(array("\r\n", "[", "]", '"',), '', $roles));

        dd($appRoleArr);
        foreach ($appRoleArr as $appRole) {

            $roleArr = explode(":", $appRole);
            
        Role::where([['app', '=', $roleArr[3]],['app_role_id', '=', $roleArr[0]]])->delete();
            Role::create([
                'app_role_id' => $roleArr[0],
                'name' => strtoupper($roleArr[1]),
                'description' => $roleArr[2],
                'active' => 1,
                'created_at' => $created_at,
                'app'    => $roleArr[3],
            ]);
        }
        return response()->json('Roles Sync Completed!');
    }
    public function syncPermission()
    {
        
    }
}
