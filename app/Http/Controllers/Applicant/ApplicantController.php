<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
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

        //$activeproposal = Proposal::whereIn('state', ['in-progress', 'awarded', 'approved 1'])->get();
          $competitonlist = Competition::where('submission_end_date','>=',date('Y-m-d'))
                                ->where('submission_start_date','<=',date('Y-m-d'))
                                ->where('state', 'enable')
                                ->get();

      //  dd(date('Y-m-d') > $competitonlist[0]->submission_end_date);
        return view("applicant.dashboard", compact('competitonlist', 'id'));
    }


    public function __construct()
    {
        //checkUser();
        $this->middleware('sign_in')->except('logout');
    }
}
