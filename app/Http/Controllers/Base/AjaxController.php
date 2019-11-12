<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\City;
use App\Models\Competition;
use App\Models\Country;
use App\Models\Email;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Message;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReports;
use App\Models\RankingRule;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\Score;
use App\Models\ScoreType;
use App\Models\User;
use App\Notifications\ActivatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class AjaxController extends Controller
{
    public function city(Request $request)
    {
        $resp = [];
        $cites = City::where('cc_fips', '=', $request['cc_fips'])->pluck('name', 'id');
        $code = Country::where('cc_fips', '=', $request['cc_fips'])->pluck('country_phone_code');
        $resp[$code[0]] = json_encode($cites);
        echo json_encode($resp);
        exit;
    }

    public function subcategories(Request $request)
    {
        $resp = [];
        $sub = Category::where('parent_id', '=', $request['id'])->pluck('title', 'id')->toArray();
        echo $resp[0] = json_encode($sub);
        exit();

    }

    public function updatePerson(Request $request)
    {
        $user = [];
        $person = [];
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);

        foreach ($items as $key => $value) {
            if ($key === 'id') {
                $id = $value;
                $user = User::find((int)$id);
//                    $p = Person::where('user_id', $id)->first();
//                    $person = Person::find($p->id);
            }

            if ($key === 'email')
                $user->email = $value;
            if ($key === 'state')
                $user->state = $value;
            if ($key === 'status')
                $user->role_id = $value;
//                if ($key === 'type')
//                    $person->type = $value;
        }
//        }
        if ($user->save())
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);

    }

    public function updateAcc(Request $request)
    {
        $user = [];
        $person = [];
        $isSaved = false;
        $isState = false;
//        dd(Session::token());
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        DB::beginTransaction();
        try {
            $id = $items->id;
            $user = User::find((int)$id);
            $user->email = trim($items->email);
            if (isset($items->state)) {
                $user->state = $items->state;
                $isState = true;
            }
            if ($user->save()) {
                $isSaved = true;
                if ($isSaved)
                    $user->notify(new ActivatedUser($user));
            }
            if (isset($items->first_name)) {
                $p = Person::where('user_id', $id)->first();
                $person = Person::find($p->id);
                $person->first_name = $items->first_name;
                $person->last_name = $items->last_name;
                if ($person->save())
                    $isSaved = true;
                if (isset($items->content)) {
                    $ip = InstitutionPerson::where('person_id', $p->id)->get();
                    $i = Institution::find($ip->institution_id);
                    $i->content = $items->content;
                    if ($i->save())
                        $isSaved = true;
                }
            }
            $response = [
                'success' => true
            ];

        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function updateCategory(Request $request)
    {
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        $category = Category::where('id', '=', $items->id)->first();
        $category->id = $items->id;
        if ($items->parent_id == 0)
            $category->parent_id = null;
        else
            $category->parent_id = $items->parent_id;
        $category->abbreviation = $items->abbreviation;
        $category->title = $items->title;
        $category->weight = $items->weight;
        if ($category->save()) {
            $response = [
                'success' => true
            ];
        } else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);
    }

    public function updateAccount(Request $request)
    {
        $user = [];
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        foreach ($items as $key => $value) {
            if ($key === 'id') {
                $id = $value;
                $user = User::find((int)$id);
                // $p = Person::where('id', $id)->first();
                $person = Person::find($id);
            }

//            if ($key === 'email')
//                $user->email = $value;
//            if ($key === 'state')
//                $user->state = $value;

            if ($key === 'firstname') {
                $person->first_name = $value;
            }
            if ($key === 'lastname') {
                $person->last_name = $value;
            }
            if ($key === 'type') {
                $person->type = $value;
            }

        }

//        }
        if ($person->save())
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);

    }

    /*Update State  Of Proposal */
    public function updateProposalState(Request $request)
    {
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        foreach ($items as $key => $value) {
            if ($key === 'id') {
                $id = $value;
                $proposal = Proposal::find((int)$id);

            }
            if ($key === 'type')
                if ($value == 'submitted') {
                    $person_members = json_decode($proposal->proposal_members);
                    if (!empty($person_members->person_support_id)) {
                        $emails = Email::select('email')->where('person_id', '=', $person_members->person_support_id)->get()->toArray();

                        if (!empty($emails)) {
                            dd($value);//$proposal->state = $value;
                        } else {
                            dd($key);//   echo "<script>alert('Please make sure all people have a emails; ');</script>";
                        }

                    }
                } else {
                    $proposal->state = $value;
                }
        }
//        }
        if ($proposal->save())
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);

    }

    /*Update State  Of Proposal */

    public function updateCompetition(Request $request)
    {
//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        if (Competition::find((int)$items->id)->update((array)$items))
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);
//        }
    }

    public function updateProposal(Request $request)
    {

//        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        $id = 0;
        $state = "";
        foreach ($items as $key => $value) {
            switch ($key) {
                case 'id':
                    $id = $value;
                    break;
                case 'state':
                    $state = $value;
                    break;
            }
        }
        $proposal = Proposal::find((int)$id);
        if ($proposal) {
            $proposal->state = $state;
        }
//        }
        if ($proposal->save())
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);

    }

    public function addUsers(Request $request)
    {
        if (isset($request->_token)) {
            $p_id = json_decode($request->p_ids);
            $u_ids = json_decode($request->u_ids);
            foreach ($u_ids as $index => $item) {
                $u_id[$index] = $item;
            }

            $type = "proposal_" . $request->type . "s";
            $response = [];
            $scors = [];

            $porc = Proposal::with(['competition.score'])->where('id', $p_id)->get();

            foreach ($porc as $index => $gSTN) {
                if (!empty($gSTN->competition->score)) {
                    foreach ($gSTN->competition->score as $index => $item) {
                        $scors[strtolower($item->name)] = 0;
                    }
                }

            }

            foreach ($p_id as $index => $item) {
                $add_r = Proposal::find($item);
                $add_r->$type = json_encode($u_id, JSON_FORCE_OBJECT);
                $add_r->state = "in-review";
                if ($add_r->save()) {
//                    todo messages
                    $message = Message::where('id', '=', 1)->first();
                    foreach ($u_id
                             as $i => $items) {
                        if ($request->type == "referee") {
                            $report = new RefereeReport();
                            $report->proposal_id = $item;
                            $report->referee_id = $items;
//                            $report->scores = json_encode($scors, JSON_FORCE_OBJECT);
                            $report->state = "in-progress";
                            $report->save();
                        }
                        $user = Person::with('user')->where('id', '=', $items)->first();

                        $objSend = new \stdClass();
                        $objSend->message = $message->text;
                        $objSend->sender = 'Ansef';
                        $objSend->receiver = 'collages';

                        Mail::to($user->user->email)->send(new \App\Mail\ChooseBoard($objSend));

                        $response = [
                            'success' => true
                        ];
                    }
                } else {

                    $response = [
                        'success' => false,
                        'error' => 'Do not save'
                    ];
                    break;
                }
            }
            return response()->json($response);
        }
    }

    public function copyItems(Request $request)
    {
        if (isset($request->_token)) {
            $id = $request->id;
            $response = DB::select('select * from ' . $request->table . ' where id = :id', ['id' => $id]);
            return response()->json($response[0]);
        }
    }

    public function getCompetitionContentByID(Request $request)
    {
        if (isset($request->_token)) {
            $comp_id = $request->id;

            $content = [];
            $com = Competition::select('recommendations_id', 'allow_foreign', 'categories', 'additional')->where('id', '=', $comp_id)->first();
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

            $content['bc'] = BudgetCategory::where('competition_id', '=', $comp_id)->get()->toArray();
            $content['st'] = ScoreType::where('competition_id', '=', $comp_id)->get()->toArray();
            $content['recommendition'] = $com->recommendations_id;
            $content['allowforeign'] = $com->allow_foreign;
            $content['additional'] = json_decode($com->additional);

            echo json_encode($content);
            exit;
        }

    }

    public function getProposalByCategory(Request $request)
    {
        try{
        if (isset($request->_token)) {
            $cat_id = $request->id;

            $sub_cat = Category::where('parent_id', $cat_id)->exists();
            $cats = Proposal::select('categories')->get();
            $selected_cat = [];
            foreach ($cats as $index => $cat) {
                $j_c = json_decode($cat->categories, true);
                foreach ($j_c as $i => $item) {
                    $selected_cat[] = $item[0];
                }
            }

            if (in_array($cat_id, $selected_cat ) && $sub_cat)
                $response = [
                    'success' => true
                ];
            else
                $response = [
                'success' => false,
                'error' => 'Do not save'
            ];

        }
        }catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        return response()->json($response);
    }

    public function deleteProposal(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $proposal_ids = $request->id;
            foreach ($proposal_ids as $index => $p_id) {
                BudgetItem::where('proposal_id', $p_id)->delete();
                ProposalInstitution::where('proposal_id', $p_id)->delete();
                ProposalReports::where('proposal_id', $p_id)->delete();
                Recommendations::where('proposal_id', $p_id)->delete();
                RefereeReport::where('proposal_id', $p_id)->delete();
                $file_path = storage_path('proposal/prop-' . $p_id);
                if (is_dir($file_path))
                    File::deleteDirectory($file_path);
                Proposal::find($p_id)->delete();
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteCats(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $cat_ids = $request->id;
            $cats = Proposal::select('categories')->get();
            $selected_cat = [];
            foreach ($cats as $index => $cat) {
                $j_c = json_decode($cat->categories, true);
                foreach ($j_c as $i => $item) {
                    $selected_cat[] = $item[0];
                }
            }
            foreach ($cat_ids as $ii => $c) {
                if (!in_array($c, $selected_cat))
                    Category::where('id', $c)->delete();
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteScores(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $response = [];
            $score_ids = $request->id;
            $score = Score::whereIn('score_type_id',
                (array)$score_ids)->get()->toArray();

            if (empty($score)) {
                foreach ($score_ids as $index => $cat) {
                    ScoreType::where('id', $cat)->delete();

                }
                $response = [
                    'success' => true
                ];
            } else {
                $response = [
                    'success' => -1
                ];
            }


        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);

        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteRule(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $rule_ids = $request->id;

            foreach ($rule_ids as $index => $cat) {
                RankingRule::where('id', $cat)->delete();
                $response = [
                    'success' => true
                ];

            }
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteReport(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $report_ids = $request->id;

            foreach ($report_ids as $index => $id) {
                RefereeReport::where('id', $id)->delete();
                $response = [
                    'success' => true
                ];

            }
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteBudgets(Request $request)
    {
        DB::beginTransaction();
        try {
//        if (isset($request->_token)) {
            $budget_ids = $request->id;
            $items = BudgetItem::select('budget_cat_id')->groupBy('budget_cat_id')->get()->toArray();
            $items = array_column($items, 'budget_cat_id');
            foreach ($budget_ids as $index => $item) {
                if (!in_array($item, $items)) {
                    BudgetCategory::where('id', $item)->delete();
                }
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
//        }
        DB::commit();
        return response()->json($response);
    }

    public function duplicateCats(Request $request)
    {

        DB::beginTransaction();
        try {
            $cats = Category::select('*')->
            whereIn('id',
                (array)($request->id))->get()->toArray();
            $_cats = [];
            foreach ($cats as $key => $cat) {
//            unset($cat['id']);
                if (empty($cat['parent_id'])) {
                    $cat['parent_id'] = null;
                }
                $_cats[$key]['abbreviation'] = $cat['abbreviation'];
                $_cats[$key]['title'] = $cat['title'];
                $_cats[$key]['weight'] = $cat['weight'];
                $_cats[$key]['parent_id'] = $cat['parent_id'];
                $_cats[$key]['created_at'] = $cat['created_at'];
                $_cats[$key]['updated_at'] = $cat['updated_at'];

            }
            Category::insert($_cats);
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        DB::commit();
        return response()->json($response);
        exit();
    }

    public function sendEmail(Request $request)
    {
        $IDs = json_decode($request->ids);
        $pi_json = Proposal::select('proposal_members')->whereIn('id', $IDs)->get();

        $message = Message::where('id', '=', $request->t_id)->first();
        $objSend = new \stdClass();
        $objSend->message = $message->text;
        $objSend->sender = 'Ansef';
        $objSend->receiver = 'collages';

        $items = [];
        foreach ($pi_json as $index => $json) {
            $pi = json_decode($json->proposal_members);
            $email = Person::with('user')->where('id', $pi->person_pi_id)->first();
            //             $email->user->email;
            Mail::to($email->user->email)->send(new \App\Mail\Invitation($objSend));
            return response()->json('ok');
        }

    }

    public function changeState(Request $request)
    {
        try {
            $IDs = json_decode($request->ids);
            foreach ($IDs as $index => $ID) {
                Proposal::where('id', $ID)
                    ->update(['state' => $request->state]);
            }
            $response = [
                'success' => true,
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        return response()->json($response);
        exit();
    }

    public function getProposal(Request $request)
    {
        $_id = $request->id;

        $count = Proposal::where('competition_id', $_id)->count();

        if ($count > 0)
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not available'
            ];

        return response()->json($response);

    }

    public function getProposalByApplicant(Request $request)
    {
        $_id = $request->id;

        $p = Person::select('id')->where('user_id', $_id)->first();
        if (!empty($p)) {
            $pm = Proposal::select('proposal_members')->get();
            $arr = [];
            foreach ($pm as $index => $item) {
                $js = json_decode($item->proposal_members, true);
                if (!empty($js['account_id']))
                    $arr[] = $js['account_id'];
            }
            if (in_array($p->id, $arr))
                $response = [
                    'success' => true
                ];
            else
                $response = [
                    'success' => false,
                    'error' => 'Do not available'
                ];
        } else {
            $response = [
                'success' => false
            ];
        }
        return response()->json($response);

    }

    public function getProposalByReferee(Request $request)
    {
        $_id = $request->id;
        $isJoined = false;
        $p = Person::select('id')->where('user_id', $_id)->first();
        if (!empty($p)) {
            $pm = Proposal::select('proposal_referees')->get();

            foreach ($pm as $index => $item) {
                $js = json_decode($item->proposal_referees, true);
                if (in_array($p->id, $js)) {
                    $response = [
                        'success' => true
                    ];
                    $isJoined = true;
                }
                break;
            }
            if (!$isJoined)
                $response = [
                    'success' => false,
                    'error' => 'Do not available'
                ];
        } else {
            $response = [
                'success' => false
            ];
        }
        return response()->json($response);

    }

    public function getBudgetByCategory(Request $request)
    {
        $_id = $request->id;
        $bi = BudgetItem::where('budget_cat_id', $_id)->first();
        if (!empty($bi)) {
            $response = [
                'success' => true,
            ];
        } else {
            $response = [
                'success' => false
            ];
        }
        return response()->json($response);

    }

    public function getSTypeCount(Request $request)
    {
        try {
            $_id = $request->id;
            $count = ScoreType::where('competition_id', $_id)->get()->count();
            if (!empty($count))
                $response = [
                    'count' => $count,
                    'success' => true
                ];
            else
                $response = [
                    'success' => false,
                    'count' => 0
                ];

        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];

            logger()->error($exception);
        }
        return response()->json($response);

    }

    public function getRR(Request $request)
    {
        try {
            $_id = $request->id;
            $rs = RankingRule::select('id', 'sql')->where('competition_id', $_id)->get();
            if (!empty($rs))
                $response = [
                    'rs' => json_encode($rs, true),
                    'success' => true
                ];
            else
                $response = [
                    'success' => false,
                    'count' => 0
                ];

        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];

            logger()->error($exception);
        }
        return response()->json($response);

    }
//    public function createSQL(Request $request)
//    {
//        try {
//            $_ids = $request->id;
//            $rs = RankingRule::select('id','sql','value')->where('competition_id', $_id)->get();
//            if (!empty($rs))
//                $response = [
//                    'rs' => json_encode($rs, true),
//                    'success' => true
//                ];
//            else
//                $response = [
//                    'success' => false,
//                    'count' => 0
//                ];
//
//        } catch (\Exception $exception) {
//            $response = [
//                'success' => false,
//                'error' => 'Do not save'
//            ];
//
//            logger()->error($exception);
//        }
//        return response()->json($response);
//
//    }

    public function approve(Request $request)
    {
        try {
            $r = json_decode($request->form, true);
            $response = "";

            if (!empty($r['id']) && !empty($r['approve'])) {

                $approve = ProposalReports::find($r['id']);

                $approve->approved = $r['approve'];
                if ($approve->save())
                    $response = [
                        'success' => true
                    ];
                else
                    $response = [
                        'success' => false,

                    ];
            }
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];

            logger()->error($exception);
        }
        return response()->json($response);
    }

    /* VIEWER ajax requests */

    public function getCompetitionsListForStatistics(Request $request)
    {
        $value = $request->value;
        $content = [];
        if ($value == 'competition') {
            $content['comp'] = Competition::all();
            foreach ($content['comp'] as $np) {
                $np_count = Proposal::select('id', 'competition_id')->where('competition_id', '=', $np->id)->count();//->get()->toArray();
//                 $np_count = DB::table("proposals")
//
//                     ->select("id", "competition_id", DB::raw('COUNT(id) as id'))
//
//                    ->get();
                $content['numberofproposals'] = $np_count;
            }

        } else if ($value == 'pi') {
            $content['pi'] = Person::all();
        }

//                $response = [
//                    'success' => false,
//                    'error' => 'Do not available'
//                ];

        echo json_encode($content);
        exit();
    }

    public function report(Request $request)
    {
        $d['data'] = [];
        $reports = RefereeReport::with(['proposal' => function ($query) {
            $query->select('id', 'title', 'proposal_admins', 'comment');
        }, 'person' => function ($query) {
            $query->select('id', 'first_name', 'last_name');
        }])
            ->get();

        foreach ($reports as $index => $report) {
            $scores = Score::with(['scoreType' => function ($query) {
                $query->select('name', 'id');
            }])
                ->where('report_id', '=', $report['id'])
                ->get();
            $s = [];
            foreach ($scores as $i => $score) {
                $s[$i]['name'] = $score->scoreType->name;
                $gstv = getScoreTypeValues();
                $s[$i]['value'] = $gstv[$score->value];
            }

            $d['data'][$index]['id'] = $report['id'];
            $d['data'][$index]['url'] = 'Admin\ReportController@destroy';
            $d['data'][$index]['title'] = $report['proposal']['title'];
            $d['data'][$index]['referee'] = $report['person']['first_name'] . ' ' . $report['person']['last_name'];
            $d['data'][$index]['admin'] = $report['proposal']['proposal_admins'];
            $d['data'][$index]['dur_date'] = $report['dur_date'];
            $d['data'][$index]['overall_scope'] = $report['overall_scope'];
            $d['data'][$index]['state'] = $report['state'];
            $d['data'][$index]['comment'] = $report['proposal']['comment'];

            if (!empty($scores)) {
                $d['data'][$index]['scores'] = json_encode($s);
            }
//
        }

        return Response::json($d);

    }
}
