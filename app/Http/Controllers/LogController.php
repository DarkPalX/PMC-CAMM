<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Services\UserRightService;
use Illuminate\Support\Facades\Session;
use \OwenIt\Auditing\Models\Audit;

class LogController extends Controller
{
    public function __construct( UserRightService $userrightService
    )
    {
        $this->userrightService = $userrightService;
    }
    public function index(Request $request)
    {
            $rolesPermissions = $this->userrightService->hasPermissions("User Logs");
            if (!$rolesPermissions['view']) {
                abort(401);
            }
        $applications = Application::where('active',1)->get();
        return view('log.index',compact('applications'));
    }
    public function getLogs(Request $request)
    {   
        $dateFrom = now()->toDateString();
        $dateTo = now()->toDateString();
        $app = 0;
        if (isset($request->dateFrom)) {
            $dateFrom = $request->dateFrom;
        }
        if (isset($request->dateTo)) {
            $dateTo = $request->dateTo;
        } if (isset($request->app)) {
            $app = $request->app;
        }
        $audits = Audit::select('audits.*', 'u.username','a.description as applicationdesc')
             ->leftjoin('users as u', 'audits.user_id', '=', 'u.id')
             ->leftjoin('applications as a', 'audits.application', '=', 'a.id')
             ->when(isset($dateTo), function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('audits.created_at',  [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        })
            ->when(!isset($dateTo), function ($q) use ($dateFrom) {
                $q->whereDate('created_at', $dateFrom);
            })->when($app != 0, function ($q) use ($app) {
                $q->where('application', $app);
            })
            ->orderBy('created_at', 'desc')->get();
            
        return $audits;
    }
}
