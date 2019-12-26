<?php

namespace App\Http\Controllers\Referee;

use App\Models\User;
use App\Models\Session;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RefereeController extends Controller
{
    public function index($id = null)
    {
        $user_id = getUserID();
        createperson($user_id, 'referee');

        return view("referee.dashboard", compact('user_id'));
    }

    public function signAs($id) {
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
        createperson($user_id, 'referee');
        return Redirect::to($newrole);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
