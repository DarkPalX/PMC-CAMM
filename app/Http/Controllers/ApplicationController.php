<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Application;
use App\Services\UserRightService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;

class ApplicationController extends Controller
{
    public function __construct( UserRightService $userrightService
    )
    {
        $this->userrightService = $userrightService;
    }
    public function index(Request $request)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Applications");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $applications = Application::orderBy('description', 'asc')->get();
        $applications = json_encode($applications);
        return view('application.index', compact('applications'));
    }

    public function create()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Applications");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        $applications = Application::orderBy('description','asc')->get();
        return view('application.create', compact('applications'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'server_name' => 'required',
            'database_name' => 'required',
            'database_username' => 'required',
            'database_password'   => 'required',
            'application_url'   => 'required',
            'loginFieldName' => 'required',
        ]);

        $created_at = \Carbon\Carbon::now();

        Application::create([
            'description' => $request->description,
            'server_name' => $request->server_name,
            'database_name' => $request->database_name,
            'dbusername' => $request->database_username,
            'dbpass'   => $request->database_password,
            'app_url'   => $request->application_url,
            'active' => 1,
            'created_at' => $created_at,                   
            'loginFieldName' => $request->loginFieldName,    
        ]);

        return redirect('/applications/create')->with('success', 'Application has been saved!!');
    }

    public function edit($id)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Applications");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $application = Application::where('id', $id)->first();
        return view('application.edit', compact('application'));
    }

    public function getApplications()
    {
        $applications = Application::get();
        return $applications;
    }


    public function update(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'server_name' => 'required',
            'database_name' => 'required',
            'database_username' => 'required',
            'database_password' => 'required',            
            'application_url' => 'required',
            'loginFieldName' => 'required',
        ]);

        $updated_at = \Carbon\Carbon::now();

        $application = Application::find($request->id);

        $data = [
            'description' => $request->description,
            'server_name' => $request->server_name,
            'database_name' => $request->database_name,
            'dbusername' => $request->database_username,
            'dbpass' => $request->database_password,
            'app_url' => $request->application_url,

            'description' => $request->description,
            'active'    => $request->active,
            'updated_at' => $updated_at,
            'loginFieldName' => $request->loginFieldName,
        ];

        $application->update($data);
        return redirect()->route('applications.index')->with('success', 'Application has been updated!!');
    }    

}
