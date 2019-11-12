<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class FileUploadController extends Controller
{
    function index($id)
    {


        return view('applicant.proposal.file_upload',compact('id'));
    }

    function upload(Request $request)
    {

       $rules = array(
            'file'  => 'required|mimes:pdf|max:12048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('file');

//      $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $new_name =$image->getClientOriginalName();
        $path = storage_path('/proposal/prop-' . $request->prop_id_file . '/');
        //$image->move(public_path('images'), $new_name);
        $image->move($path, $new_name);

        $proposal = Proposal::find($request->prop_id_file);
        $proposal->document = $new_name;
        $proposal->save();
        $output = array(
            'success' => 'Document uploaded successfully',
//          'image'  => '<img src="/images/'.$new_name.'" class="img-thumbnail" />'
            'pdf' => $new_name
        );

        return response()->json($output);
    }


    function uploadreport(Request $request)
    {

        $rules = array(
            'report_file'  => 'required|mimes:pdf|max:12048'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $doc = $request->file('report_file');
        $reportdesc = $request->report_description;

//        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $new_name =$doc->getClientOriginalName();
        $path = storage_path('/proposal/prop-' . $request->prop_id_file . '/');
        //$image->move(public_path('images'), $new_name);
        $doc->move($path, $new_name);

        $proposalrep = new ProposalReports();
        $proposalrep->document = $new_name;
        $proposalrep->description = $reportdesc;
        $proposalrep->proposal_id = $request->prop_id_file;
        $proposalrep->due_date	 = date("Y-m-d");
        $proposalrep->save();
        $output = array(
            'success' => 'Document uploaded successfully',
//          'image'  => '<img src="/images/'.$new_name.'" class="img-thumbnail" />'
            'pdf' => $new_name
        );

        return response()->json($output);
    }

    public function remove(Request $request,$id)
    {
        $proposal = Proposal::find($id);
        if($request->file('file') != "" || $proposal->document != ""){

        if(is_file(storage_path('/proposal/prop-'.$proposal->id.'/'.$proposal->document)))
        {
            unlink(storage_path('/proposal/prop-'.$proposal->id.'/'.$proposal->document));
        }
        else
        {
            echo "File does not exist";
        }
        $proposal->document = "";
        $proposal->save();
        }
      return  redirect()->action('Applicant\ProposalController@activeProposal');
}

public function removereport(Request $request,$id)
    {
        if($request->file('report_file') != ""){
        $proposal = ProposalReports::find($id);
        if(is_file(storage_path('/proposal/prop-'.$proposal->id.'/'.$proposal->document)))
        {
            unlink(storage_path('/proposal/prop-'.$proposal->id.'/'.$proposal->document));
        }
        else
        {
            echo "File does not exist";
        }

        $proposal->delete();;
        }
      return  redirect()->action('Applicant\ProposalController@activeProposal');
}
}
