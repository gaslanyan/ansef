<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Person;
use Request;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        redirect(\Illuminate\Support\Facades\Request::url());
        if ($id !== "null") {
            setcookie('sign_id', $id, 0, '/applicant');
        }

        $competitionlist = Competition::where('submission_end_date','>=',date('Y-m-d'))
                                ->where('submission_start_date','<=',date('Y-m-d'))
                                ->where('state', 'enable')
                                ->get();

        $upcomingcompetitions = Competition::where('announcement_date','<=', date('Y-m-d'))
                                ->where('submission_start_date','>=',date('Y-m-d'))
                                ->get();


        $user_id = \Auth::guard('applicant')->user()->id;
        createperson($user_id);

        return view("applicant.dashboard", compact('competitionlist', 'upcomingcompetitions', 'id'));
    }


    public function __construct()
    {
        //checkUser();
        $this->middleware('sign_in')->except('logout');
    }
}
