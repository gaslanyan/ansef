<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('home');
    }


    public function admin()
    {
        dd('Access admin');
    }


    public function referee()
    {
        dd('Access Admin and referee');
    }

    public function applicant()
    {
        dd('Access Admin and applicant');
    }

    public function viewer()
    {
        dd('Access only viewer and admin');
    }
}
