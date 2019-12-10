<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ResearchBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
         if($type=='board'){
        return view('applicant.email.research');
         }
         else{
             return view('applicant.email.requestadmin');
         }
    }


    public function send(Request $request)
    {

        $referee = Proposal::select('proposal_referees')
            ->where('id', '=', 13)->get()->first();
        //dd($referee['proposal_referees']);
        if (!empty($request->board)) {
            $objSend = new \stdClass();
            $objSend->message = $request->board;
            $objSend->sender = 'Ansef';
            $objSend->receiver = 'collages';

            Mail::to('dopplerthepom@gmail.com')->send(new \App\Mail\ResearchBoard($objSend));
            return redirect()->back()->with('success', getMessage("success"));
        } else
            return back()->withInput($request->only('board', 'remember'));
    }

    public function sendtoadmin(Request $request)
    {


        if (!empty($request->requesttoadmin)) {
            $objSend = new \stdClass();
            $objSend->message = $request->board;
            $objSend->sender = 'Ansef';
            $objSend->receiver = 'collages';

            Mail::to('krist68@mail.ru')->send(new \App\Mail\SendToAdmin($objSend));
            return redirect()->back()->with('success', getMessage("success"));
        } else
            return back()->withInput($request->only('sendtoadmin', 'remember'));
    }

    /***
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
