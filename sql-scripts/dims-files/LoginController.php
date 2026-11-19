<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\RoleRightService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * index
     *
     * @return void
     */

    private $roleRightService;
    public function __construct(
        RoleRightService $roleRightService
    ) {
        $this->roleRightService = $roleRightService;
    }

    
    public function index(Request $request)
    {
        if ($request->get('username') == null) {
            abort(403);
        }
        $checker = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);

        if ($checker) {
            User::where('id', Auth::id())->update(['isLoggedIn' => 1]);
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->back()->withErrors('Invalid login credentials.');
        }
        // return auth()->check() ? redirect()->route('admin.dashboard.index') : view('admin.login.index');
    }


    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $checker = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);

        if ($checker) {
            // Session::flush();
            // Session::regenerate();
            // Session::put('userid',auth()->id());
            // Session::put('userid',auth()->user()->username);
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->back()->withErrors('Invalid login credentials.');
        }
    }

    public function logout()
    {
        return auth()->logout() ?? redirect()->route('admin.login');
    }

    public function change_password()
    {
        $role = auth()->user()->role;
        if ($role != "ADMIN") {

            $rolesPermissions = $this->roleRightService->hasPermissions("Change Password");

            $view = $rolesPermissions['view'];
            if (!$view) {
                abort(401);
            }
        }

        $id = \Auth::user()->id;
        return view('admin.register.change_password');
    }

    public function updatePassword(Request $request)
    {

        $user = \Auth::user();
        $hasher = app('hash');

        // $validate = $request->validate([
        //     'current_password'      => 'required',
        //     'new_password'          => 'required',
        //     'new_confirm_password'  => 'same:new_password'
        // ]); 


        $validate = $request->validate([
            'current_password'      => 'required',
            'new_password'          => [
                'required', 'string', 'min:8', 'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&._]/'
            ],
            'new_confirm_password'  => 'same:new_password'
        ]);


        if ($hasher->check($request->current_password, $user->password)) {

            $user->update([
                'password'  => Hash::make($request->new_password)
            ]);

            \Session::flash('success', "New Password Successfully Updated, Please Re-Login");
            \Auth::logout();
            return redirect('/');

            //\Auth::logout();
            //return redirect('/');

        }

        \Session::flash('error_message', 'Something is wrong while trying to change the password');

        return back();
    }
    public function adminLogin()
    {
        return view('admin.login.adminlogin');
    }
    public function adminSubmit(Request $request)
    {
        $checker = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);
        if ($checker) {
            if(auth()->user()->role == "ADMIN"){
                return redirect()->route('admin.application.index');
            }
            else
            {
                abort(503);
            }
        } else {
            return redirect()->back()->withErrors('Invalid login credentials.');
        }
    }
    
}
