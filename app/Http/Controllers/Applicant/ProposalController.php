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

        $user_id = chooseUser();
        $signed_person_id = signUser()->pid;
        $competitions = Competition::where('submission_end_date','>',date('Y-m-d'))->get();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();

        $persons = Person::where('user_id', $user_id)->where(function($query) {
                $query->where('type', 'contributor');
                $query->orWhere('type', 'external_support');
            })->get()->toArray();
        if (count($persons) > 1) {
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
        $accountid = signUser();
        $acc_id = $accountid->user_id;
        //dd($acc_id);
        $pid = [];
        $proposals = Proposal::all();
        $activeproposal = [];

        for ($i = 0; $i <= count($proposals) - 1; $i++) {
            $elements = json_decode($proposals[$i]->proposal_members, true);
           // dd($elements);
            if (in_array($acc_id, (array)$elements)) {
                $pid[] = $proposals[$i]->id;
               // dd($pid);
                $activeproposal = Proposal::whereIn('state', ['in-progress', 'awarded', 'approved 1', 'in-review'])->
                whereIn('id', $pid)->get()->toArray();

            }
        }
      /*  $activeproposal = Proposal::whereIn('state', ['in-progress', 'awarded', 'approved 1', 'in-review'])->where('id', '=', $acc_id)->toSql();
            dd($activeproposal);*/


        return view('applicant.proposal.active', compact('activeproposal'));
    }

    public function pastProposal()
    {
        $accountid = signUser();
        $acc_id = $accountid->user_id;
        $pid = [];
        $proposals = Proposal::all();
        $pastproposal = [];

        for ($i = 0; $i <= count($proposals) - 1; $i++) {
            $elements = json_decode($proposals[$i]->proposal_members, true);

            if (in_array($acc_id, (array)$elements)) {
                $pid[] = $proposals[$i]->id;
                $pastproposal = Proposal::whereIn('state', ['submitted', 'unsuccessfull', 'complete', 'disqualified'])->
                whereIn('id', $pid)->get()->toArray();

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
            'abstract' => 'required|min:5',
            /*  "prop_document" => "required|mimes:pdf|max:10000"*/

        ]);
//        try {
        $signed_person_id = signUser()->pid;
        $prop_members = [];
        $proposal = new Proposal();
        $proposal->title = $request->title;
        $proposal->abstract = $request->abstract;
        $proposal->state = "in-progress";
        $user_id = chooseUser();
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
        $prop_members['account_id'] = $signed_person_id;
        $prop_members['account_user_id'] = $user_id;
        $proposal->proposal_members = json_encode($prop_members);
        $proposal->save();
        $proposal_id = $proposal->id;


        if (!empty($request->choose_person)) {
            for ($i = 0; $i < count($request->choose_person); $i++) {
                $id_type = explode("_", $request->choose_person[$i]);
                $persontype = new PersonType();
                $persontype->person_id = $id_type[0];
                $persontype->proposal_id = $proposal_id;
                $persontype->subtype = $id_type[1];
                $persontype->save();
                // dd($request->choose_person_id[$i]);
                /*  if ($request->choose_person_t[$i] == "PI") {
                      $prop_members['person_pi_id'] = $request->choose_person_id[$i];

                  }
                  if ($request->choose_person_t[$i] == "director") {
                      $prop_members['person_director_id'] = $request->choose_person_id[$i];

                  }

                  if ($request->choose_person_t[$i] == "collaborator") {
                      $prop_members['person_collaborator_id'] = $request->choose_person_id[$i];
                  */
            }
        }

        $totalbudgetamount = 0;
        if (!empty($request->budget_item_categories)) {
            foreach ($request->budget_item_categories as $key => $val) {
                $budget_item = new BudgetItem();
                $budget_item->budget_cat_id = $request->budget_item_categories_hidden[$key];
                $budget_item->description = $request->budget_categories_description[$key];
                $budget_item->amount = $request->amount[$key];
                $totalbudgetamount += $request->amount[$key];
                $budget_item->proposal_id = $proposal_id;
                $budget_item->save();
            }
        }

        if (!empty($request->institution)) {
            foreach ($request->institution as $key => $val) {
                $institution = new ProposalInstitution();
                $institution->proposal_id = $proposal_id;
                $institution->institution_id = (int)$request->institution[$key];;
                $institution->save();
            }
        }
        if (!empty($request->institutionname)) {
            for($i = 0; $i<count($request->institutionname); $i++) {
                $institutions = new Institution();
                $institutions->content = $request->institutionname[$i];
                $institutions->address_id = 130;
                $institutions->save();
                $ins_id = $institutions->id;

                $prop_institution = new ProposalInstitution();
                $prop_institution->proposal_id = $proposal_id;
                $prop_institution->institution_id = $ins_id;
                $prop_institution->save();
            }
        }
        return redirect()->action('Applicant\FileUploadController@index', $proposal_id);
       /* if (!empty($prop_members['person_pi_id'])) {
            $personid_recom = [];
            $bool_support = false;

            for ($i = 0; $i < count($request->choose_person); $i++) {
                if ($request->choose_person_t[$i] == "support") {
                    if (!empty(getEmailByPersonID($request->choose_person_id[$i]))) {
                        array_push($personid_recom, $request->choose_person_id[$i]);
                        $bool_support = true;
                    } else {
                        $bool_support = false;
                        break;
                        //TODO   Wrong message chi berum
                        //return Redirect::back()->with('wrong', getMessage("wrong"));
                    }
                    // $prop_members['person_support_id'] = $request->choose_person_id[$i];
                }

            }


              if ($bool_support == true) {
                  $proposal->save();
                  $proposal_id = $proposal->id;

                  //dd($personid_recom);
                  if ($request->recommendation == 1) {
                      for ($i = 0; $i < count($personid_recom); $i++) {
                          $recommendations = new Recommendations();
                          $recommendations->person_id = $personid_recom[$i];
                          $recommendations->proposal_id = $proposal_id;
                          $recommendations->save();
                          $objSend = new \stdClass();
                          $objSend->message = $personid_recom[$i] . " Click here to write recommendation http://ansef.gitc.am/support/" . $proposal_id . "/" . $personid_recom[$i];
                          $objSend->sender = 'Ansef';
                          $objSend->receiver = 'Recomedations';

                          // TODO add emails  -  ov vo piti stana namaky
                          Mail::to('krist68@mail.ru')->send(new \App\Mail\SupportPerson($objSend));

                      }
                  }


                  return redirect()->action('Applicant\FileUploadController@index', $proposal_id);
              } else {
                  return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
              }
          } //TODO write wrong message text
          else {
              return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
          }


  //        } catch (\Exception $exception) {
  //            return Redirect::back()->with('wrong', getMessage("wrong"));
  //
  //        }*/

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
        $user_id = chooseUser();
        $competitions = Competition::all();
        $recom = Recommendations::where('proposal_id', $id)->get()->toArray();
        $persons = Person::where('user_id', $user_id)->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $additional = json_decode($competition_name->additional);
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();;
        $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        $person_members = json_decode($proposal->proposal_members);

        //TODO stugel person memebrs-i mej yuraqanchyur  persony ka te chka
        $person_acc = User::where("id", '=', $person_members->account_user_id)->get()->first();
       //$person_account = Person::whereIn('id', [$person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
       $person_account = \DB::table('persons')
           ->select('persons.first_name', 'persons.last_name', 'person_type.subtype')
           ->join('person_type', 'persons.id', '=', 'person_type.person_id')
           ->where('person_type.proposal_id', '=', $id)
           ->get()->toArray();
        //dd($person_account);
        /*if (!empty($person_members->person_collaborator_id)) {
            $person_collaborator = Person::whereIn('id', [$person_members->person_collaborator_id])->get()->toArray();
        }*/

        //ToDO $person_members->account_id;

        $budget_item = BudgetItem::where('proposal_id', '=', $proposal->id)->get()->toArray();
        $budget_categories = BudgetCategory::where('competition_id', '=', $proposal->competition_id)->get()->toArray();
        $refereereport = RefereeReport::where('proposal_id', '=', $id)->get()->toArray();
        $propsalinstitution = ProposalInstitution::select('institution_id')->where('proposal_id', '=', $id)->get()->first();
        if (!empty($propsalinstitution->institution_id)) {
            $ins = Institution::find($propsalinstitution->institution_id);
        }
        $scoreTypes = ScoreType::with('score')->where('competition_id', $proposal->competition_id)->get()->toArray();

        return view('applicant.proposal.show', compact('scoreTypes', 'person_collaborator', 'ins', 'budget_categories', 'budget_item', 'competition_name', 'competitions', 'proposal', 'cat_parent', 'cat_sub',
            'cat_sec_parent', 'cat_sec_sub', 'persons', 'person_account', 'proposalreports', 'additional', 'refereereport', 'recom'));

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
        $user_id = chooseUser();
        $competitions = Competition::all();
        $recom = Recommendations::where('proposal_id', $id)->get()->toArray();
        $persons = Person::where('user_id', $user_id)->where(function($query) {
                $query->where('type', 'contributor');
                $query->orWhere('type', 'external_support');
            })->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $additional = json_decode($competition_name->additional);
        $categories = json_decode($proposal->categories);
        if(property_exists($categories,'parent')) $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        else $cat_parent = '';
        if(property_exists($categories,'sub')) $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();
        else $cat_sub = '';
        if(property_exists($categories,'sec_parent')) $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();
        else $cat_sec_parent = '';
        if(property_exists($categories,'sec_sub')) $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        else $cat_sec_sub = '';
        $person_members = json_decode($proposal->proposal_members);
        $person_acc = User::where("id", '=', $person_members->account_user_id)->get()->first();
//        $person_account = Person::whereIn('id', [$person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
//        if (!empty($person_members->person_collaborator_id)) {
//            $person_collaborator = Person::whereIn('id', [$person_members->person_collaborator_id])->get()->toArray();
//        }
        $person_account = \DB::table('persons')
            ->select('persons.first_name', 'persons.last_name', 'person_type.subtype','persons.id')
            ->join('person_type', 'persons.id', '=', 'person_type.person_id')
            ->where('person_type.proposal_id', '=', $id)
            ->get()->toArray();


        // dd($person_account[1]->first_name);

        $budget_item = BudgetItem::where('proposal_id', '=', $proposal->id)->get()->toArray();
        $budget_categories = BudgetCategory::where('competition_id', '=', $proposal->competition_id)->get()->toArray();
        $totalbudgetamout = 0;
        if (!empty($budget_item)) {
            foreach ($budget_item as $item) {
                $totalbudgetamout += $item['amount'];
            }
        }

        $budget_message = "";
        if (($totalbudgetamout > $competition_name->max_budget) && ($totalbudgetamout < $competition_name->min_budget)) {
            $budget_message = "Amount of budget items must be between " . $competition_name->min_budget . " to " . $competition_name->max_budget;
        }

        $proposalreports = ProposalReports::where('proposal_id', '=', $id)->get()->toArray();
        $refereereport = RefereeReport::where('proposal_id', '=', $id)->get()->toArray();
        $scoreTypes = ScoreType::with('score')->where('competition_id', $proposal->competition_id)->get()->toArray();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();
        $propsalinstitution = ProposalInstitution::select('institution_id')->where('proposal_id', '=', $id)->get()->first();

        if (!empty($propsalinstitution->institution_id)) {
            $ins = Institution::find($propsalinstitution->institution_id);
        }

        return view('applicant.proposal.edit', compact('person_collaborator', 'institutions', 'ins', 'budget_categories', 'budget_item', 'competition_name', 'competitions', 'proposal', 'cat_parent', 'cat_sub',
            'cat_sec_parent', 'cat_sec_sub', 'persons', 'budget_message', 'person_account', 'proposalreports', 'competition_name->max_budget', 'person_acc', 'additional', 'refereereport', 'recom', 'scoreTypes'));
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
        $proposal = Proposal::find($id);
        $proposal->competition_id = $request->comp_prop;
        $validatedData = $request->validate([
            /* 'category.*' => 'required|not_in:0',
             'sub_category.*' => 'required|not_in:0',
             'comp_prop' => 'required|not_in:choosecompetition',
             'title' => 'required|alpha|min:3',
             'abstract' => 'required|min:55',
             "first_report" => "required|mimes:pdf|max:10000"*/

        ]);
        
        // VVS
        // try {
        //     $proposal->title = $request->title;
        //     $proposal->abstract = $request->abstract;
        //     // TODO
        //     // $proposal->document;
        //     $totalbudgetamount = 0;
        //     if (!empty($request->budget_item_categories)) {
        //         $budget_item = BudgetItem::find($id);
        //         $budget_item->delete();
        //         foreach ($request->budget_item_categories as $key => $val) {
        //             $budget_item = new BudgetItem();
        //             $budget_item->budget_cat_id = $request->budget_item_categories_hidden[$key];
        //             $budget_item->description = $request->budget_categories_description[$key];
        //             $budget_item->amount = $request->amount[$key];
        //             $totalbudgetamount += $request->amount[$key];
        //             $budget_item->proposal_id = $id;
        //             $budget_item->save();
        //         }
        //     }

        //     if (!empty($request->institution)) {
        //         $institution = ProposalInstitution::find($id);
        //         $institution->delete();
        //         foreach ($request->institution as $key => $val) {
        //             $institution = new ProposalInstitution();
        //             $institution->proposal_id = $id;
        //             $institution->institution_id = (int)$request->institution[$key];;
        //             $institution->save();
        //         }
        //     }
        //     return redirect()->action('Applicant\AccountController@index');

        // } catch (\Exception $exception) {
        //     return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();

        // }
//        try {
//            /*Proposal Report  Update*/
//            if (!empty($request->first_report)) {
//                $proposalreports = new ProposalReports();
//                $proposalreports->description = $request->first_report_description;
//
//                if (!empty($request->first_report)) {
//                    $proposalreports->document = $request->first_report->getClientOriginalName();
//                    $request->first_report->storeAs('/proposal/user-' . $id . '/', $request->first_report->getClientOriginalName());
//
//                }
//
//                $proposalreports->proposal_id = $id;
//                $proposalreports->due_date = '2019-06-27';
//                $proposalreports->approved = 1;
//                $proposalreports->save();
//            }
//
//            //TODO stugel ashxatum e code te voch
//            if (!empty($request->second_report)) {
//                $proposalreports = new ProposalReports();
//                $proposalreports->description = $request->second_report_description;
//                $proposalreports->document = $request->second_report->getClientOriginalName();
//                $request->second_report->storeAs('/proposal/user-' . $id . '/', $request->second_report->getClientOriginalName());
//                $proposalreports->proposal_id = $id;
//                $proposalreports->due_date = '2019-06-27';
//                $proposalreports->approved = 1;
//                $proposalreports->save();
//            }
//
//            return Redirect::back()->with('success', getMessage("success"));
//
//        } catch (\Exception $exception) {
//            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
//
//        }

        return redirect()->action('Applicant\ProposalController@activeProposal');

    }

    public function generatePDF($id)
    {
        // $proposal = Proposal::where('id','=',$id)->get()->toArray();

        $proposal = Proposal::find($id);
        $user_id = chooseUser();
        $competitions = Competition::all();
        $persons = Person::where('user_id', $user_id)->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $additional = json_decode($competition_name->additional);
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();;
        $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        $person_members = json_decode($proposal->proposal_members);
        $person_account = Person::whereIn('id', [$person_members->account_id, $person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
        $budget_item = BudgetItem::where('proposal_id', '=', $proposal->id)->get()->toArray();
        $budget_categories = BudgetCategory::where('competition_id', '=', $proposal->competition_id)->get()->toArray();
        $proposalreports = ProposalReports::where('proposal_id', '=', $id)->get()->toArray();
        $refereereport = RefereeReport::where('proposal_id', '=', $id)->get()->toArray();
        $data = ['title' => $proposal[0]['title'],
            'proposal' => $proposal,
            'competition_name' => $competition_name,
            'additional' => $additional,
            'cat_parent' => $cat_parent,
            'cat_sub' => $cat_sub,
            'cat_sec_parent' => $cat_sec_parent,
            'cat_sec_sub' => $cat_sec_sub,
            'person_account' => $person_account,
            'budget_item' => $budget_item,
            'budget_categories' => $budget_categories,
            'proposalreports' => $proposalreports,
            'refereereport' => $refereereport
        ];

        $pdf = PDF::loadView('applicant.proposal.pdf', $data);

        return $pdf->download($proposal->title . '.pdf');
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
