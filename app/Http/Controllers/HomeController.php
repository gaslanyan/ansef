<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest:applicant')->except('logout');
        // $this->middleware('guest:admin')->except('logout');
        // $this->middleware('guest:viewer')->except('logout');
        // $this->middleware('guest:referee')->except('logout');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function admin()
    {
        dd('Access admin');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function referee()
    {
        dd('Access Admin and referee');
    }
    public function applicant()
    {
        dd('Access Admin and applicant');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewer()
    {
        dd('Access only viewer and admin');
    }
}
