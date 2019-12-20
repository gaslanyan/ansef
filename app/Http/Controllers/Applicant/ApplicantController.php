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
        if ($id !== "null") {
            // setcookie('sign_id', $id, 0, '/applicant');
            redirect(\Illuminate\Support\Facades\Request::url());
        }

        $competitionlist = Competition::where('submission_end_date','>=',date('Y-m-d'))
                                ->where('submission_start_date','<=',date('Y-m-d'))
                                ->where('state', 'enable')
                                ->get();

        $upcomingcompetitions = Competition::where('announcement_date','<=', date('Y-m-d'))
                                ->where('submission_start_date','>=',date('Y-m-d'))
                                ->get();


        $user_id = \Auth::guard('applicant')->user()->id;
        createperson($user_id, 'applicant');

        return view("applicant.dashboard", compact('competitionlist', 'upcomingcompetitions', 'id'));
    }

    public function loginAs($id = null)
    {
    }

    public function __construct()
    {
        // $this->except('logout');
    }
}
