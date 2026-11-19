<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use App\Models\UsersPermissions;
use App\Services\AccessedAppService;
use Illuminate\Support\Facades\Session;
use App\Services\UserService;
use App\Services\UserRightService;
use Carbon\Carbon;
use Exception;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PDO;

class UserController extends Controller
{
    public function __construct(
        UserRightService $userrightService,
        UserService $userService,
        AccessedAppService $accessedAppService
    ) {
        $this->userService = $userService;
        $this->userrightService = $userrightService;
        $this->accessedAppService = $accessedAppService;
    }
    public function index()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Users");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        return view('user.index');
    }

    public function getUsers()
    {
        $users = User::where([['active', 1], ['username', '<>', 'ADMIN']])->get();
        $new_users = $users->map(
            function ($user, $key) {
                return $user;
            }
        );

        return $new_users;
    }

    public function getAllUsers()
    {
        $users = User::where([['username', '<>', 'ADMIN']])->orderBy('name', 'asc')->get();

        return $users;
    }

    public function create()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Users");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        $permissions = Permission::where('active', 1)->get();
        $applications = Application::where('active', 1)->orderBy('description', 'asc')->get();
        return view('user.create', compact('applications', 'permissions'));
    }
    public static function employee_lookup()
    {
        $streamContext = stream_context_create([
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false
            ]
        ]);
        $employees = file_get_contents(config('app.api_path') . "hris-api.php", false, $streamContext);
        // $employees = file_get_contents("https://localhost/camm/api/hris-api.php", false, $streamContext);
        return $employees;
    }
    public static function userList()
    {

        $users = User::where('active', 1)->get();

        return json_encode($users);
    }
    public function store(UserRequest $userRequest, User $user)
    {
        // $userRequest->file('imageFile')->move(public_path('file/'), $userRequest->file('imageFile')->getClientOriginalName());
        $filename = $userRequest->file('imageFile')->getClientOriginalName();

        $userRequest->file('imageFile')->storeAs(('public/uploaded/' . $userRequest->username), $filename);

        $imageFile  = $userRequest->file('imageFile')->getClientOriginalName();

        $createdUser = $this->userService->create($userRequest, $imageFile);
        $appRole = $this->userService->saveAppRole($userRequest->appRole, $createdUser->id);
        $accessRights = $this->userrightService->create($userRequest);
        $this->updateP1users($userRequest);
        $this->syncUserPerm($userRequest);

        return redirect('/users/create')->with('success', 'User has been saved!!');
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $user = User::find($request->id);

        $data = [
            'active' => 0,
        ];
        $user->update($data);
        return redirect()->route('users.index');
    }

    public function reset(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);

        $user = User::find($request->id);

        $data = [
            'password' => \Hash::make("password"),
            'dtpassexpiration' => Carbon::now()->addDays(90),
        ];
        $user->update($data);
        $this->resetPass($request);
        return redirect()->route('users.index');
    }

    public function activate(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);

        $user = User::find($request->id);

        $data = [
            'active' => 1,
        ];
        $user->update($data);
        $this->updateP1users($request);
        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Users");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $applications = Application::orderBy('description', 'asc')->get();
        $user = User::where('id', $id)->first();
        $permissions = Permission::where('active', 1)->get();
        return view('user.edit', compact('user', 'applications', 'permissions'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'imageFile' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'appRole' => 'required|string',
            'userPermissions' => 'required|string',
            'role_id'   => 'string',
        ]);
        $fileExists = Storage::exists('public/uploaded/' . $request->username . '/' . $request->filePath);
        $historyFile = 'public/history/' . $request->username . '/' . Carbon::now()->toDateString() . '_' .  $request->filePath;
        if ($fileExists) {
            if (Storage::exists($historyFile)) {
                Storage::delete($historyFile);
            }
            Storage::move(('public/uploaded/' . $request->username . '/' . $request->filePath), ('public/history/' . $request->username . '/' .
                Carbon::now()->toDateString() . '_' .  $request->filePath));
        }
        $filename = $request->file('imageFile')->getClientOriginalName();
        $ext = $request->file('imageFile')->extension();
        $request->file('imageFile')->storeAs(('public/uploaded/' . $request->username), $filename);
        $imageFile  = $request->file('imageFile')->getClientOriginalName();

        $createdUser = $this->userService->update($request, $imageFile);

        $accessRights = $this->userrightService->create($request);
        $this->updateP1users($request);
        $this->syncUserPerm($request);

        return response()->json("User Successfully updated!");
    }
    public function getAccessRights(Request $request)
    {
        $users_permissions = $this->userrightService->getByUsername($request->username, $request->appid);
        return $users_permissions;
    }
    public function getLoggedIn(Request $request)
    {
        $loggedInUsers = $this->accessedAppService->all();
        return $loggedInUsers;
    }
    public function updateP1users($request)
    {
        try {
            $appRoles = explode('|', $request->appRole);
            foreach ($appRoles as $appRoleArr) {
                $appRole = explode(',', $appRoleArr);
                if ($appRole[0] != 6) {
                    $application = Application::where('id', $appRole[0])->first();
                    $conn_a['app']['type'] = config('app.db_connection');
                    $conn_a['app']['host'] =   $application->server_name; //'192.155.86.62';
                    $conn_a['app']['name'] = $application->database_name;
                    $conn_a['app']['uname'] = $application->dbusername;
                    $conn_a['app']['pword'] = $application->dbpass;

                    $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

                    $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $selectuser = $pdoApps->prepare("SELECT id from users  where " . $application->loginFieldName . " = '" . $request->username . "'");
                    $selectuser->execute();
                    $selectuser->setFetchMode(PDO::FETCH_ASSOC);
                    $row = $selectuser->fetch();
                    $appRoles = explode('|', $request->appRole);
                    $roledesc = "";
                    $role_id = 0;
                    foreach ($appRoles as $appRoleArr) {
                        $appRole = explode(',', $appRoleArr);
                        if ($appRole[0] == $application->id) {
                            $role = Role::where([['app_role_id', $appRole[1]], ['app', $application->description]])->first();
                            $roledesc = $role->description;
                            $role_id = $role->app_role_id;
                        }
                    }
                    if ($row) {
                        $data = [
                            'role' => $roledesc,
                            'role_id' => $role_id,
                        ];
                        $sql = "UPDATE users SET  role=:role, role_id=:role_id WHERE " . $application->loginFieldName . " = '" . $request->username . "'";
                        $updateuser = $pdoApps->prepare($sql);
                        $updateuser->execute($data);
                    } else {
                        $data = [
                            'username' => $request->username,
                            'password' => Hash::make("password"),
                            'role' => $roledesc,
                            'role_id' => $role_id,
                        ];
                        $sql = "INSERT INTO users (" . $application->loginFieldName . ",password,role,role_id) VALUES (:username,:password,:role,:role_id)";
                        $updateuser = $pdoApps->prepare($sql);
                        $updateuser->execute($data);
                    }
                }
                // return response()->json('success');
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function syncUserPerm($user)
    {
        try {
            $appRoles = explode('|', $user->appRole);
            foreach ($appRoles as $appRoleArr) {
                $appRole = explode(',', $appRoleArr);

                if ($appRole[0] != 6) {
                    $application = Application::where('id', $appRole[0])->first();
                    $conn_a['app']['type'] = config('app.db_connection');
                    $conn_a['app']['host'] =   $application->server_name; //'192.155.86.62';
                    $conn_a['app']['name'] = $application->database_name;
                    $conn_a['app']['uname'] = $application->dbusername;
                    $conn_a['app']['pword'] = $application->dbpass;

                    $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

                    $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $userPermissions = UsersPermissions::where([['app_id', $application->id], ['username', '!=', 'admin']])->get();

                    $selectuser = $pdoApps->prepare("SELECT id from users  where " . $application->loginFieldName . " = '" . $user->username . "'");
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
            // return response()->json('success');

        } catch (Exception $e) {
            dd($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function resetPass($request)
    {
        try {
            $user = User::WHERE('id', $request->id)->first();

            $applications = Application::where('active', 1)->get();

            foreach ($applications as $application) {
                $databasename = $application->database_name;
                if ($databasename == null) {
                    $databasename = "";
                }
                if ($databasename != "") {
                    $conn_a['app']['type'] = config('app.db_connection');
                    $conn_a['app']['host'] = $application->server_name;;
                    $conn_a['app']['name'] = $databasename;
                    $conn_a['app']['uname'] = $application->dbusername;
                    $conn_a['app']['pword'] = $application->dbpass;

                    $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);
                    $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $data = [
                        'password' => Hash::make("password"),
                        'username' => $user->username,
                    ];
                    $sql = "UPDATE users SET password=:password WHERE " . $application->loginFieldName . "=:username";
                    $pdoApps->prepare($sql)->execute($data);
                }
            }
            return response()->json('success');

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
