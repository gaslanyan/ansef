<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/login';

    public function redirectTo()
    {
        $url = \Illuminate\Support\Facades\Request::fullUrl();
        return 'login.';
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        $role = basename($_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI']);

        if ($role === 'login')
            $role = "applicant";
        return Auth::guard($role);
    }

    /**
     * Show login form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        $role = basename($request->url());

        if ($role === 'login')
            $role = "applicant";
        return view("auth.login", ['url' => $role]);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */


    public function doLogin(Request $request)
    {
        $role = basename($request->url());
        $get_role = Role::where('name', '=', $role)->first();
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::guard($role)->attempt(['email' => $request->email, 'state' => 'active',
            'password' => $request->password,
            'role_id' => $get_role->id],
            $request->get('remember'))) {

//           setcookie('c_user', $role, time()+31556926, '/');
            setcookie('c_user', $role, 0, '/');
//            $success['token'] =  $user->createToken('App')->accessToken;

            $u = Auth::guard($role)->user();
            $session = Session::where('user_id', $u->id)->first();

            if (empty($session))
                $s_user = new Session;
            else
                $s_user = Session::find($session->id);
            $s_user->user_id = $u->id;
            $s_user->domain = $_SERVER['REMOTE_ADDR'];

            $s_user->save();
            $s_user->touch();
            $request->session()->put('u_id', $u->id);
            return Redirect::to($role);
        } else {
            if ($get_role->name !== "applicant")
                return Redirect::back()->with('status', getMessage("waiting"));
            else
                return Redirect::back()->with('status', getMessage("status"));
            // return back()->with('error', getMessage("error"));
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

//        $this->middleware('check-role');
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:applicant')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:superadmin')->except('logout');
        $this->middleware('guest:viewer')->except('logout');
        $this->middleware('guest:referee')->except('logout');

    }

    public function logout(Request $request)
    {
//        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        $role = $_COOKIE['c_user'];
        setcookie('c_user', $role, time() - 31556926, '/');
        return redirect('/');
    }
}
