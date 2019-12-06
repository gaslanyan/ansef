<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Book;
use App\Models\Country;
use App\Models\DegreePerson;
use App\Models\DisciplinePerson;
use App\Models\Email;
use App\Models\Honors;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Phone;
use App\Models\Role;
use App\Models\User;
use App\Notifications\UserRegisteredSuccessfully;
use App\Notifications\GeneratePasswordSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function account($type)
    {
        try {
            $get_role = Role::where('name', '=', $type)->first();
            $users = User::select('id')
                ->where('role_id', '=', $get_role->id)
                ->get()->toArray();
            $persons = [];
            $ip = [];
            foreach ($users as $index => $user) {
                $person = Person::where('user_id', '=', $user['id'])->first();
                if (!empty($person)) {
                    $persons[$user['id']] = Person::with('user')
                        ->select('persons.*')
                        ->join('users', 'users.id', '=', 'persons.user_id')
                        ->where('users.role_id', '=', $get_role->id)
                        ->where('persons.user_id', '=', $user['id'])
                        ->where(function ($query) use ($type) {
                            if ($type == 'applicant')
                                $query->where('persons.type', 'participant')
                                    ->orWhere('persons.type', 'support');
                        })
                        ->get()->toArray();

                } else {
                    $persons[$user['id']] = User::where('id', $user['id'])->get()->toArray();
                }


                foreach ($persons as $p) {
                    if (!empty($p)) {

                        if (!empty($p[0]['id']))
                            $ip[$p[0]['id']] = InstitutionPerson::with('iperson')
                                ->join('institutions', 'institutions.id', '=', 'institutions_persons.institution_id')
                                ->where('institutions_persons.person_id', '=', $p[0]['id'])
                                ->first();
                    }
                }
            }

            $institutions = Institution::all();
            return view('admin.account.list', compact('persons', 'type', 'ip', 'institutions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/account')->with('error', getMessage('wrong'));
        }
    }

    /**
     * Generating a new password for account.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePassword(Request $request, $id)
    {
        try {
            $generate_password = randomPassword();
            $user = User::find($id);
            $user->password = bcrypt($generate_password);
            if ($user->save()) {
                $user->notify(new GeneratePasswordSend($user, $generate_password));
                $request->session()->flash('success', getMessage('generated_password') . " " . $user->email);
            } else
                $request->session()->flash('error', getMessage('wrong'));
            return redirect()->back();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', getMessage('check_email'));
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
            return redirect()->back()->with('error', getMessage('wrong'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->isMethod('post'))
            return view('admin.account.create');
        else {
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
                    $user = app(User::class)->create($validatedData);
                    $user->notify(new UserRegisteredSuccessfully($user));
                    return redirect()->action('PersonController@index');
                } else return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect()->back()->with('error', getMessage('wrong'));
            }
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
        $disciplines = DisciplinePerson::with('Discipline')->where('person_id', $person_id)->get()->toArray();

        $degrees = DegreePerson::select('degree_id', 'year')->with('degree')->where('person_id', $person_id)->get()->toArray();

        $honors = Honors::select('description', 'year')->where('person_id', $person_id)->get()->toArray();
        $meetings = Meeting::select('description', 'year', 'ansef_supported', 'domestic')->where('person_id', $person_id)->get()->toArray();

        return view('admin.account.show', compact('person',
            'phones', 'emails', 'address_array', 'books', 'degrees', 'honors', 'meetings', 'disciplines', 'institution'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect()->back()->with('error', getMessage('wrong'));
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
            return redirect()->back()->with('error', getMessage('wrong'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mailreferee($id)
    {
        $user = User::where('id', '=', $id)->first();
        $objSend = new \stdClass();
        $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at ansef.dopplerthepom.com.";
        $objSend->sender = 'dopplerthepom@gmail.com';
        $objSend->receiver = 'dopplerthepom@gmail.com';

        Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

        return redirect()->back()->with('status', 'Mail sent to referee');
    }

    public function mailviewer($id)
    {
        $user = User::where('id', '=', $id)->first();
        $objSend = new \stdClass();
        $objSend->message = "Your ANSEF portal account with email " . $user->email . " has been approved by the portal administrator. You may now log into the ANSEF portal at ansef.dopplerthepom.com.";
        $objSend->sender = 'dopplerthepom@gmail.com';
        $objSend->receiver = 'dopplerthepom@gmail.com';

        Mail::to($user->email)->send(new \App\Mail\SendToUser($objSend));

        return redirect()->back()->with('status', 'Mail sent to referee');
    }

    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('check-role:superadmin');
        $this->middleware('sign_in')->except('logout');

    }

}
