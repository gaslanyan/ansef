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
use App\Models\Address;
use App\Models\Country;
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
        if (count($pipersons[0]) > 1) {
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
        $activeproposals = User::find($user_id)->proposals()->where('state','=','in-progress')->get()->toArray();
        return view('applicant.proposal.active', compact('activeproposals'));
    }

    public function pastProposal()
    {
        $user_id = getUserID();
        $pid = [];
        $proposals = Proposal::all();
        $pastproposal = [];

        for ($i = 0; $i <= count($proposals) - 1; $i++) {
            $elements = json_decode($proposals[$i]->proposal_members, true);

            if (in_array($user_id, (array) $elements)) {
                $pid[] = $proposals[$i]->id;
                $pastproposal = Proposal::whereIn('state', ['submitted', 'unsuccessfull', 'complete', 'disqualified'])->whereIn('id', $pid)->get()->toArray();
            }
        }

        //$pastproposal = Proposal::whereIn('state', ['submitted', 'unsuccessfull', 'complete', 'disqualified'])->get();

        return view('applicant.proposal.past', compact('pastproposal'));
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
        $proposal->title = $request->title;
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

        if (!empty($request->choose_person)) {
            for ($i = 0; $i < count($request->choose_person); $i++) {
                $id_type = explode("_", $request->choose_person[$i]);
                if (count($id_type) != 2) {
                    dd($id_type);
                    return redirect()->action('Applicant\ProposalController@create');
                }

                $persontype = new PersonType();
                $persontype->person_id = $id_type[0];
                $persontype->proposal_id = $proposal_id;
                $persontype->subtype = $id_type[1];
                $persontype->save();
            }
        }

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

        return redirect()->action('Applicant\FileUploadController@index', $proposal_id);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proposal = Proposal::find($id);
        $institution = $proposal->institutions()->first();
        $competition = $proposal->competition;
        $persons = $proposal->persons()->get();
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
        $recom = Recommendations::where('proposal_id', $id)->get()->toArray();
        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->toArray();
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
        $person_members = json_decode($proposal->proposal_members);
        $person_acc = User::where("id", '=', $person_members->account_user_id)->get()->first();
        //        $person_account = Person::whereIn('id', [$person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
        //        if (!empty($person_members->person_collaborator_id)) {
        //            $person_collaborator = Person::whereIn('id', [$person_members->person_collaborator_id])->get()->toArray();
        //        }
        $person_account = \DB::table('persons')
            ->select('persons.first_name', 'persons.last_name', 'person_type.subtype', 'persons.id')
            ->join('person_type', 'persons.id', '=', 'person_type.person_id')
            ->where('person_type.proposal_id', '=', $id)
            ->get()->toArray();

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
            'cat_sec_sub',
            'persons',
            'person_account',
            'person_acc',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request '
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            //  'category.*' => 'required|not_in:0',
            //  'sub_category.*' => 'required|not_in:0',
            'title' => 'required|alpha|min:3',
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
        $institution = $proposal->institutions()->first();
        $competition = $proposal->competition;
        $persons = $proposal->persons()->get();
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

    public function updatepersons($id)
    {
        $proposaltag = getProposalTag($id);

        // FOR KRISTINE

        return view('applicant.proposal.personedit', compact('proposaltag', 'id'));
    }

    public function savepersons($id)
    {
        $proposaltag = getProposalTag($id);

        // FOR KRISTINE

        return view('applicant.proposal.personedit', compact('proposaltag', 'id'));
    }

    public function addperson($id)
    {
        $proposaltag = getProposalTag($id);

        // FOR KRISTINE

        return view('applicant.proposal.personedit', compact('proposaltag', 'id'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $budget_item = BudgetItem::where('proposal_id', '=', $id);
            if (!empty($budget_item)) {
                $budget_item->delete();
            }

            $prop_ins = PersonType::where('proposal_id', '=', $id);
            if (!empty($prop_ins)) {
                $prop_ins->delete();
            }


            $proposal = Proposal::find($id);
            $proposal_institutons = ProposalInstitution::where('proposal_id', '=', $id);
            if (!empty($proposal_institutons)) {
                $proposal_institutons->delete();
            }

            $proposal_report = ProposalReports::where('proposal_id', '=', $id);
            if (!empty($proposal_report)) {
                foreach ($proposal_report->get()->toArray() as $pr) {
                    if (is_file(storage_path('proposal/prop-' . $pr['proposal_id'] . '/' . $pr['document']))) {
                        unlink(storage_path('proposal/prop-' . $pr['proposal_id'] . '/' . $pr['document']));
                    } else {
                        echo "File does not exist";
                    }
                }
                $proposal_report->delete();
            }

            $referee_report = RefereeReport::where('proposal_id', '=', $id);
            if (!empty($referee_report)) {

                $referee_report->delete();
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
