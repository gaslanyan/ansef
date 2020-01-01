<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Message;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReport;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\Role;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade as PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

class ProposalController extends Controller
{
    public function list()
    {
        try {
            // \Debugbar::error('Something is definitely going wrong.');

            $competitions = Competition::select('id', 'title')
                ->orderBy('submission_end_date', 'desc')
                ->get()->toArray();

            $referee = Role::where('name', '=', 'referee')->first();
            $admin = Role::where('name', '=', 'admin')->first();
            $superadmin = Role::where('name', '=', 'superadmin')->first();

            $referees = $referee->persons->sortBy('last_name');
            $adminpersons = $admin->persons;
            $superadminpersons = $superadmin->persons;
            $admins = $adminpersons->concat($superadminpersons)->all();
            $messages = Message::all();
            $enumvals = getEnumValues('proposals', 'state');

            return view('admin.proposal.index', compact('referees', 'admins', 'messages', 'enumvals', 'competitions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal/list')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function awardslist()
    {
        try {
            $competitions = Competition::select('id', 'title')
                ->orderBy('submission_end_date', 'desc')
                ->get()->toArray();

            $referee = Role::where('name', '=', 'referee')->first();
            $admin = Role::where('name', '=', 'admin')->first();
            $superadmin = Role::where('name', '=', 'superadmin')->first();

            $referees = $referee->persons;
            $adminpersons = $admin->persons;
            $superadminpersons = $superadmin->persons;
            $admins = $adminpersons->concat($superadminpersons)->all();
            $messages = Message::all();
            $enumvals = getEnumValues('proposals', 'state');

            return view('admin.proposal.awardsindex', compact('referees', 'admins', 'messages', 'enumvals', 'competitions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal/list')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function index()
    {
    }

    public function create()
    {
        try {
            $title = Proposal::select('title')->where('id', Session::get('p_id'))->first();
            return view("admin.proposal.create", compact('title'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function display(Request $request)
    {
        $pid = $request['id'];
        $proposal = Proposal::find($pid);
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
        $recommendations = Recommendation::where('proposal_id', '=', $pid)->get();
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

        return view('admin.proposal.show', compact(
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
            'recommendations',
            'reports'
        ));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {

            $v = Validator::make($request->all(), [
                'comment' => 'required|max:1024'
            ]);
            if (!$v->fails()) {
                $proposal = Proposal::find($id);
                $proposal->comment = $request->comment;
                $proposal->save();

                return redirect('admin/proposal/' . $id)->with('success', messageFromTemplate("success"));
            } else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', messageFromTemplate("wrong"));
            //        }
        }
    }

    public function destroy($p_id)
    {
        DB::beginTransaction();
        try {
            BudgetItem::where('proposal_id', $p_id)->delete();
            ProposalInstitution::where('proposal_id', $p_id)->delete();
            ProposalReport::where('proposal_id', $p_id)->delete();
            Recommendation::where('proposal_id', $p_id)->delete();
            RefereeReport::where('proposal_id', $p_id)->delete();

            $file_path = storage_path(proppath($p_id));
            if (is_dir($file_path)) File::deleteDirectory($file_path);
            Proposal::find($p_id)->delete();
            DB::commit();
            return redirect('admin/proposal')->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', messageFromTemplate('wrong'));
        }
    }

    public function listproposals($cid)
    {
        Cookie::queue('cid', $cid, 24 * 60);
        ini_set('memory_limit', '384M');
        $d['data'] = [];

        if ($cid == -1) {
            $proposals = Proposal::all()->sortBy('id');
        } else {
            $proposals = Proposal::where('competition_id', '=', $cid)
                ->orderBy('id', 'asc')
                ->get();
        }
        foreach ($proposals as $index => $pr) {
            $d['data'][$index]['id'] = $pr->id;
            $d['data'][$index]['tag'] = getProposalTag($pr->id);
            $d['data'][$index]['title'] = truncate($pr->title, 25);
            $d['data'][$index]['state'] = ($pr->state);
            $d['data'][$index]['score'] = strval(round($pr->overall_score)) . "%";
            $d['data'][$index]['rank'] = ($pr->rank);
            $pi = $pr->pi();
            $d['data'][$index]['pi'] = !empty($pi) ? truncate($pi->last_name, 7) . " " . $pi->first_name : 'No PI';
            $refs = $pr->refereesasstring();
            $d['data'][$index]['refs'] = !empty($refs) ? $refs : '';
            $a = $pr->admin()->first();
            $d['data'][$index]['admin'] = !empty($a) ? substr($a->last_name, 0, 4) . '.' : \App\Models\User::find($a->user_id)->email;
        }

        return Response::json($d);
    }

    public function downloadfirstreport(Request $request)
    {
        $pid = $request['id'];
        $pr = Proposal::find($pid);
        $propreports = $pr->propreports;
        foreach ($propreports as $propreport) {
            if ($propreport->due_date == $pr->competition->first_report) {
                if (Storage::exists(ppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"))
                    return response()->download(storage_path(proppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"));
                else return response()->json(new \stdClass());
            } else {
                return response()->json(new \stdClass());
            }
        }
    }

    public function downloadsecondreport(Request $request)
    {
        $pid = $request['id'];
        $pr = Proposal::find($pid);
        $propreports = $pr->propreports;
        foreach ($propreports as $propreport) {
            if ($propreport->due_date == $pr->competition->second_report) {
                if (Storage::exists(ppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"))
                    return response()->download(storage_path(proppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"));
                else return response()->json(new \stdClass());
            }
        }
        return response()->json(new \stdClass());
    }

    public function listawards($cid)
    {
        Cookie::queue('cid', $cid, 24 * 60);
        $d['data'] = [];

        if ($cid == -1) {
            $proposals = Proposal::whereIn('state', ['awarded', 'approved 1', 'approved 2'])
                                ->get()->sortBy('id');
        } else {
            $proposals = Proposal::where('competition_id', '=', $cid)
                ->whereIn('state', ['awarded', 'approved 1', 'approved 2'])
                ->orderBy('id', 'asc')
                ->get();
        }
        foreach ($proposals as $index => $pr) {
            $d['data'][$index]['id'] = $pr->id;
            $d['data'][$index]['tag'] = getProposalTag($pr->id);
            $d['data'][$index]['title'] = truncate($pr->title, 25);
            $d['data'][$index]['state'] = ($pr->state);
            $d['data'][$index]['score'] = strval(round($pr->overall_score)) . "%";
            $pi = $pr->pi();
            $d['data'][$index]['pi'] = !empty($pi) ? truncate($pi->last_name, 7) . " " . $pi->first_name : 'No PI';
            $propreports = $pr->propreports;
            $d['data'][$index]['first'] = false;
            $d['data'][$index]['second'] = false;
            foreach ($propreports as $propreport) {
                if ($propreport->due_date == $pr->competition->first_report) {
                    $d['data'][$index]['first'] = true;
                } else if ($propreport->due_date == $pr->competition->second_report) {
                    $d['data'][$index]['second'] = true;
                } else {
                }
            }
        }

        return Response::json($d);
    }

    public function checkProposal(Request $request)
    {
        DB::beginTransaction();
        try {
            $proposal_ids = $request->id;
            foreach ($proposal_ids as $index => $p_id) {
                $r = checkproposal($p_id);
                $messages =  $r['messages'];
                $submittedrecs = $r['submittedrecs'];
                $p = Proposal::find($p_id);

                if (count($messages) > 0 || count($submittedrecs) < $p->competition->recommendations) {
                    $p->state = "disqualified";
                    $p->save();
                } else {
                    updateProposalState($p->id);
                }
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Error while checking'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        DB::commit();
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
                ProposalReport::where('proposal_id', $p_id)->delete();
                Recommendation::where('proposal_id', $p_id)->delete();
                RefereeReport::where('proposal_id', $p_id)->delete();
                $file_path = storage_path(proppath($p_id));
                if (is_dir($file_path)) File::deleteDirectory($file_path);
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

    public function sendEmail(Request $request)
    {
        $IDs = json_decode($request->ids);
        $counter = 0;
        foreach ($IDs as $ID) {
            $prop = Proposal::find($ID);
            if (!empty($prop)) {
                $pi = $prop->pi();
                if (!empty($pi)) {
                    $to = Email::where('person_id', '=', $pi->id)->first();
                    $name = $pi->first_name . " " . $pi->last_name;
                    $tag = getProposalTag($prop->id);
                    if (!empty($to)) {
                        $subject = $request->subject;
                        $data = ['tag' => $tag, 'name' => $name, 'content' => $request->content];
                        $counter++;
                        Mail::send(
                            ['text' => 'admin.email.emailtemplate'],
                            $data,
                            function ($message) use ($subject, $to) {
                                $message->to($to->email)
                                    ->subject($subject);
                                $message->from(config('emails.RB'), 'ANSEF Research Board');
                            }
                        );
                    }
                }
            }
        }

        return response()->json('Sent ' . $counter . ' of ' . count($IDs) . ' emails.');
    }

    public function addUsers(Request $request)
    {
        if (isset($request->_token)) {
            $p_ids = json_decode($request->p_ids);
            $u_ids = json_decode($request->u_ids);
            foreach ($u_ids as $index => $item) {
                $u_id[$index] = $item;
            }

            $response = [];
            $scors = [];

            $flag = true;
            foreach ($p_ids as $pid) {
                if (!$flag) break;
                $proposal = Proposal::find($pid);
                foreach ($u_ids as $uid) {
                    if ($request->type == "admin") {
                        $proposal->proposal_admin = $uid;
                    } else if ($request->type == "referee") {
                        $report = new RefereeReport();
                        $report->proposal_id = $pid;
                        $report->referee_id = $uid;
                        $report->competition_id = $proposal->competition->id;
                        $report->overall_score = 0;
                        $report->private_comment = "";
                        $report->public_comment = "";
                        $report->state = "in-progress";
                        $report->user_id = getUserID();
                        $report->due_date = date('Y-m-d', strtotime('+3 months'));
                        $flag = $flag & $report->save();
                        if (!$flag) break;
                        $proposal->state = 'in-review';
                    } else {
                    }
                }
                $flag = $flag & $proposal->save();
            }

            $response = [
                'success' => true
            ];
            if (!$flag) {
                $response = [
                    'success' => false,
                    'error' => 'Cannot make assignment'
                ];
            }
            return response()->json($response);
        }
    }

    public function generatePDF($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::find($id);
        $pid = $proposal->id;
        $recommendations = Recommendation::where('proposal_id', '=', $pid)->get();
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
            'recommendations' => $recommendations,
            'reports' => $reports
        ];

        $pdf = PDF::loadView('admin.proposal.pdf', $data);
        $pdf->save(storage_path(proppath($pid) . '/combined.pdf'));

        $pdfMerge = PDFMerger::init();
        if (Storage::exists(ppath($pid) . '/combined.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/combined.pdf'), 'all');
        if (Storage::exists(ppath($pid) . '/document.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/document.pdf'), 'all');
        foreach ($recommendations as $r) {
            if (Storage::exists(ppath($pid) . '/letter-' . $r->id . '.pdf'))
                $pdfMerge->addPDF(storage_path(proppath($pid) . '/letter-' . $r->id . '.pdf'), 'all');
        }
        $pdfMerge->merge();

        $pdfMerge->save(storage_path(proppath($pid) . '/download.pdf'), 'download');

        Storage::delete('proposals/prop-' . $pid . '/combined.pdf');
    }

    public function getProposalByApplicant(Request $request)
    {
        $_id = $request->id;

        $p = Person::select('id')->where('user_id', $_id)->first();
        if (!empty($p)) {
            $arr = [];
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

    public function getProposalByCategory(Request $request)
    {
        try {
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

                if (in_array($cat_id, $selected_cat) && $sub_cat)
                    $response = [
                        'success' => true
                    ];
                else
                    $response = [
                        'success' => false,
                        'error' => 'Do not save'
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
        return response()->json($response);
    }
}
