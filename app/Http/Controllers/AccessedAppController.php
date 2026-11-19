<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessedApp;
use App\Models\Application;
use App\Services\AccessedAppService;
use Exception;
use Illuminate\Support\Facades\Schema;
use PDO;

class AccessedAppController extends Controller
{
    public function __construct(AccessedAppService $accessedAppService)
    {
        $this->accessedAppService = $accessedAppService;
    }
    public function create(Request $request)
    {
        $this->accessedAppService->create($request);
        return response()->json(['message' => 'success']);
    }
    public function logoutUser(Request $request)
    {
        try {
            $application = Application::where('description', $request->app)->first();

            $conn_a['app']['type'] = config('app.db_connection');
            $conn_a['app']['host'] =  $application->server_name;; //'192.155.86.62';
            $conn_a['app']['name'] = $application->database_name;
            $conn_a['app']['uname'] = $application->dbusername;
            $conn_a['app']['pword'] = $application->dbpass;

            $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

            $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $pdoApps->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $data = [
                'isLoggedIn' => 0,
                'username' => $request->user,
            ];
            $sql = "UPDATE users SET isLoggedIn=:isLoggedIn WHERE " . $application->loginFieldName ."=:username";
            $pdoApps->prepare($sql)->execute($data);

            $accessApp = AccessedApp::where([['username', $request->user], ['app', $application->id]])->update(['isActive' => 0]);
            return response()->json('success');
        } catch (Exception $e) {
            echo 'Exception -> ';
            dd($e->getMessage());
        }
    }
}
