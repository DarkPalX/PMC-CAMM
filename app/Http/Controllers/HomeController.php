<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;
use App\Services\AccessedAppService;
use App\Services\UserRightService;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AccessedAppService $accessedAppService
    , UserRightService $userRightService
    )
    {
        $this->accessedAppService = $accessedAppService;
        $this->userRightService = $userRightService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->session()->has('firstLogin')) {
            return redirect()->route('auth.change_password');
        } else {
            if (Auth::user()->role != "USER") {
                $rolesPermissions = $this->userRightService->hasPermissions("Dashboard");
                if (!$rolesPermissions['view']) {
                    abort(401);
                }
                $applications = Application::where('active', 1)->get();
                $loggedInUsers = $this->accessedAppService->all();
                return view('index', compact('applications', 'loggedInUsers'));
            } else {
                $users = User::where([['username', '=', Auth::user()->username]])->first();
                $appids = [];
                $listofAppRole = explode('|', $users->appRole);
                foreach ($listofAppRole as $appRole) {

                    $appRoleArr = explode(",", $appRole);
                    $appids[] = $appRoleArr[0];
                }
                $applications = Application::whereIn('id', $appids)->where('id', '!=', 6)->get();
                $expDay = $request->session()->get('expDay');
                return view('employeepage.index', compact('applications', 'expDay'));
            }
        }
    }
}
