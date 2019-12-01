<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $guard = 'admin';
    public function index()
    {
        ini_set('memory_limit', '512M');
        try {
        $proposals = Competition::with('proposalsCount')->get();

        return view('admin.dashboard', compact('proposals'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/index')->with('error', getMessage("wrong"));
        }

    }


    public function __construct()
    {

//        $this->middleware('auth');
//        $this->middleware('check-role');

        $this->middleware('sign_in')->except('logout');

    }
}
