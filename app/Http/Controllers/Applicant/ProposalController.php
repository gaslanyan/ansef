<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\ProposalPerson;
use App\Models\ProposalReport;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Degree;
use App\Models\Institution;
use App\Models\ProposalInstitution;
use App\Models\Proposal;
use App\Models\Email;
use App\Models\Country;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifyRecommender;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    public function index()
    {
        $user_id = getUserID();

        return view('applicant.proposal.index');
    }

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

        $countries = Country::all()->sortBy('country_name')->pluck('country_name', 'cc_fips')->toArray();
        $institutions = Institution::all()->sortBy('content')->pluck('content', 'id')->toArray();

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

    public function activeProposal()
    {
        $user_id = getUserID();
        $proposals = User::find($user_id)->proposals()->where('state', '=', 'in-progress')->get();
        $activeproposals = $proposals->filter(function ($p, $key) {
            return date('Y-m-d') <= $p->competition->submission_end_date;
        });
        return view('applicant.proposal.active', compact('activeproposals'));
    }

    public function pastProposal()
    {
        $user_id = getUserID();
        $proposals = User::find($user_id)->proposals()->get()->sortBy('updated_at');
        $pastproposals = $proposals->filter(function ($p, $key) {
            return  date('Y-m-d') > $p->competition->submission_end_date
                && $p->state != 'awarded'
                && $p->state != 'approved 1'
                && $p->state != 'approved 2';
        });

        $awards = $proposals->filter(function ($p, $key) {
            return  date('Y-m-d') > $p->competition->submission_end_date
                && ($p->state == 'awarded'
                    || $p->state == 'approved 1'
                    || $p->state == 'approved 2');
        });

        $firstreports = [];
        $secondreports = [];
        foreach ($awards as $award) {
            $first = $award->competition->first_report;
            $second = $award->competition->second_report;
            $firstreport = ProposalReport::where('proposal_id', '=', $award->id)
                ->where('due_date', '=', $first)
                ->first();
            $secondreport = ProposalReport::where('proposal_id', '=', $award->id)
                ->where('due_date', '=', $second)
                ->first();
            if (!empty($firstreport) && !empty($firstreport->document))
                $firstreports[$award->id] = (true);
            else $firstreports[$award->id] = (false);
            if (!empty($secondreport) && !empty($secondreport->document))
                $secondreports[$award->id] = (true);
            else $secondreports[$award->id] = (false);
        }

        return view('applicant.proposal.past', compact('pastproposals', 'awards', 'firstreports', 'secondreports'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $v = Validator::make($request->all(), [
            'comp_prop' => 'required|not_in:0',
            'category' => 'required|not_in:0',
            'sub_category' => 'required|not_in:0',
            'title' => 'required|min:3',
            'abstract' => 'required|min:55',
            /*  "prop_document" => "required|mimes:pdf|max:10000"*/
        ]);
        if (!$v->fails()) {
            $proposal = new Proposal();
            $proposal->title = ucwords(mb_strtolower($request->title));
            $proposal->abstract = $request->abstract;
            $proposal->state = "in-progress";
            $proposal->user_id = $user_id;
            $proposal->competition_id = $request->comp_prop;
            $cat["parent"] = [$request->category];
            $cat['sub'] = [$request->sub_category];

            if (!empty($request->sec_category)) {
                $cat["sec_parent"] = [$request->sec_category];
            }
            if (!empty($request->sec_sub_category)) {
                $cat["sec_sub"] = [$request->sec_sub_category];
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
        else {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
    }


    public function show($id)
    {
        $user_id = getUserID();
        $pid = $id;
        $proposal = Proposal::where('id', '=', $id)->where('user_id', '=', $user_id)->first();
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
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

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
            'budget',
            'reports'
        ));
    }

    public function edit($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::where('id', '=', $id)->where('user_id', '=', $user_id)->first();
        $competitions = Competition::all();
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

        $proposalinstitution = ProposalInstitution::where('proposal_id', '=', $id)
            ->first();
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

        $institutions = Institution::all()->sortBy('content')->pluck('content', 'id')->toArray();

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
        $user_id = getUserID();
        try {
            $v = Validator::make($request->all(), [
                'title' => 'required|min:3',
                'abstract' => 'required|min:55',
            ]);
            if (!$v->fails()) {

            $proposal = Proposal::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
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
            }
            else {
                return redirect()->back()->withErrors($v->errors())->withInput();
            }
        } catch (\Exception $exception) {
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function generatePDF($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $pid = $proposal->id;
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
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

        $data = [
            'id' => $id,
            'pid' => $pid,
            'proposal' => $proposal,
            'institution' => $institution,
            'competition' => $competition,
            'persons' => $persons,
            'additional' => $additional,
            'categories' => $categories,
            'proposal' => $proposal,
            'cat_parent' => $cat_parent,
            'cat_sub' => $cat_sub,
            'cat_sec_parent' => $cat_sec_parent,
            'cat_sec_sub' => $cat_sec_sub,
            'pi' => $pi,
            'budget_items' => $budget_items,
            'budget' => $budget,
            'reports' => $reports
        ];

        $pdf = PDF::loadView('applicant.proposal.pdf', $data);
        $pdf->save(storage_path(proppath($pid) . '/combined.pdf'));

        $pdfMerge = PDFMerger::init();
        if (Storage::exists(ppath($pid) . '/combined.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/combined.pdf'), 'all');
        if (Storage::exists(ppath($pid) . '/document.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/document.pdf'), 'all');
        $pdfMerge->merge();

        $pdfMerge->save(storage_path(proppath($pid) . '/download.pdf'), 'download');

        Storage::delete('proposals/prop-' . $pid . '/combined.pdf');
    }


    public function updatepersons(Request $request, $id)
    {
        $user_id = getUserID();
        $enum = getEnumValues('proposal_persons', 'subtype');
        $participant = [];
        $support = [];
        foreach ($enum as $item) {
            if ($item == 'PI' || $item == 'collaborator') array_push($participant, $item);
            else array_push($support, $item);
        }
        $proposaltag = getProposalTag($id);
        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->keyBy('id')->toArray();

        $added_persons = ProposalPerson::where('proposal_id', '=', $id)
            ->get()->toArray();

        return view('applicant.proposal.personedit', compact('proposaltag', 'id', 'persons', 'added_persons', 'participant', 'support'));
    }

    public function savepersons(Request $request, $id)
    {
        $user_id = getUserID();
        $enum = getEnumValues('proposal_persons', 'subtype');
        $participant = [];
        $support = [];
        foreach ($enum as $item) {
            if ($item == 'PI' || $item == 'collaborator') array_push($participant, $item);
            else array_push($support, $item);
        }
        $proposaltag = getProposalTag($id);

        for ($i = 0; $i <= count($request->person_list) - 1; $i++) {
            $pt = ProposalPerson::find($request->person_list_hidden[$i]);
            if (!empty($pt)) {
                $person = Person::where('id', '=', $pt->person_id)
                    ->where('user_id', '=', $user_id)
                    ->first();
                if ($person->type == 'participant') {
                    $pt->subtype = $request->subtypeparticipant[$i];
                    $pt->save();
                } else if ($person->type == 'support') {
                    $pt->subtype = $request->subtypesupport[$i];
                    $pt->save();
                } else {
                }
            }
        }

        $persons = Person::where('user_id', $user_id)->where(function ($query) {
            $query->where('type', 'participant');
            $query->orWhere('type', 'support');
        })->get()->keyBy('id')->toArray();

        $added_persons = ProposalPerson::where('proposal_id', '=', $id)
            ->get()->toArray();
        return view('applicant.proposal.personedit', compact('proposaltag', 'id', 'persons', 'added_persons', 'participant', 'support'));
    }

    public function removeperson($id)
    {
        $user_id = getUserID();
        try {
            $added_person = ProposalPerson::find($id);
            $added_person->delete();
            return Redirect::back()->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }

    public function addperson(Request $request, $id)
    {
        $user_id = getUserID();
        if ($request->theperson == 0) {
            return Redirect::back()->with('wrong', 'Please choose a person to add.')->withInput();
        } else {
            $ptc = ProposalPerson::where('proposal_id', '=', $id)
                ->where('person_id', '=', $request->theperson)->count();
            if ($ptc != 0) {
                return Redirect::back()->with('wrong', 'This person has already been added to the project.')->withInput();
            }
            $person = Person::find($request->theperson);
            if ($person->type == 'participant') {
                if ($request->subtypeparticipant == "0") {
                    return Redirect::back()->with('wrong', 'Please choose the role of the person in the project.')->withInput();
                }
            } else if ($person->type == 'support') {
                if ($request->subtypesupport == "0") {
                    return Redirect::back()->with('wrong', 'Please choose the role of the person in the project.')->withInput();
                }
            } else {
            }
        }

        try {
            $proposalperson = new ProposalPerson();
            $proposalperson->person_id = $request->theperson;
            $proposalperson->proposal_id = $id;
            $proposalperson->competition_id = Proposal::find($id)->competition->id;
            $person = Person::find($request->theperson);
            if ($person->type == 'participant') {
                $proposalperson->subtype = $request->subtypeparticipant;
                $proposalperson->save();
                return Redirect::back()->with('success', messageFromTemplate("success"));
            } else if ($person->type == 'support') {
                $proposalperson->subtype = $request->subtypesupport;
                $proposalperson->save();
                return Redirect::back()->with('success', messageFromTemplate("success"));
            } else {
                return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function check($id)
    {
        $user_id = getUserID();
        $p = Proposal::where('id', '=', $id)->where('user_id', '=', $user_id)->first();
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

    public function notifyrecommenders($id)
    {
        $user_id = getUserID();
        $missingrecs = [];
        $recommenders = ProposalPerson::where('proposal_id', '=', $id)
            ->where('subtype', '=', 'supportletter')
            ->join('persons', 'persons.id', '=', 'person_id')
            ->get();

        foreach ($recommenders as $recommender) {
            $email = Email::where('person_id', '=', $recommender->person_id)
                ->where('user_id', '=', $user_id)
                ->first();

            if (!Recommendation::where('proposal_id', '=', $id)->where('document', '!=', null)->where('person_id', '=', $recommender->person_id)->exists() && !empty($email)) {
                array_push($missingrecs, ["id" => $recommender->person_id, "email" => $email->email, "name" => $recommender->first_name . " " . $recommender->last_name]);
            }
        }

        $p = Proposal::where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->first();
        $pi = ProposalPerson::where('proposal_id', '=', $id)
            ->join('persons', 'persons.id', '=', 'person_id')
            ->where('subtype', '=', 'PI')
            ->first();

        foreach ($missingrecs as $missingrec) {
            $confirmation = randomPassword();
            $r = Recommendation::updateOrCreate([
                'proposal_id' => $p->id,
                'person_id' => $missingrec['id']
            ], [
                'confirmation' => $confirmation
            ]);
            $r->confirmation = $confirmation;
            $r->save();
            Notification::route('mail', $missingrec['email'])
                ->notify(new NotifyRecommender($missingrec['email'], $missingrec['name'], $pi->first_name . " " . $pi->last_name, $r->id, $confirmation));
        }

        return redirect()->action('Applicant\ProposalController@activeProposal')->with('success', count($missingrecs) . ' email' . (count($missingrecs) > 1 ? 's' : '') . ' sent requesting recommendation letter' . (count($missingrecs) > 1 ? 's' : '') . '.');
    }

    public function instructions($id)
    {
        $competition = Proposal::find($id)->competition;
        return view('applicant.proposal.instructions', compact('competition'));
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $budget_item = BudgetItem::where('proposal_id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->get();
            if (!empty($budget_item)) {
                $budget_item->delete();
            }

            $persons = ProposalPerson::where('proposal_id', '=', $id);
            if (!empty($persons)) {
                $persons->delete();
            }

            $recs = Recommendation::where('proposal_id', '=', $id);
            if (!empty($recs)) {
                $recs->delete();
            }

            $proposal = Proposal::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();

            $proposal_institutions = ProposalInstitution::where('proposal_id', '=', $id);
            if (!empty($proposal_institutions)) {
                $proposal_institutions->delete();
            }

            $proposal_reports = ProposalReport::where('proposal_id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->get();
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
            if (is_file(storage_path(proppath($proposal['id']) . '/' . $proposal['document']))) {
                unlink(storage_path(proppath($proposal['id']) . '/' . $proposal['document']));
            } else {
                echo "File does not exist";
            }
            if (is_dir_empty(storage_path(proppath($proposal['id'])))) {
                File::deleteDirectory(storage_path(proppath($proposal['id'])));
            } else {
                echo "the folder is NOT empty";
            }

            $proposal->delete();

            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }

    public function subcategories(Request $request)
    {
        $resp = [];
        $sub = Category::where('parent_id', '=', $request['id'])->pluck('title', 'id')->toArray();
        echo $resp[0] = json_encode($sub);
        exit();
    }

    public function getCompetitionRestrictions(Request $request)
    {
        // \Debugbar::error('getCompetitionRestrictions');

        if (isset($request->_token)) {
            $comp_id = $request->id;

            $content = [];
            $com = Competition::where('id', '=', $comp_id)->first();
            $categories = json_decode($com->categories);
            foreach ($categories as $index => $category) {
                if ($category != 0) {
                    $cat = Category::with('children')->where('id', $category)->get()->first();

                    if (empty($cat->parent_id))
                        $content['cats'][$cat->id]['parent'] = $cat->title;
                    else {
                        if (in_array($cat->parent_id, $categories))
                            $content['cats'][$cat->parent_id]['sub'] = $cat->title;
                    }
                }
            }

            // $content['bc'] = BudgetCategory::where('competition_id', '=', $comp_id)->get()->toArray();
            // $content['st'] = ScoreType::where('competition_id', '=', $comp_id)->get()->toArray();
            $content['recommendation'] = $com->recommendations;
            $content['allowforeign'] = $com->allow_foreign;
            $content['min_level_deg_id'] = $com->min_level_deg_id != 1 ? Degree::find($com->min_level_deg_id)->text : "";
            $content['max_level_deg_id'] = $com->max_level_deg_id != 1 ? Degree::find($com->max_level_deg_id)->text : "";
            $content['min_age'] = $com->min_age;
            $content['max_age'] = $com->max_age;
            $content['min_budget'] = $com->min_budget;
            $content['max_budget'] = $com->max_budget;
            // $content['additional'] = json_decode($com->additional);

            echo json_encode($content);
            exit;
        }
    }
}
