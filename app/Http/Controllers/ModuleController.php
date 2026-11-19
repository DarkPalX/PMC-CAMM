<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Application;
use App\Services\UserRightService;
use Illuminate\Support\Facades\Session;

class ModuleController extends Controller
{
    public function __construct( UserRightService $userrightService
    )
    {
        $this->userrightService = $userrightService;
    }
    public function index()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Modules");
        if (!$rolesPermissions['view']) {
            abort(401);
        }
        $modules = Module::where('application',6)->orderBy('description','asc')->get();

        $modules = json_encode($modules);
        return view('module.index', compact('modules'));
  
    }

    public function getModules(){
        $modules = Module::where('active', 1)->get();
        return $modules;
    }    

    public function getModule_selected(){
        $modules = Module::where('active', 1)->where([['application', '=', '6']])->get();
        return $modules;
    }        

    public function create()
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Modules");
        if (!$rolesPermissions['create']) {
            abort(401);
        }
        return view('module.create');
    }

    public function store(Request $request) 
    {
        $request->validate([            
            'description' => 'required',
        ]);
		
        $created_at = \Carbon\Carbon::now();


        $module = Module::create([
            'application' => '6',
            'description' => $request->description,
            'active' => 1,
            'created_at' => $created_at
        ]);
        $returnedModule = Module::find($module->id);
        $data = [
            'app_module_id' => $module->id,
        ];
        $returnedModule->update($data);
        return redirect('/modules/create')->with('success', 'Module has been saved!!');        
	}

    public function show(Module $module)
    {
        //
    }

    public function edit($id)
    {
        $rolesPermissions = $this->userrightService->hasPermissions("Modules");
        if (!$rolesPermissions['edit']) {
            abort(401);
        }
        $module = Module::where('id', $id)->first();
        return view('module.edit', compact('module'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $updated_at = \Carbon\Carbon::now();

        $module = Module::find($request->id);

        $data = [
            'description' => $request->description,
            'active'    => $request->active,
            'updated_at' => $updated_at
        ];

        $module->update($data);
        return redirect()->route('modules.index')->with('success', 'Module has been updated!!');                     

    }

    public function destroy(Module $module)
    {
        //
    }
    public function applicationsList(Application $application)
    {
        $applications = $application->orderBy('description','asc')->get();

        // dd($applications);
        return $applications;
    }
}
