<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalReports;
use App\Models\Recommendations;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class FileUploadController extends Controller
{
    function index($id)
    {
        $document = Proposal::find($id)->document;

        return view('applicant.proposal.file_upload',compact('id', 'document'));
    }

    function docfile($id)
    {
        $document = Proposal::find($id)->document;

        return view('applicant.proposal.file_upload', compact('id', 'document'));
    }

    function letterfile(Request $request)
    {
        $input = $request->all();
        $id = $input['rid'];
        $rec = Recommendations::find($id);
        $pi = Person::find($rec->person_id);
        $prop = Proposal::find($rec->proposal_id)->id;
        if (!empty($rec)) {
            if ($rec->confirmation == $input['confirmation']) {
                $document = $rec->document;
                return view('applicant.proposal.recletter', compact('id', 'document', 'pi', 'prop'));
            }
        }
        return view('applicant.proposal.recletterdeny');
    }

    function reportfile($id)
    {
        $document = Proposal::find($id)->document;

        return view('applicant.proposal.file_upload', compact('id', 'document'));
    }

    function upload(Request $request)
    {
        $rules = array(
            'file'  => 'required|mimes:pdf|max:20480'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('file');

        // $new_name =$image->getClientOriginalName();
        $new_name ='document.pdf';
        $path = storage_path('/proposal/prop-' . $request->prop_id_file . '/');
        $image->move($path, $new_name);

        $proposal = Proposal::find($request->prop_id_file);
        $proposal->document = $new_name;
        $proposal->save();
        $output = array(
            'success' => 'Document uploaded successfully',
            'pdf' => $new_name
        );

        return response()->json($output);
    }

    function uploadletter(Request $request) {
        $rules = array(
            'file'  => 'required|mimes:pdf|max:20480'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return  redirect()->back()->withErrors(['There was an error uploading the file. Please try again, or contact dopplerthepom@gmail.com for help.']);;
        }

        $image = $request->file('file');
        $prop = $request->prop_id_file;
        $rec = $request->rec_id_file;
        $new_name = 'letter-' . $rec . '.pdf';
        $path = storage_path('/proposal/prop-' . $prop . '/');
        $image->move($path, $new_name);

        $recommendation = Recommendations::find($rec);
        $recommendation->document = $new_name;
        $recommendation->save();
        $output = array(
            'success' => 'Document uploaded successfully',
            'pdf' => $new_name
        );
        return  redirect()->back()->withErrors(['Thank you, the file was uploaded successfully. You may now close the browser window.']);;
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

    public function removeletter(Request $request, $id)
    {
        $recommendation = Recommendations::find($id);
        if ($request->file('file') != "" || $recommendation->document != "") {
            if (is_file(storage_path('/proposal/prop-' . $recommendation->proposal_id . '/' . $recommendation->document))) {
                unlink(storage_path('/proposal/prop-' . $recommendation->proposal_id . '/' . $recommendation->document));
            } else {
                echo "File does not exist";
            }
            $recommendation->document = null;
            $recommendation->save();
        }
        return  redirect()->back();
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
