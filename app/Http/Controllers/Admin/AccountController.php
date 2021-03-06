<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\DegreePerson;
use App\Models\Email;
use App\Models\Honor;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\ProposalPerson;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use App\Models\Publication;
use App\Models\Recommendation;
use App\Models\ProposalReport;
use App\Models\RefereeReport;
use App\Models\ProposalInstitution;
use App\Notifications\UserAddedSuccessfully;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{

    public function index()
    {
        try {
            $users = User::with('role')
                ->get();
            $roles = Role::all();

            return view('admin.person.index', compact('users', 'roles'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function account($type, $subtype)
    {
        $competitions = Competition::select('id', 'title')->get();

        return view('admin.account.list', compact('subtype', 'type', 'competitions'));
    }

    public function listpersons($cid, $subtype, $type) {
        ini_set('memory_limit', '256M');
        $d['data'] = [];
        if ($type == 'referee') {
            $persons = Person::where('type', '=', $type)->get();
            foreach ($persons as $index => $p) {
                $propcount = RefereeReport::where('referee_id', '=', $p->id)->count();
                $cats = RefereeReport::select('categories')
                            ->join('proposals', 'proposals.id','proposal_id')
                            ->where('referee_id', '=', $p->id)
                            ->get();
                $subcatarray = [];
                foreach($cats as $cat) {
                    $propcat = json_decode($cat->categories, true);

                    $subcat = Category::where('id', ($propcat["sub"])[0])->first()->abbreviation ?? 'None';
                    array_push($subcatarray, $subcat);
                }
                $a = array_unique($subcatarray);
                sort($a);

                $d['data'][$index]['first_name'] = $p->first_name ?? '';
                $d['data'][$index]['last_name'] = $p->last_name ?? '';
                $d['data'][$index]['email'] = $p->user->email;
                $d['data'][$index]['propcount'] = $propcount;
                $d['data'][$index]['subcats'] = truncate("<span style='font-size:9px;'>" . implode(" ", $a) . "</span>",100);
                $d['data'][$index]['awards'] = '';
                $d['data'][$index]['finalists'] = '';
            }
        }
        else if ($type == 'applicant') {
            Cookie::queue('cid', $cid, 24 * 60);
            $persons = ProposalPerson::where('competition_id', '=', $cid)
                                    ->where('subtype', $subtype)->get();
            foreach ($persons as $index => $p) {
                $person = Person::find($p->person_id);
                $propcount = ProposalPerson::where('person_id', '=', $p->person_id)->count();
                $as = ProposalPerson::join('proposals', 'proposals.id', '=', 'proposal_id')
                    ->join('competitions', 'competitions.id', '=', 'proposals.competition_id')
                    ->select('competitions.title')
                    ->where('person_id', '=', $p->person_id)
                    ->whereIn('proposals.state', ['awarded', 'approved 1', 'approved 2'])
                    ->get()->pluck('title');
                $asf = ProposalPerson::join('proposals', 'proposals.id', '=', 'proposal_id')
                    ->join('competitions', 'competitions.id', '=', 'proposals.competition_id')
                    ->select('competitions.title')
                    ->where('person_id', '=', $p->person_id)
                    ->where('proposals.state', '=', 'finalist')
                    ->get()->pluck('title');
                $awards = strval($as);
                $finalists = strval($asf);
                $d['data'][$index]['first_name'] = $person->first_name ?? '';
                $d['data'][$index]['last_name'] = $person->last_name ?? '';
                $d['data'][$index]['email'] = (!empty($person->emails()->first()) ? $person->emails()->first()->email : '');
                $d['data'][$index]['propcount'] = $propcount . "/" . count($as) . "/" . count($asf);
                $d['data'][$index]['subcats'] = "";
                $d['data'][$index]['awards'] = $awards;
                $d['data'][$index]['finalists'] = $finalists;
            }
        } else {
        }

        return Response::json($d);
    }

    public function generatePassword(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->confirmation = str_random(30) . time();
            $user->password_salt = 10;
            $user->requested_role_id = $user->role_id;
            $user->state = "inactive";
            $user->save();

            return redirect()->route('activate.addeduser', ['email' => $user->email, 'code' => $user->confirmation, 'admin' => 'true']);
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', 'Error setting password.');
        }
    }

    public function create()
    {
        try {
            $roles = Role::all();
            return view('admin.account.create', compact('roles'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate('wrong'));
        }
    }

    public function store(Request $request)
    {
        $generate_password = randomPassword();

        try {
            $v = Validator::make($request->all(), [
                'email' => 'required|email',
                'role' => 'required|numeric',
            ]);

            if (!$v->fails()) {
                $validatedData['email'] = $request->email;
                $validatedData['password'] = bcrypt($generate_password);
                $validatedData['confirmation'] = str_random(30) . time();
                $validatedData['password_salt'] = "10";
                $validatedData['requested_role_id'] = $request->role;
                $validatedData['state'] = "inactive";
                $user = User::create($validatedData);
                $user->notify(new UserAddedSuccessfully($user));
                return redirect()->action('Admin\AccountController@index');
            } else return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate('wrong'));
        }
    }

    public function show($id)
    {
        $person = Person::where('user_id', '=', $id)
            ->whereIn('type', ['admin', 'referee', 'viewer', 'applicant'])
            ->first();

        return view('admin.account.show', compact('person'));
    }

    public function update(Request $request)
    {
        try {
            $user = [];
            $person = [];
            if ($request->_token === Session::token()) {
                $items = json_decode($request->form);

                foreach ($items as $key => $value) {
                    if ($key === 'id') {
                        $id = $value;
                        $user = User::find((int) $id);
                        $p = Person::where('user_id', $id)->first();
                        $person = Person::find($p->id);
                    }

                    if ($key === 'email')
                        $user->email = $value;
                    if ($key === 'state')
                        $user->state = $value;
                    if ($key === 'status')
                        $user->role_id = $value;
                    if ($key === 'type')
                        $person->type = $value;
                }
            }
            if ($user->save() && $person->save())
                $response = [
                    'success' => true
                ];
            else
                $response = [
                    'success' => false,
                    'error' => 'Do not save'
                ];
            return response()->json($response);
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate('wrong'));
        }
    }

    public function destroy($id)
    {
        try {
            $p = Person::where('user_id', '=', $id)
                ->whereIn('type', ['applicant', 'referee', 'viewer', 'admin'])
                ->first();
            if (empty($p)) {
                User::where('id', $id)->delete();
                return redirect()->back()->with('delete', messageFromTemplate('deleted'));
            }
            $type = $p->type;
            switch ($type) {
                case 'applicant':
                    DegreePerson::where('user_id', $p->user_id)->delete();
                    Email::where('user_id', $p->user_id)->delete();
                    Honor::where('user_id', $p->user_id)->delete();
                    Address::where('user_id', $p->user_id)->delete();
                    Meeting::where('user_id', $p->user_id)->delete();
                    InstitutionPerson::where('user_id', $p->user_id)->delete();
                    Publication::where('user_id', $p->user_id)->delete();
                    BudgetItem::where('user_id', $p->user_id)->delete();
                    ProposalReport::where('user_id', $p->user_id)->delete();
                    RefereeReport::where('user_id', $p->user_id)->delete();
                    $proposals = Proposal::where('user_id', $p->user_id)->get();
                    foreach ($proposals as $proposal) {
                        ProposalInstitution::where('proposal_id', $proposal->id)->delete();
                        $file_path = storage_path(proppath($proposal->id));
                        if (is_dir($file_path)) File::deleteDirectory($file_path);
                        Recommendation::where('proposal_id', $proposal->id)->delete();
                    }
                    Proposal::where('user_id', $p->user_id)->delete();
                    Person::where('user_id', $p->user_id)->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
                case 'viewer';
                    $person = Person::where('user_id', $id)->first();
                    if(!empty($person)) $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
                case  'admin':
                    $person = Person::where('user_id', $id)->first();
                    if (!empty($person)) $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
                case 'referee':
                    $person = Person::where('user_id', $id)->first();
                    if (!empty($person)) RefereeReport::where('referee_id', $person->id)->delete();
                    if (!empty($person)) $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
            }
            return redirect()->back()->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('wrong', messageFromTemplate('wrong'));
        }
    }

    // public function mailreferee($id)
    // {
    //     $user = User::where('id', '=', $id)->first();
    //     $objSend = new \stdClass();
    //     $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at help@ansef.org.";
    //     $objSend->sender = config('emails.webmaster');
    //     $objSend->receiver = config('emails.webmaster');

    //     Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

    //     return redirect()->back()->with('status', 'Mail sent to referee');
    // }

    // public function mailviewer($id)
    // {
    //     $user = User::where('id', '=', $id)->first();
    //     $objSend = new \stdClass();
    //     $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at help@ansef.org.";
    //     $objSend->sender = config('emails.webmaster');
    //     $objSend->receiver = config('emails.webmaster');

    //     Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

    //     return redirect()->back()->with('status', 'Mail sent to referee');
    // }

    // public function updateAcc(Request $request)
    // {
    //     $user = [];
    //     $person = [];
    //     $isSaved = false;
    //     $isState = false;
    //     $items = json_decode($request->form);
    //     DB::beginTransaction();
    //     try {
    //         $id = $items->id;
    //         $user = User::find((int) $id);
    //         $user->email = trim($items->email);
    //         if (isset($items->state)) {
    //             $user->state = $items->state;
    //             $isState = true;
    //         }
    //         if ($user->save()) {
    //             $isSaved = true;
    //             if ($isSaved)
    //                 $user->notify(new ActivatedUser($user));
    //         }
    //         if (isset($items->first_name)) {
    //             $p = Person::where('user_id', $id)->first();
    //             $person = Person::find($p->id);
    //             $person->first_name = $items->first_name;
    //             $person->last_name = $items->last_name;
    //             if ($person->save())
    //                 $isSaved = true;
    //             if (isset($items->content)) {
    //                 $ip = InstitutionPerson::where('person_id', $p->id)->get();
    //                 $i = Institution::find($ip->institution_id);
    //                 $i->content = $items->content;
    //                 if ($i->save())
    //                     $isSaved = true;
    //             }
    //         }
    //         $response = [
    //             'success' => true
    //         ];
    //     } catch (\Exception $exception) {
    //         $response = [
    //             'success' => false,
    //             'error' => 'Do not save'
    //         ];
    //         DB::rollBack();
    //         logger()->error($exception);
    //     }
    //     //        }
    //     DB::commit();
    //     return response()->json($response);
    // }

    public function __construct()
    {
        // $this->except('logout');

    }
}
