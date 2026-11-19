<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ShutdownApplication;
use App\Services\UserRightService;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use PDO;

class ShutdownApplicationController extends Controller
{
    public function __construct(
        UserRightService $userrightService
    ) {
        $this->userrightService = $userrightService;
    }
    public function index(Request $request)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Shutdown Applications");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $shutdownapplications = ShutdownApplication::where('deleted_at', null)->get();
        $shutdownapplications = json_encode($shutdownapplications);
        return view('shutdownapplication.index', compact('shutdownapplications'));
    }
    public function getShutdownApplications()
    {
        $shutdownapplications = ShutdownApplication::where('deleted_at', null)->get();
        return $shutdownapplications;
    }
    public function create()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Shutdown Applications");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        $applications = Application::orderBy('description', 'asc')->get();
        return view('shutdownapplication.create', compact('applications'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'scheduled_date' => 'required',
            'scheduled_time' => 'required',
            'reason' => 'required',
            'apps'   => 'required',

        ]);
        ShutdownApplication::create([
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'reason' => $request->reason,
            'apps'   => $request->apps,
        ]);
        $this->syncSchedShutdown($request, 'create');

        return redirect('/shutdownapplications/create')->with('success', 'Shutdown Application has been saved!!');
    }
    public function edit($id)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Shutdown Applications");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $applications = Application::orderBy('description', 'asc')->get();
        $shutdownapplication = ShutdownApplication::where('id', $id)->first();
        return view('shutdownapplication.edit', compact('shutdownapplication', 'applications'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'scheduled_date' => 'required',
            'scheduled_time' => 'required',
            'reason' => 'required',
            'apps'   => 'required',

        ]);
        $shutdownapplication = ShutdownApplication::find($request->id);

        $data = [
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'reason' => $request->reason,
            'apps'   => $request->apps,
        ];


        $shutdownapplication->update($data);
        $this->syncSchedShutdown($request, 'update');
        return redirect()->route('shutdownapplications.index')->with('success', 'Shutdown Application has been updated!!');
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $shutdownapplication = ShutdownApplication::find($request->id);

        $data = [
            'deleted_at' => Carbon::now(),
        ];
        $shutdownapplication->update($data);
        $this->syncSchedShutdown($request, 'delete');
        return response()->json('Success');
    }
    public function syncSchedShutdown($request, $mode)
    {
        try {
            if ($mode != 'create') {
                $request = ShutdownApplication::where('id',$request->id)->first();
            }
            $apps = explode('|', $request->apps);
            foreach ($apps as $appArr) {
                $app = explode(',', $appArr);
                if ($app[0] != 6) {
                    $application = Application::where('id', $app[0])->first();
                    $conn_a['app']['type'] = config('app.db_connection');
                    $conn_a['app']['host'] =   $application->server_name; //'192.155.86.62';
                    $conn_a['app']['name'] = $application->database_name;
                    $conn_a['app']['uname'] = $application->dbusername;
                    $conn_a['app']['pword'] = $application->dbpass;

                    $pdoApps = new PDO($conn_a['app']['type'] . ":server=" . $conn_a['app']['host'] . ";Database=" . $conn_a['app']['name'], $conn_a['app']['uname'], $conn_a['app']['pword']);

                    $pdoApps->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $selectSchedule = $pdoApps->prepare("SELECT scheduled_date from applications  where scheduled_date = '" . $request->scheduled_date . "'");

                    $selectSchedule->execute();
                    $selectSchedule->setFetchMode(PDO::FETCH_ASSOC);
                    $row = $selectSchedule->fetch();
                    if ($row) {

                        $data = [
                            'scheduled_date' => $request->scheduled_date,
                            'scheduled_time' =>  $request->scheduled_time,
                            'reason' =>  $request->reason,
                            'posted_date' => $request->posted_date,
                            'posted_time' =>  $request->posted_time,
                            'created_at' =>  $request->created_at,
                            'updated_at' => $request->updated_at,
                            'deleted_at' =>  $request->deleted_at,
                        ];
                        $sql = "UPDATE applications SET  scheduled_date=:scheduled_date, scheduled_time=:scheduled_time, reason=:reason,
                        posted_date=:posted_date, posted_time=:posted_time, created_at=:created_at,updated_at=:updated_at, deleted_at=:deleted_at
                         WHERE scheduled_date = '" . $request->scheduled_date . "'";
                        $updateSchedule = $pdoApps->prepare($sql);
                        $updateSchedule->execute($data);
                    } else {

                        $data = [
                            'scheduled_date' => $request->scheduled_date,
                            'scheduled_time' =>  $request->scheduled_time,
                            'reason' =>  $request->reason,
                            'posted_date' => $request->posted_date,
                            'posted_time' =>  $request->posted_time,
                            'created_at' =>  $request->created_at,
                            'updated_at' => $request->updated_at,
                            'deleted_at' =>  $request->deleted_at,
                        ];
                        $sql = "INSERT INTO applications (scheduled_date,scheduled_time,reason,posted_date,posted_time,created_at,updated_at,deleted_at) 
                        VALUES (:scheduled_date,:scheduled_time,:reason,:posted_date,:posted_time,:created_at,:updated_at,:deleted_at)";
                        $createSchedule = $pdoApps->prepare($sql);
                        $createSchedule->execute($data);
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
