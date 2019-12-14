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
        $proposal = Proposal::find($id);
        $due_date = date('Y-m-d');
        if($proposal->competition->first_report >= date('Y-m-d'))
            $due_date = $proposal->competition->first_report;
        else if($proposal->competition->second_report >= date('Y-m-d') && $proposal->competition->first_report < date('Y-m-d'))
            $due_date = $proposal->competition->second_report;
        else $due_date = $proposal->competition->second_report;

        $due_date = $proposal->competition->first_report;
        $report = ProposalReports::firstOrCreate([
            'proposal_id' => $id,
            'due_date' => $due_date
        ], []);

        $document = $report->document;
        $repid = $report->id;

        return view('applicant.proposal.report_upload', compact('id', 'repid', 'document'));
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

        return redirect()->back()->withErrors(['The file was uploaded successfully.']);;
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
        return  redirect()->back()->withErrors(['Thank you, the file was uploaded successfully. You may now close the browser window.']);
    }

    function uploadreport(Request $request)
    {
        $rules = array(
            'file'  => 'required|mimes:pdf|max:20480'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return  redirect()->back()->withErrors(['There was an error uploading the file. Please try again, or contact dopplerthepom@gmail.com for help.']);;
        }

        $image = $request->file('file');
        $proposal = Proposal::find($request->prop_id);
        $new_name = 'report-' . $request->rep_id . '.pdf';
        $path = storage_path('/proposal/prop-' . $proposal->id . '/');
        $image->move($path, $new_name);

        $report = ProposalReports::find($request->rep_id);
        $report->document = $new_name;
        $report->approved = '0';
        $report->save();

        return  redirect()->back()->withErrors(['The file was uploaded successfully.']);
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
        $report = ProposalReports::find($id);
        if ($request->file('file') != "" || $report->document != "") {

            if (is_file(storage_path('/proposal/prop-' . $report->proposal_id . '/' . $report->document))) {
                unlink(storage_path('/proposal/prop-' . $report->proposal_id . '/' . $report->document));
            } else {
                echo "File does not exist";
            }
            $report->document = "";
            $report->save();
        }
        return  redirect()->back();
    }
}
