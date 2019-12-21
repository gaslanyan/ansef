<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Institution;
use App\Models\ProposalInstitution;
use App\Models\Proposal;
use App\Models\Country;
use App\Models\ProposalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PDF;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $competitions_lists = Competition::all();
        $request = Request::createFromGlobals();
          //if(!empty($request->competitions_lists)){
             // $proposals  = Proposal::select()->where('competition_id','=','$request->competitions_lists');
               //$proposals =Proposal::all();
          //}
        return view('viewer.proposal.index',compact('proposals','competitions_lists'));
    }

    public function getProposalByCompByID(Request $request)
    {
        $competitions_lists = Competition::all();

        if (isset($request->_token)) {
            $comp_id = $request->id;
            $proposals = Proposal::select('*')->where('competition_id', '=', $comp_id)->get()->toArray();

         // $proposals = json_encode($proposals);

            //echo $proposals;
           return view('viewer.proposal.competition',compact('proposals','competitions_lists'));;
        }
      //  echo view('viewer.proposal.index',compact('proposals','competitions_lists'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
//        $competitions = [];
//        $persons = [];
//
//        $user_id = getUserID();
//        $competitions = Competition::all();
//        $persons = Person::where('user_id', $user_id)->get()->toArray();
//        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
//        $institutions = Institution::all()->pluck('content', 'id')->toArray();
//
//        return view('applicant.proposal.create', compact('persons', 'competitions', 'countries', 'institutions'));
   }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*dd($request->choose_person);*/

        /*$validatedData = $request->validate([
            'category.*' => 'required|not_in:0',
            'sub_category.*' => 'required|not_in:0',
            'comp_prop' => 'required|not_in:choosecompetition',
            'title' => 'required|alpha|min:3',
            'abstract' => 'required|alpha|min:55',
            'prop_document' => 'required',
            'nationality' => 'required|in:Armenia',

        ]);*/
       try {

            $cat = [];
            $prop_members = [];
            $proposal = new Proposal();
            $proposal->title = $request->title;
            $proposal->abstract = $request->abstract;
            $proposal->state = "in-progress";
            $proposal->document = $request->prop_document->getClientOriginalName();
            $user_id = getUserID();
//            $persons = Person::where('user_id', $user_id)->get()->toArray();
            $folder = storage_path('/app/proposal/' . $user_id . '/');
//            if (!File::exists($folder)) {
//                File::makeDirectory($folder, 0775, true, true);
            $request->prop_document->storeAs('/app/proposal/' . $user_id . '/', $request->prop_document->getClientOriginalName());
            // }

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
            // $proposal->affiliation_institution_id = 10;
            for ($i = 0; $i < count($request->choose_person); $i++) {
                //$prop_members[$request->choose_person_t[$i]] = $request->choose_person[$i];
                $prop_members['account_id'] = $user_id;
//                $prop_members['admin_id'] = "";
                if ($request->choose_person_t[$i] == "PI") {
                    $prop_members['person_pi_id'] = $request->choose_person_id[$i];
                }
                if ($request->choose_person_t[$i] == "director") {
                    $prop_members['person_director_id'] = $request->choose_person_id[$i];
                }
            }
            $proposal->save();
            $proposal_id = $proposal->id;

            foreach ($request->budget_item_categories as $key => $val) {
                $budget_item = new BudgetItem();
                $budget_item->budget_cat_id = $request->budget_item_categories[$key];
                $budget_item->description = $request->budget_categories_description[$key];
                $budget_item->amount = $request->amount[$key];
                $budget_item->proposal_id = $proposal_id;
                $budget_item->save();
            }

            foreach ($request->institution as $key => $val) {
                $institution = new ProposalInstitution();
                $institution->proposal_id = $proposal_id;
                $institution->institution_id = (int)$request->institution[$key];;
                $institution->save();
            }

          return Redirect::back()->with('success', messageFromTemplate("success"));

        } catch (\Exception $exception) {
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $competitions = [];
        $persons = [];
        $user_id = getUserID();

        $proposal = Proposal::find($id);
        $competitions = Competition::select('title', 'submission_start_date')->where('id','=',$proposal->competition_id)->get()->first();
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();;
        $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        $person_account = Person::whereIn('id', [$person_members->account_id, $person_members->account_id, $person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
        $budget_item = \DB::table('budget_item')
            ->select('budget_item.*','budget_categories.name')
            ->join('budget_categories', 'budget_categories.id', '=', 'budget_item.budget_cat_id')
            ->where('proposal_id', '=', $id)
            ->get()->toArray();
        $proposalreports = ProposalReport::where('proposal_id','=',$id)->get()->toArray();

        $persons = Person::where('user_id', $user_id)->get()->toArray();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();
        return view('viewer.proposal.show',compact('proposalreports','budget_item','person_account','competitions','proposal','cat_parent','cat_sub','cat_sec_parent','cat_sec_sub'));
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
        $persons = Person::where('user_id', $user_id)->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();;
        $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        $person_account = Person::whereIn('id', [$person_members->account_id, $person_members->account_id, $person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();

        return view('viewer.proposal.edit', compact('competition_name', 'competitions', 'proposal', 'cat_parent', 'cat_sub',
            'cat_sec_parent', 'cat_sec_sub', 'persons', 'person_account'));
    }
    public function generatePDF($id)
    {
        // $proposal = Proposal::where('id','=',$id)->get()->toArray();

        $proposal = Proposal::find($id);
        $user_id = getUserID();
        $competitions = Competition::all();
        $persons = Person::where('user_id', $user_id)->get()->toArray();
        $competition_name = Competition::where('id', $proposal->competition_id)->get()->first();
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = Category::with('children')->where('id', $categories->sec_parent)->get()->first();;
        $cat_sec_sub = Category::with('children')->where('id', $categories->sec_sub)->get()->first();
        $person_account = Person::whereIn('id', [$person_members->account_id, $person_members->person_director_id, $person_members->person_pi_id])->get()->toArray();
        $budget_item = BudgetItem::where('proposal_id', '=', $proposal->id)->get()->toArray();
        $budget_categories = BudgetCategory::where('competition_id', '=', $proposal->competition_id)->get()->toArray();
        $data = ['title' => $proposal[0]['title'],
            'proposal' => $proposal,
            'competition_name' => $competition_name,
            'cat_parent' => $cat_parent,
            'cat_sub' => $cat_sub,
            'cat_sec_parent' => $cat_sec_parent,
            'cat_sec_sub' => $cat_sec_sub,
            'person_account' => $person_account,
            'budget_item' => $budget_item,
            'budget_categories' => $budget_categories
        ];

        $pdf = PDF::loadView('applicant.proposal.pdf', $data);

        return $pdf->download('proposal.pdf');
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
    /*Download PDF files*/
    public function downloadPDF($id){
        //$user = UserDetail::find($id);

        $pdf = PDF::loadView('pdf');
        return $pdf->download('invoice.pdf');

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
