<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Recommendations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($prop_id, $person_id)
    {

        $person_id = $person_id;
        $rec = Recommendations::select('text','proposal_id')->where('person_id',$person_id)
                                              ->where('proposal_id',$prop_id)->get()->first();
        return view("support", compact('person_id','rec'));

    }
    public function save(Request $request,$person_id)
    {
         if(!empty($request->support_text)){

             DB::table('recommendations')->where(['person_id'=>$person_id , 'proposal_id'=>$request->supp_prop_id])
                                               ->update(['text' => $request->support_text]);

         }

        return view("thankyou_support");
    }


    public function __construct()
    {
        //checkUser();
        $this->middleware('sign_in')->except('logout');
    }
}
