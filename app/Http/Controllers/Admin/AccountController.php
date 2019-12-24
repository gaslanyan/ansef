<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BudgetItem;
use App\Models\Book;
use App\Models\Competition;
use App\Models\Country;
use App\Models\DegreePerson;
use App\Models\Email;
use App\Models\Honor;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\ProposalPerson;
use App\Models\Phone;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{

    public function index() {
        try {
            $persons = User::with('role')->get();
            $roles = Role::all();

            return view('admin.person.index', compact('persons', 'roles'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function account($type)
    {
        ini_set('memory_limit', '2048M');

        try {
            $persons = collect([]);
            $total = Person::where('type', '=', $type)->count();

            foreach(Person::where('type', '=', $type)->cursor() as $index => $p) {
                $awards = '';
                $finalists = '';
                if ($type == 'referee') {
                    $propcount = RefereeReport::where('referee_id', '=', $p->id)->count();
                }
                else if ($type == 'participant') {
                    $propcount = ProposalPerson::where('person_id', '=', $p->id)->count();
                    $as = ProposalPerson::join('proposals', 'proposals.id', '=', 'proposal_id')
                            ->select('proposals.competition_id')
                            ->where('person_id', '=', $p->id)
                            ->whereIn('proposals.state',['awarded','approved 1','approved 2'])
                            ->get();
                    $asf = ProposalPerson::join('proposals', 'proposals.id', '=', 'proposal_id')
                            ->select('proposals.competition_id')
                            ->where('person_id', '=', $p->id)
                            ->where('proposals.state', '=', 'finalist')
                            ->get();
                    foreach($as as $award) {
                        $awards .= (Competition::find($award->competition_id)->title . " ");
                    }
                    foreach ($asf as $finalist) {
                        $finalists .= (Competition::find($finalist->competition_id)->title . " ");
                    }
                }
                else {
                    $propcount = ProposalPerson::where('person_id', '=', $p->id)->count();
                }
                $persons->push(['first_name' => $p->first_name,
                                'last_name' => $p->last_name,
                                'email' => $type == 'referee' ? $p->user->email : (!empty($p->emails()->first()) ? $p->emails()->first()->email : ''),
                                'propcount' => $propcount,
                                'awards' => $awards,
                                'finalists' => $finalists
                                ]);
            }
            // dd($persons);

            return view('admin.account.list', compact('persons', 'type'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/account')->with('error', messageFromTemplate('wrong'));
        }
    }

    public function generatePassword(Request $request, $id)
    {
        try {
            $user = Person::find($id)->user;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        try {
        $person = Person::where('user_id', '=', $id)->first()->toArray();

        $person_id = $person['id'];
        $emails = Email::where('person_id', $person_id)->get();
        $phones = Phone::where('person_id', $person_id)->get()->toArray();
        $address_array = [];

        foreach ($addr as $key => $value) {
            $a = Address::select('country_id')
                ->where('id', $value['address_id'])->first();

            $country = Country::where('id', $a->country_id)->first();

            $address = Address::find($value['address_id'])->toArray();
            $address_array[$key]['country'] = $country['country_name'];
            $address_array[$key]['province'] = $address['province'];
            $address_array[$key]['street'] = $address['street'];
        }
        $institution = [];
        $ip = InstitutionPerson::with('iperson')
            ->select('title', 'start', 'end', 'type')
            ->where('person_id', '=', $person_id)
            ->get()->toArray();
        $institution['ip'] = $ip;
        $ip_add = Address::where('address.id', 24)
            ->
            join('countries', 'countries.id', '=', 'address.country_id')
            ->get()->toArray();
        $institution['addr'] = $ip_add;
        $books = Book::select('title', 'publisher', 'year')->where('person_id', $person_id)->get()->toArray();

        $degrees = DegreePerson::select('degree_id', 'year')->with('degree')->where('person_id', $person_id)->get()->toArray();

        $honors = Honor::select('description', 'year')->where('person_id', $person_id)->get()->toArray();
        $meetings = Meeting::select('description', 'year', 'ansef_supported', 'domestic')->where('person_id', $person_id)->get()->toArray();

        return view('admin.account.show', compact('person',
            'phones', 'emails', 'address_array', 'books', 'degrees', 'honors', 'meetings', 'institution'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect()->back()->with('error', messageFromTemplate('wrong'));
//        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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
                        $user = User::find((int)$id);
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
            $p = Person::find($id);
            $type = $p->type;
            switch ($type) {
                case 'applicant':
                    $persons = Person::where('user_id', $p->user_id)->get();
                    foreach ($persons as $person) {
                        DegreePerson::where('person_id', $person->id)->delete();
                        Email::where('person_id', $person->id)->delete();
                        Honor::where('person_id', $person->id)->delete();
                        Address::where('addressable_id', $person->id)
                                ->where('addressable_type','App\Models\Person')->delete();
                        Meeting::where('person_id', $person->id)->delete();
                        InstitutionPerson::where('person_id', $person->id)->delete();
                        Publication::where('person_id', $person->id)->delete();
                        Recommendation::where('person_id', $person->id)->delete();
                        $proposals = Proposal::where('user_id', $p->user_id)->get();
                        foreach($proposals as $proposal) {
                            BudgetItem::where('proposal_id', $proposal->id)->delete();
                            ProposalInstitution::where('proposal_id', $proposal->id)->delete();
                            ProposalReport::where('proposal_id', $proposal->id)->delete();
                            Recommendation::where('proposal_id', $proposal->id)->delete();
                            RefereeReport::where('proposal_id', $proposal->id)->delete();
                            $file_path = storage_path(proppath($proposal->id));
                            if (is_dir($file_path)) File::deleteDirectory($file_path);
                            Proposal::find($proposal->id)->delete();
                        }
                        Person::where('id', $person->id)->delete();
                        User::where('id', $p->user_id)->delete();
                    }
                    break;
                case 'viewer';
                    $person = Person::where('user_id', $p->user_id)->first();
                    $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
                case  'admin':
                    $person = Person::where('user_id', $p->user_id)->first();
                    $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
                case 'referee':
                    $person = Person::where('user_id', $p->user_id)->first();
                    RefereeReport::where('referee_id', $person->id)->delete();
                    $person->delete();
                    User::where('id', $p->user_id)->delete();
                    break;
            }

            return redirect()->back()->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('wrong', messageFromTemplate('wrong'));
        }
    }

    public function mailreferee($id)
    {
        $user = User::where('id', '=', $id)->first();
        $objSend = new \stdClass();
        $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at ansef.dopplerthepom.com.";
        $objSend->sender = config('emails.webmaster');
        $objSend->receiver = config('emails.webmaster');

        Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

        return redirect()->back()->with('status', 'Mail sent to referee');
    }

    public function mailviewer($id)
    {
        $user = User::where('id', '=', $id)->first();
        $objSend = new \stdClass();
        $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at ansef.dopplerthepom.com.";
        $objSend->sender = config('emails.webmaster');
        $objSend->receiver = config('emails.webmaster');

        Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

        return redirect()->back()->with('status', 'Mail sent to referee');
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
            $user = User::find((int) $id);
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

    public function __construct()
    {
        // $this->except('logout');

    }

}
