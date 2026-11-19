<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Application;
use App\Models\ReindexApplications;
use App\Models\ShrinkApplications;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ShutdownApplication;
use App\Models\User;
use App\Models\UsersPermissions;
use App\Services\UserRightService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;
use PDO;

class DatabaseController extends Controller
{
    public function __construct(
        UserRightService $userrightService
    ) {
        $this->userrightService = $userrightService;
    }
    public function index(Request $request)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Databases");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $databasetables = Application::where('active', 1)->get();
        $databasetables = json_encode($databasetables);
        return view('database.index', compact('databasetables'));
    }

    public function getApplications()
    {
        return Application::where('active', 1)->get();
    }
    public function reindex(Request $request)
    {
        try {
            $application = Application::where('id', $request->id)->first();
            $appname = $application->description;
            $conn_a['app']['type'] = config('app.db_connection');
            $conn_a['app']['host'] =   $application->server_name;
            $conn_a['app']['name'] = $application->database_name;
            $conn_a['app']['uname'] = $application->dbusername;
            $conn_a['app']['pword'] = $application->dbpass;

            $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

            $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->reindexDatabases($pdoApps, $application->id);

            $updated_at = \Carbon\Carbon::now();
            $data = [
                'updated_at_reindex' => $updated_at,
            ];
            $application->update($data);

            ReindexApplications::create([
                'app_id'        => $request->id,
                'description'   => $application->description,
                'created_at'    =>  $updated_at,
                'updated_at'    => $updated_at,
                'database_name' => $application->database_name,
            ]);

            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['error' => $appname . ' : ' . $e->getMessage()], 500);
        }
    }

    public function reindexDatabases($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_modules = array();
        $moduledatasql = $pdoApps->prepare("EXEC runScheduledIndexing  ");
        $moduledatasql->execute();
        $moduledatasql->setFetchMode(PDO::FETCH_ASSOC);
    }

    public function shrink(Request $request)
    {
        try {
            $application = Application::where('id', $request->id)->first();
            $appname = $application->description;
            $conn_a['app']['type'] = config('app.db_connection');
            $conn_a['app']['host'] =   $application->server_name;
            $conn_a['app']['name'] = $application->database_name;
            $conn_a['app']['uname'] = $application->dbusername;
            $conn_a['app']['pword'] = $application->dbpass;

            $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

            $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->shrinkDatabases($pdoApps, $application->id);

            $updated_at = \Carbon\Carbon::now();
            $data = [
                'updated_at_shrink' => $updated_at,
            ];
            $application->update($data);

            ShrinkApplications::create([
                'app_id'        => $request->id,
                'description'   => $application->description,
                'created_at'    =>  $updated_at,
                'updated_at'    => $updated_at,
                'database_name' => $application->database_name,
            ]);

            return response()->json('success');
        } catch (Exception $e) {
            return response()->json(['error' => $appname . ' : ' . $e->getMessage()], 500);
        }
    }

    public function shrinkDatabases($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_modules = array();
        $moduledatasql = $pdoApps->prepare("EXEC runShrinkDatabase  ");
        $moduledatasql->execute();
        $moduledatasql->setFetchMode(PDO::FETCH_ASSOC);
    }

    public function sync(Request $request)
    {
        try {
            $application = Application::where('id', $request->id)->first();
            $appname = $application->description;
            $funcName = '';

            if ($appname != 'CAMM') {
                $conn_a['app']['type'] = config('app.db_connection');
                $conn_a['app']['host'] =   $application->server_name; //'192.155.86.62';
                $conn_a['app']['name'] = $application->database_name;
                $conn_a['app']['uname'] = $application->dbusername;
                $conn_a['app']['pword'] = $application->dbpass;

                $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

                $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $funcName = 'Roles';
                $this->syncRoles($pdoApps, $application->description);
                $funcName = 'Modules';
                $this->syncModules($pdoApps, $application->id);
                $funcName = 'Permissions';
                $this->syncPermissions($pdoApps, $application->id);
                $funcName = 'Users';
                $this->syncUsers($pdoApps, $application->id, $application->description, $application->loginFieldName);
                $funcName = 'User Permissions';
                $this->syncUserPerm($pdoApps, $application->id, $application->description, $application->loginFieldName);
                $funcName = 'Logs';
                $this->syncLogs($pdoApps, $application->id, $application->loginFieldName);
                $funcName = 'Scheduled Shutdown';
                $this->syncSchedShutdown($pdoApps, $application->id, $application->description, $application->loginFieldName);

                $application->update(['syncDate' => Carbon::now()]);

                return response()->json('success');
            }
            else
            {
                return response()->json(['error' => 'Syncing is not applicable for CAMM database'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $appname . ' ' . $funcName . ' : ' . $e->getMessage()], 500);
        }
    }
    // public function sync_auto(Request $request)
    // {
    //     try {
    //         $applications = Application::where([['active', 1], ['id', '!=', 6]])->get();
    //         foreach ($applications as $application) {

    //             $conn_a['app']['type'] = config('app.db_connection');
    //             $conn_a['app']['host'] =   $application->server_name; //'192.155.86.62';
    //             $conn_a['app']['name'] = $application->database_name;
    //             $conn_a['app']['uname'] = $application->dbusername;
    //             $conn_a['app']['pword'] = $application->dbpass;

    //             $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

    //             $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //             $funcName = 'Roles';
    //             $this->syncRoles($pdoApps, $application->description);
    //             $funcName = 'Modules';
    //             $this->syncModules($pdoApps, $application->id);
    //             $funcName = 'Permissions';
    //             $this->syncPermissions($pdoApps, $application->id);
    //             $funcName = 'Users';
    //             $this->syncUsers($pdoApps, $application->id, $application->description, $application->loginFieldName);
    //             $funcName = 'User Permissions';
    //             $this->syncUserPerm($pdoApps, $application->id, $application->description, $application->loginFieldName);
    //             $funcName = 'Logs';
    //             $this->syncLogs($pdoApps, $application->id, $application->loginFieldName);
    //             $funcName = 'Scheduled Shutdown';
    //             $this->syncSchedShutdown($pdoApps, $application->id, $application->description, $application->loginFieldName);

    //             $application->update(['syncDate' => Carbon::now()]);

    //             return response()->json('success');
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['error' => $funcName . ' : ' . $e->getMessage()], 500);
    //     }
    // }
    public function clear($id)
    {
    }
    public function syncRoles($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_role = array();
        $roledatasql = $pdoApps->prepare("SELECT id,[name] AS roleName,[name] AS roleDescription, active  FROM roles");

        $roledatasql->execute();
        $roledatasql->setFetchMode(PDO::FETCH_ASSOC);

        for ($i = 0; $rowdata = $roledatasql->fetch(); $i++) {
            $array_role[] .= str_replace(':', ' ', $rowdata['id']) . ':' . $rowdata['roleName'] . ':' . $rowdata['roleDescription'] . ':' . $rowdata['active'];
        }

        foreach ($array_role as $appRole) {

            $roleArr = explode(":", $appRole);

            Role::where([['app', '=', $app], ['app_role_id', '=', $roleArr[0]]])->delete();
            Role::create([
                'app_role_id' => $roleArr[0],
                'name' => strtoupper($roleArr[1]),
                'description' => $roleArr[2],
                'active' =>  $roleArr[3],
                'created_at' => $created_at,
                'app'    => $app,
            ]);
        }
    }
    public function syncModules($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_modules = array();
        $moduledatasql = $pdoApps->prepare("SELECT id,[description] AS moduleDescription  FROM modules");
        $moduledatasql->execute();
        $moduledatasql->setFetchMode(PDO::FETCH_ASSOC);

        for ($i = 0; $rowdata = $moduledatasql->fetch(); $i++) {
            $array_modules[] .= str_replace(':', ' ', $rowdata['id']) . ':' . $rowdata['moduleDescription'];
        }
        foreach ($array_modules as $appModule) {

            $moduleArr = explode(":", $appModule);
            Module::where([['application', '=', $app], ['app_module_id', '=', $moduleArr[0]]])->delete();
            Module::create([
                'app_module_id' => $moduleArr[0],
                'description' => $moduleArr[1],
                'active' => 1,
                'application'    => $app,
            ]);
        }
    }
    public function syncPermissions($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_permissions = array();
        $permissiondatasql = $pdoApps->prepare("SELECT p.id AS id,m.description AS module,p.[description] AS permDescription, p.active  FROM permissions p left join modules m  on p.module_type = m.description");

        $permissiondatasql->execute();
        $permissiondatasql->setFetchMode(PDO::FETCH_ASSOC);

        for ($i = 0; $rowdata = $permissiondatasql->fetch(); $i++) {
            $array_permissions[] .= str_replace(':', ' ', $rowdata['id'])  . ':' . $rowdata['permDescription'] . ':' . $rowdata['active'] . ':' . $rowdata['module'];
        }

        foreach ($array_permissions as $appPermission) {

            $permissionArr = explode(":", $appPermission);

            Permission::where([['application', '=', $app], ['app_permission_id', '=', $permissionArr[0]]])->delete();
            Permission::create([
                'app_permission_id' => $permissionArr[0],
                'description' => $permissionArr[1],
                'active' => $permissionArr[2],
                'module_type'    => $permissionArr[3],
                'application'    => $app,
            ]);
        }
    }
    public function syncUserPerm($pdoApps, $app, $appdesc, $loginFieldName)
    {
        $created_at = \Carbon\Carbon::now();
        $array_users = array();

        $users = User::where([['active', 1], ['username', '!=', 'admin']])->get();

        foreach ($users as $user) {

            $userPermissions = UsersPermissions::where([['app_id', $app], ['username', $user->username]])->get();
            $selectuser = $pdoApps->prepare("SELECT id from users  where " . $loginFieldName . " = '" . $user->username . "'");
            $selectuser->execute();
            $selectuser->setFetchMode(PDO::FETCH_ASSOC);
            $userid = $selectuser->fetch();

            $sql = "DELETE FROM users_permissions WHERE user_id = " . $userid['id'];
            $deleteUserPerm = $pdoApps->prepare($sql)->execute();

            foreach ($userPermissions as $userPermission) {
                $data = [
                    'user_id' => $userid['id'],
                    'permission_id' => $userPermission->permission_id,
                    'module_id' => $userPermission->module_id,
                    'action' => $userPermission->action,
                ];
                $sql = "INSERT INTO users_permissions (user_id,permission_id,module_id,action) VALUES (:user_id,:permission_id,:module_id,:action)";
                $updateuser = $pdoApps->prepare($sql);
                $updateuser->execute($data);
            }
        }
    }
    public function syncUsers($pdoApps, $app, $appdesc, $loginFieldName)
    {
        $created_at = \Carbon\Carbon::now();
        $array_users = array();


        $users = User::where('username', '!=', 'admin')->get();
        foreach ($users as $user) {
            $selectuser = $pdoApps->prepare("SELECT id from users  where " . $loginFieldName . " = '" . $user->username . "'");
            $selectuser->execute();
            $selectuser->setFetchMode(PDO::FETCH_ASSOC);
            $row = $selectuser->fetch();
            $appRoles = explode('|', $user->appRole);
            $roledesc = "";
            $role_id = 0;
            foreach ($appRoles as $appRoleArr) {
                $appRole = explode(',', $appRoleArr);
                if ($appRole[0] == $app) {
                    $role = Role::where([['app_role_id', $appRole[1]], ['app', $appdesc]])->first();
                    $roledesc = $role->description;
                    $role_id = $role->app_role_id;
                }
            }
            if ($row) {
                $data = [
                    'password' => $user->password,
                    'role' => $roledesc,
                    'role_id' => $role_id,
                ];
                $sql = "UPDATE users SET  role=:role, role_id=:role_id, password=:password WHERE " . $loginFieldName . " = '" . $user->username . "'";
                $updateuser = $pdoApps->prepare($sql);
                $updateuser->execute($data);
            } else {
                $data = [
                    'username' => $user->username,
                    'password' => $user->password,
                    'role' => $roledesc,
                    'role_id' => $role_id,
                ];
                $sql = "INSERT INTO users (" . $loginFieldName . ",password,role,role_id) VALUES (:username,:password,:role,:role_id)";
                $updateuser = $pdoApps->prepare($sql);
                $updateuser->execute($data);
            }
        }
    }
    public function syncLogs($pdoApps, $app, $loginFieldName)
    {
        $created_at = \Carbon\Carbon::now();
        $array_role = array();
        $users = User::where([['active', 1], ['username', '!=', 'admin']])->get();
        foreach ($users as $user) {

            $selectuser = $pdoApps->prepare("SELECT id from users  where " . $loginFieldName . " = '" . $user->username . "'");
            $selectuser->execute();
            $selectuser->setFetchMode(PDO::FETCH_ASSOC);
            $userid = $selectuser->fetch();
            $logsdatasql = $pdoApps->prepare("SELECT id, user_type,user_id,event,auditable_type,auditable_id,old_values,new_values,url,ip_address,user_agent,created_at,updated_at from audits where isSync = 0 AND user_id = " . $userid['id']);

            $logsdatasql->execute();
            $logsdatasql->setFetchMode(PDO::FETCH_ASSOC);

            for ($i = 0; $row = $logsdatasql->fetch(); $i++) {
                Audit::create([
                    'user_type' => $row['user_type'],
                    'user_id' => $row['user_id'],
                    'event' => $row['event'],
                    'auditable_type' =>  $row['auditable_type'],
                    'auditable_id' => $row['auditable_id'],
                    'old_values' => $row['old_values'],
                    'new_values' => $row['new_values'],
                    'url' => $row['url'],
                    'ip_address' =>  $row['ip_address'],
                    'user_agent' => $row['user_agent'],
                    'created_at' =>  $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'application'    => $app,
                ]);
            }
            $data = [
                'user_id' => $userid['id'],
            ];
            $sql = "UPDATE audits SET  isSync=1 WHERE user_id=:user_id";
            $updateaudit = $pdoApps->prepare($sql);
            $updateaudit->execute($data);
        }
    }
    public function syncSchedShutdown($pdoApps, $app)
    {
        $created_at = \Carbon\Carbon::now();
        $array_users = array();


        $shutdownapps = ShutdownApplication::where('scheduled_date', '>', Carbon::now()->subDays(30))->get();
        foreach ($shutdownapps as $shutdownapp) {
            if ($shutdownapp->apps != null) {
                $apps = explode(',', $shutdownapp->apps);
                foreach ($apps as $appShutdown) {
                    if ($appShutdown == $app) {
                        $selectSchedule = $pdoApps->prepare("SELECT scheduled_date from applications  where scheduled_date = '" . $shutdownapp->scheduled_date . "'");

                        $selectSchedule->execute();
                        $selectSchedule->setFetchMode(PDO::FETCH_ASSOC);
                        $row = $selectSchedule->fetch();

                        if ($row) {
                            $data = [
                                'scheduled_date' => $shutdownapp->scheduled_date,
                                'scheduled_time' =>  $shutdownapp->scheduled_time,
                                'reason' =>  $shutdownapp->reason,
                                'posted_date' => $shutdownapp->posted_date,
                                'posted_time' =>  $shutdownapp->posted_time,
                                'created_at' =>  $shutdownapp->created_at,
                                'updated_at' => $shutdownapp->updated_at,
                                'deleted_at' =>  $shutdownapp->deleted_at,
                            ];
                            $sql = "UPDATE applications SET  scheduled_date=:scheduled_date, scheduled_time=:scheduled_time, reason=:reason,
                            posted_date=:posted_date, posted_time=:posted_time, created_at=:created_at,updated_at=:updated_at, deleted_at=:deleted_at
                             WHERE scheduled_date = '" . $shutdownapp->scheduled_date . "'";
                            $updateSchedule = $pdoApps->prepare($sql);
                            $updateSchedule->execute($data);
                        } else {
                            $data = [
                                'scheduled_date' => $shutdownapp->scheduled_date,
                                'scheduled_time' =>  $shutdownapp->scheduled_time,
                                'reason' =>  $shutdownapp->reason,
                                'posted_date' => $shutdownapp->posted_date,
                                'posted_time' =>  $shutdownapp->posted_time,
                                'created_at' =>  $shutdownapp->created_at,
                                'updated_at' => $shutdownapp->updated_at,
                                'deleted_at' =>  $shutdownapp->deleted_at,
                            ];
                            $sql = "INSERT INTO applications (scheduled_date,scheduled_time,reason,posted_date,posted_time,created_at,updated_at,deleted_at) 
                            VALUES (:scheduled_date,:scheduled_time,:reason,:posted_date,:posted_time,:created_at,:updated_at,:deleted_at)";
                            $createSchedule = $pdoApps->prepare($sql);
                            $createSchedule->execute($data);
                        }
                    }
                }
            }
        }
    }
}
