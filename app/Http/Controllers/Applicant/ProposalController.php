<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\PersonType;
use App\Models\ProposalReports;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Institution;
use App\Models\ProposalInstitution;
use App\Models\Proposal;
use App\Models\Email;
use App\Models\Address;
use App\Models\Country;
use App\Models\DegreePerson;
use App\Models\InstitutionPerson;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\ScoreType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyRecommender;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('applicant.proposal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $competitions = [];
        $persons = [];

        $user_id = getUserID();
        $competitions = Competition::where('submission_end_date', '>=', date('Y-m-d'))
            ->where('submission_start_date', '<=', date('Y-m-d'))
            ->where('submission_end_date', '>=', date('Y-m-d'))
            ->where('state', 'enable')
            ->get();

        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();

        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->toArray();
        $pipersons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
        })->get()->toArray();
        if (!empty($pipersons) && count($pipersons[0]) > 1) {
            return view('applicant.proposal.create', compact('persons', 'competitions', 'countries', 'institutions'));
        }

        return view('applicant.proposal.notice', compact('persons', 'competitions', 'countries', 'institutions'));
    }

    /**
     * Show the active Proposals.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeProposal()
    {
        $user_id = getUserID();
        $proposals = User::find($user_id)->proposals()->where('state','=','in-progress')->get();
        $activeproposals = $proposals->filter(function($p, $key) {
            return date('Y-m-d') <= $p->competition->submission_end_date;
        });
        return view('applicant.proposal.active', compact('activeproposals'));
    }

    public function pastProposal()
    {
        $user_id = getUserID();
        $proposals = User::find($user_id)->proposals()->get();
        $pastproposals = $proposals->filter(function ($p, $key) {
            return  date('Y-m-d') > $p->competition->submission_end_date
                    && $p->state != 'awarded'
                    && $p->state != 'approved 1'
                    && $p->state != 'approved 2'
                    && $p->state != 'complete';
        });

        $awards = $proposals->filter(function ($p, $key) {
            return  date('Y-m-d') > $p->competition->submission_end_date
                && ($p->state == 'awarded'
                || $p->state == 'approved 1'
                || $p->state == 'approved 2'
                || $p->state == 'complete');
        });

        return view('applicant.proposal.past', compact('pastproposals', 'awards'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category.*' => 'required|not_in:0',
            'sub_category.*' => 'required|not_in:0',
            'comp_prop' => 'required|not_in:choosecompetition',
            'title' => 'required|min:3',
            'abstract' => 'required|min:55',
            /*  "prop_document" => "required|mimes:pdf|max:10000"*/

        ]);
        //        try {
        $prop_members = [];
        $proposal = new Proposal();
        $proposal->title = ucwords(mb_strtolower($request->title));
        $proposal->abstract = $request->abstract;
        $proposal->state = "in-progress";
        $user_id = getUserID();
        $proposal->user_id = $user_id;
        $proposal->competition_id = $request->comp_prop;
        $cat["parent"] = ($request->category);
        $cat['sub'] = ($request->sub_category);

        if (!empty($request->sec_category)) {
            $cat["sec_parent"] = ($request->sec_category);
        }
        if (!empty($request->sec_sub_category)) {
            $cat["sec_sub"] = ($request->sec_sub_category);
        }
        $json_merge = json_encode($cat);
        $proposal->categories = $json_merge;
        $proposal->save();
        $proposal_id = $proposal->id;

        $prop_institution = new ProposalInstitution();
        if (!empty($request->institutionname)) {
            $prop_institution->proposal_id = $proposal_id;
            $prop_institution->institutionname = $request->institutionname;
        } elseif (!empty($request->institution)) {
            $prop_institution->proposal_id = $proposal_id;
            $prop_institution->institution_id = (int) $request->institution;
        } else {
            $prop_institution->proposal_id = $proposal_id;
            $prop_institution->institutionname = "";
        }
        $prop_institution->save();

        return redirect()->action('Applicant\FileUploadController@docfile', $proposal_id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pid = $id;
        $proposal = Proposal::find($id);
        $institution = $proposal->institution();
        $competition = $proposal->competition;
        $persons = $proposal->persons()->get()->sortBy('last_name');
        $additional = json_decode($competition->additional);
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = property_exists($categories, 'sec_parent') ? Category::with('children')->where('id', $categories->sec_parent)->get()->first() : null;
        $cat_sec_sub = property_exists($categories, 'sec_sub') ? Category::with('children')->where('id', $categories->sec_sub)->get()->first() : null;
        $pi = $proposal->persons()->where('subtype', '=', 'PI')->first();
        $budget_items = $proposal->budgetitems()->get();
        $budget = $proposal->budget();

        return view('applicant.proposal.show', compact(
            'pid',
            'proposal',
            'institution',
            'competition',
            'persons',
            'additional',
            'categories',
            'proposal',
            'cat_parent',
            'cat_sub',
            'cat_sec_parent',
            'cat_sec_sub',
            'pi',
            'budget_items',
            'budget'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proposal = Proposal::find($id);
        $user_id = getUserID();
        $competitions = Competition::all();
        // $persons = Person::where('user_id', $user_id)->where(function ($query) {
        //     $query->where('type', 'participant');
        //     $query->orWhere('type', 'support');
        // })->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $additional = json_decode($competition_name->additional);
        $categories = json_decode($proposal->categories);
        if (property_exists($categories, 'parent')) $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        else $cat_parent = '';
        if (property_exists($categories, 'sub')) $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();
        else $cat_sub = '';
        if (property_exists($categories, 'sec_parent')) $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();
        else $cat_sec_parent = '';
        if (property_exists($categories, 'sec_sub')) $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        else $cat_sec_sub = '';

        $proposalinstitution = ProposalInstitution::where('proposal_id', '=', $id)->first();
        if (!empty($proposalinstitution)) {
            if (!empty($proposalinstitution->institutionname)) {
                $ins = ['id' => 0, 'name' => $proposalinstitution->institutionname];
            } else {
                $ins = Institution::find($proposalinstitution->institution_id);
                if (!empty($ins)) {
                    $ins = ['id' => $proposalinstitution->institution_id, 'name' => ""];
                } else {
                    $ins = ['id' => 0, 'name' => ""];
                }
            }
        } else {
            $proposalinstitution = new ProposalInstitution();
            $proposalinstitution->proposal_id = $id;
            $proposalinstitution->save();
            $ins = ['id' => 0, 'name' => ""];
        }

        $institutions = Institution::all()->pluck('content', 'id')->toArray();

        return view('applicant.proposal.edit', compact(
            'institutions',
            'ins',
            'competition_name',
            'competitions',
            'proposal',
            'cat_parent',
            'cat_sub',
            'cat_sec_parent',
            'cat_sec_sub'
        ));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'abstract' => 'required|min:55',
        ]);

        try {
            $proposal = Proposal::find($id);
            $proposal->title = $request->title;
            $proposal->abstract = $request->abstract;
            $proposal->save();

            $propins = ProposalInstitution::where('proposal_id', '=', $id)->first();
            if (empty($propins)) {
                $propins = new ProposalInstitution();
                $propins->proposal_id = $id;
            }
            if (!empty($request->institutionname) && $request->institutionname != "") {
                $propins->institutionname = $request->institutionname;
                $propins->institution_id = 0;
            } elseif (!empty($request->institution[0])) {
                $propins->institution_id = (int) $request->institution[0];
                $propins->institutionname = "";
            } else {
                $propins->institutionname = "";
            }
            $propins->save();

            return redirect()->action('Applicant\ProposalController@activeProposal');
        } catch (\Exception $exception) {
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    public function generatePDF($id)
    {
        $proposal = Proposal::find($id);
        $institution = $proposal->institution();
        $competition = $proposal->competition;
        $persons = $proposal->persons()->get()->sortBy('last_name');
        $additional = json_decode($competition->additional);
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = property_exists($categories, 'sec_parent') ? Category::with('children')->where('id', $categories->sec_parent)->get()->first() : null;
        $cat_sec_sub = property_exists($categories, 'sec_sub') ? Category::with('children')->where('id', $categories->sec_sub)->get()->first() : null;
        $pi = $proposal->persons()->where('subtype', '=', 'PI')->first();
        $budget_items = $proposal->budgetitems()->get();
        $budget = $proposal->budget();

        $pdf = PDF::loadView('applicant.proposal.pdf', compact(
            'id',
            'proposal',
            'institution',
            'competition',
            'persons',
            'additional',
            'categories',
            'proposal',
            'cat_parent',
            'cat_sub',
            'cat_sec_parent',
            'cat_sec_sub',
            'pi',
            'budget_items',
            'budget'
        ));

        return $pdf->download('document.pdf');
    }

    public function updatepersons(Request $request,$id)
    {
        $enum = getEnumValues('person_type', 'subtype');
        $participant = [];
        $support = [];
        foreach($enum as $item) {
            if($item == 'PI' || $item =='collaborator') array_push($participant, $item);
            else array_push($support, $item);
        }
        $proposaltag = getProposalTag($id);
        $user_id = getUserID();
        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->keyBy('id')->toArray();

        $added_persons = PersonType::where('proposal_id','=', $id)
            ->get()->toArray();

        return view('applicant.proposal.personedit', compact('proposaltag', 'id','persons','added_persons', 'participant', 'support'));
    }

    public function savepersons(Request $request, $id)
    {
        $enum = getEnumValues('person_type', 'subtype');
        $participant = [];
        $support = [];
        foreach ($enum as $item) {
            if ($item == 'PI' || $item == 'collaborator') array_push($participant, $item);
            else array_push($support, $item);
        }
        $proposaltag = getProposalTag($id);
        $user_id = getUserID();

        for ($i = 0; $i <= count($request->person_list) - 1; $i++) {
            $pt = PersonType::find($request->person_list_hidden[$i]);
            if(!empty($pt)) {
                $person = Person::find($pt->person_id);
                if ($person->type == 'participant') {
                    $pt->subtype = $request->subtypeparticipant[$i];
                    $pt->save();
                } else if ($person->type == 'support') {
                    $pt->subtype = $request->subtypesupport[$i];
                    $pt->save();
                } else { }
            }
        }

        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->keyBy('id')->toArray();

        $added_persons = PersonType::where('proposal_id', '=', $id)
            ->get()->toArray();
        return view('applicant.proposal.personedit', compact('proposaltag', 'id', 'persons', 'added_persons', 'participant', 'support'));
    }

    public function removeperson($id)
    {
        try {
            $added_person = PersonType::find($id);
            $added_person->delete();
            return view('applicant.proposal.personedit', compact('proposaltag', 'id','persons','added_persons', 'participant', 'support'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }

    public function addperson(Request $request, $id)
    {
        if($request->theperson == 0) {
            return Redirect::back()->with('wrong', 'Please choose a person to add.')->withInput();
        }
        else {
            $ptc = PersonType::where('proposal_id','=',$id)
                    ->where('person_id','=', $request->theperson)->count();
            if($ptc != 0) {
                return Redirect::back()->with('wrong', 'This person has already been added to the project.')->withInput();
            }
            $person = Person::find($request->theperson);
            if ($person->type == 'participant') {
                if ($request->subtypeparticipant == "0") {
                    return Redirect::back()->with('wrong', 'Please choose the role of the person in the project.')->withInput();
                }
            }
            else if ($person->type == 'support') {
                if ($request->subtypesupport == "0") {
                    return Redirect::back()->with('wrong', 'Please choose the role of the person in the project.')->withInput();
                }
            }
            else {
            }
        }

        try {
            $persontype = new PersonType();
            $persontype->person_id = $request->theperson;
            $persontype->proposal_id = $id;
            $person = Person::find($request->theperson);
            if($person->type == 'participant') {
                $persontype->subtype = $request->subtypeparticipant;
                $persontype->save();
                return Redirect::back()->with('success', getMessage("success"));
            }
            else if($person->type == 'support') {
                $persontype->subtype = $request->subtypesupport;
                $persontype->save();
                return Redirect::back()->with('success', getMessage("success"));
            }
            else {
                return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    public function check($id)
    {
        $p = Proposal::find($id);
        $proposaltag = getProposalTag($id);
        $competition = $p->competition;

        $r = checkproposal($id);
        $messages =  $r['messages'];
        $warnings = $r['warnings'];
        $submittedrecs = $r['submittedrecs'];
        $missingrecs = $r['missingrecs'];
        $pi = $r['pi'];

        return view('applicant.proposal.audit', compact('proposaltag', 'id', 'messages', 'warnings', 'competition', 'submittedrecs', 'missingrecs', 'pi'));
    }

    public function notifyrecommenders($id) {
        $missingrecs = [];
        $recommenders = PersonType::where('proposal_id', '=', $id)
            ->where('subtype', '=', 'supportletter')
            ->join('persons', 'persons.id', '=', 'person_id')
            ->get();

        foreach ($recommenders as $recommender) {
            $email = Email::where('person_id', '=', $recommender->person_id)->first();

            if (!Recommendations::where('proposal_id', '=', $id)->where('document','!=',null)->where('person_id', '=', $recommender->person_id)->exists() && !empty($email)) {
                array_push($missingrecs, ["id" => $recommender->person_id, "email" => $email->email, "name" => $recommender->first_name . " " . $recommender->last_name]);
            }
        }

        $p = Proposal::find($id);
        $pi = PersonType::where('proposal_id', '=', $id)
            ->join('persons', 'persons.id', '=', 'person_id')
            ->where('subtype', '=', 'PI')
            ->first();

        foreach ($missingrecs as $missingrec) {
            $confirmation = randomPassword();
            $r = Recommendations::firstOrCreate([
                'proposal_id' => $p->id,
                'person_id' => $missingrec['id']] , [
                'confirmation' => $confirmation
            ]);
            $r->confirmation = $confirmation;
            $r->save();
            Notification::route('mail', $missingrec['email'])
                ->notify(new NotifyRecommender($missingrec['email'], $missingrec['name'], $pi->first_name . " " . $pi->last_name, $r->id, $confirmation));
        }

        return redirect()->action('Applicant\ProposalController@activeProposal')->with('success', count($missingrecs) . ' email' . (count($missingrecs)>1 ? 's' : '') . ' sent requesting recommendation letter' . (count($missingrecs)>1 ? 's' : '') . '.');
    }

    public function instructions($id) {
        $competition = Proposal::find($id)->competition;
        return view('applicant.proposal.instructions', compact('competition'));
    }

    public function destroy($id)
    {
        try {
            $budget_item = BudgetItem::where('proposal_id', '=', $id);
            if (!empty($budget_item)) {
                $budget_item->delete();
            }

            $persons = PersonType::where('proposal_id', '=', $id);
            if (!empty($persons)) {
                $persons->delete();
            }

            $recs = Recommendations::where('proposal_id', '=', $id);
            if (!empty($recs)) {
                $recs->delete();
            }

            $proposal = Proposal::find($id);

            $proposal_institutions = ProposalInstitution::where('proposal_id', '=', $id);
            if (!empty($proposal_institutions)) {
                $proposal_institutions->delete();
            }

            $proposal_reports = ProposalReports::where('proposal_id', '=', $id);
            if (!empty($proposal_reports)) {
                foreach ($proposal_reports->get()->toArray() as $pr) {
                    if (is_file(storage_path('proposal/prop-' . $pr['proposal_id'] . '/' . $pr['document']))) {
                        unlink(storage_path('proposal/prop-' . $pr['proposal_id'] . '/' . $pr['document']));
                    } else {
                        echo "File does not exist";
                    }
                }
                $proposal_reports->delete();
            }

            $referee_reports = RefereeReport::where('proposal_id', '=', $id);
            if (!empty($referee_reports)) {
                $referee_reports->delete();
            }

            //
            if (is_file(storage_path('proposal/prop-' . $proposal['id'] . '/' . $proposal['document']))) {
                unlink(storage_path('proposal/prop-' . $proposal['id'] . '/' . $proposal['document']));
            } else {
                echo "File does not exist";
            }
            if (is_dir_empty(storage_path('proposal/prop-' . $proposal['id']))) {
                File::deleteDirectory(storage_path('proposal/prop-' . $proposal['id']));
            } else {
                echo "the folder is NOT empty";
            }

            $proposal->delete();

            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }
}
