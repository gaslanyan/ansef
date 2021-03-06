<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalReport;
use App\Models\Recommendation;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class FileUploadController extends Controller
{
    function index($id)
    {
        $user_id = getUserID();
        $document = Proposal::where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first()->document;

        return view('applicant.proposal.file_upload', compact('id', 'document'));
    }

    function docfile($id)
    {
        $user_id = getUserID();
        $document = Proposal::where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first()->document;

        return view('applicant.proposal.file_upload', compact('id', 'document'));
    }

    function letterfile(Request $request)
    {
        $input = $request->all();
        $rid = $input['rid'];
        $rec = Recommendation::find($rid);
        $pi = Person::find($rec->person_id);
        $prop = Proposal::find($rec->proposal_id)->id;
        if (!empty($rec)) {
            if ($rec->confirmation == $input['confirmation']) {
                $document = $rec->document;
                return view('applicant.proposal.recletter', compact('rid', 'document', 'pi', 'prop'));
            }
        }
        return view('applicant.proposal.recletterdeny');
    }

    function reportfile($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $due_date = date('Y-m-d');
        if ($proposal->competition->first_report >= date('Y-m-d'))
            $due_date = $proposal->competition->first_report;
        else if ($proposal->competition->second_report >= date('Y-m-d') && $proposal->competition->first_report < date('Y-m-d'))
            $due_date = $proposal->competition->second_report;
        else $due_date = $proposal->competition->second_report;

        $due_date = $proposal->competition->first_report;
        $report = ProposalReport::updateOrCreate([
            'proposal_id' => $id,
            'due_date' => $due_date,
            'user_id' => $user_id
        ], []);

        $document = $report->document;
        $repid = $report->id;

        return view('applicant.proposal.report_upload', compact('id', 'repid', 'document'));
    }

    function upload(Request $request)
    {
        $user_id = getUserID();
        try {
            $rules = array(
                'file'  => 'required|mimes:pdf|max:10240'
            );
            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $proposal = Proposal::where('id', '=', $request->prop_id_file)
                ->where('user_id', '=', $user_id)
                ->first();

            $request->file('file')->storeAs(
                '/proposals/prop-' . $request->prop_id_file,
                'document.pdf'
            );

            $proposal->document = Uuid::generate()->string;
            $proposal->save();

            return redirect()->back()->withErrors(['The file was uploaded successfully.']);;
        } catch (\Exception $exception) {
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    function uploadletter(Request $request)
    {
        $rules = array(
            'file'  => 'required|mimes:pdf|max:20480'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return  redirect()->back()->withErrors(['There was an error uploading the file. Please try again, or contact ' . config('emails.webmaster') . ' for help.']);;
        }

        $request->file('file')->storeAs(
            '/proposals/prop-' . $request->pid,
            'letter-' . $request->rid . '.pdf'
        );

        $recommendation = Recommendation::find($request->rid);
        $recommendation->document = Uuid::generate()->string;
        $recommendation->save();

        // return  redirect()->back()->withErrors(['Thank you, the file was uploaded successfully. You may now close the browser window.'])->with('rid', $request->rid);
    }

    function uploadreport(Request $request)
    {
        $user_id = getUserID();
        $rules = array(
            'file'  => 'required|mimes:pdf|max:20480'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return  redirect()->back()->withErrors(['There was an error uploading the file. Please try again, or contact ' . config('emails.webmaster') . ' for help.']);;
        }

        $report = ProposalReport::where('id', '=', $request->rep_id)
            ->where('user_id', '=', $user_id)
            ->first();

        $request->file('file')->storeAs(
            '/proposals/prop-' . $request->prop_id,
            'report-' . $request->rep_id . '.pdf'
        );

        $report->document = Uuid::generate()->string;
        $report->approved = '0';
        $report->save();

        return  redirect()->back()->withErrors(['The file was uploaded successfully.']);
    }

    public function remove(Request $request, $uuid)
    {
        $user_id = getUserID();
        $proposal = Proposal::where('document', '=', $uuid)
            ->where('user_id', '=', $user_id)
            ->firstOrFail();
        if (Storage::has("proposals/prop-" . $proposal->id . "/document.pdf"))
            Storage::delete("proposals/prop-" . $proposal->id . "/document.pdf");
        $proposal->document = "";
        $proposal->save();
        return  redirect()->action('Applicant\ProposalController@activeProposal');
    }

    public function removeletter(Request $request, $uuid)
    {
        $recommendation = Recommendation::where('document', '=', $uuid)->firstOrFail();
        if (Storage::has("proposals/prop-" . $recommendation->proposal_id . "/letter-" . $recommendation->id . ".pdf"))
            Storage::delete("proposals/prop-" . $recommendation->proposal_id . "/letter-" . $recommendation->id . ".pdf");

        $recommendation->document = "";
        $recommendation->save();
        return  redirect()->back();
    }

    public function removereport(Request $request, $uuid)
    {
        $user_id = getUserID();
        $report = ProposalReport::where('document', '=', $uuid)
            ->where('user_id', '=', $user_id)
            ->firstOrFail();

        if (Storage::has("proposals/prop-" . $report->proposal_id . "/letter-" . $report->id . ".pdf"))
            Storage::delete("proposals/prop-" . $report->proposal_id . "/letter-" . $report->id . ".pdf");

        $report->document = "";
        $report->save();
        return  redirect()->back();
    }


    public function downloadfile($uuid)
    {
        $user_id = getUserID();
        $proposal = Proposal::where('document', '=', $uuid)
            ->firstOrFail();
        if (Storage::exists(ppath($proposal->id) . "/document.pdf"))
            return response()->download(storage_path(proppath($proposal->id) . "/document.pdf"));
    }

    public function downloadreport($uuid)
    {
        $user_id = getUserID();
        $report = ProposalReport::where('document', '=', $uuid)
            ->firstOrFail();
        if (Storage::exists(ppath($report->proposal_id) . "/report-" . $report->id . ".pdf"))
            return response()->download(storage_path(proppath($report->proposal_id) . "/report-" . $report->id . ".pdf"));
    }

    public function downloadletter($uuid)
    {
        $letter = Recommendation::where('document', '=', $uuid)->firstOrFail();
        if (Storage::exists(ppath($letter->proposal_id) . "/letter-" . $letter->id . ".pdf"))
            return response()->download(storage_path(proppath($letter->proposal_id) . "/letter-" . $letter->id . ".pdf"));
    }
}
