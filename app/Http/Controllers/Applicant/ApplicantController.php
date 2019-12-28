<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\User;
use App\Models\Session;
use \Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ApplicantController extends Controller
{
    public function index($id = null)
    {
        $competitionlist = Competition::where('submission_end_date', '>=', date('Y-m-d'))
            ->where('submission_start_date', '<=', date('Y-m-d'))
            ->where('state', 'enable')
            ->get();

        $upcomingcompetitions = Competition::where('announcement_date', '<=', date('Y-m-d'))
            ->where('submission_start_date', '>=', date('Y-m-d'))
            ->get();

        $user_id = getUserID();
        createperson($user_id, 'applicant');
        return view("applicant.dashboard", compact('competitionlist', 'upcomingcompetitions', 'id'));
    }

    public function signAs($id)
    {
        $newuser = User::find($id);
        $newrole = $newuser->role->name;

        Request::session()->flush();
        Request::session()->regenerate();
        $role = get_role_cookie();
        Auth::guard($role)->logout();
        if (!empty($role)) setcookie('user_role', $role, time() - 31556926, '/');

        setcookie('user_role', $newuser->role->name, 0, '/');
        Auth::guard($newrole)->loginUsingId($newuser->id, true);
        $session = Session::where('user_id', $newuser->id)->first();

        if (empty($session))
            $s_user = new Session;
        else
            $s_user = Session::find($session->id);
        $s_user->user_id = $newuser->id;
        $s_user->domain = $_SERVER['REMOTE_ADDR'];

        $s_user->save();
        $s_user->touch();
        Request::session()->put('u_id', $newuser->id);
        $user_id = $newuser->id;
        createperson($user_id, 'applicant');
        return Redirect::to($newrole);
    }

    public function __construct()
    {
    }
}
