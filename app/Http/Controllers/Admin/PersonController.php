<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\BudgetItem;
use App\Models\Country;
use App\Models\DegreePerson;
use App\Models\DisciplinePerson;
use App\Models\Email;
use App\Models\Honors;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReports;
use App\Models\Publications;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\Role;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $persons = User::with('role')
                ->get();

            $roles = Role::all();

            return view('admin.person.index', compact('persons', 'roles'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', getMessage("wrong"));
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
            $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
            $institutions = Institution::all()->pluck('content', 'id')->toArray();
            return view('admin.person.create', compact('countries', 'institutions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!$request->isMethod('post'))
            return view('admin.person.create');
        else {
            $user_type = get_Cookie();
            $user = Auth::guard($user_type)->user();

            $v = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'birthdate' => 'required|date|date_format:Y-m-d',
                'nationality' => 'required|max:255',
                'sex' => 'required|max:15',
                'countries.*' => 'required|max:255',
                'provence.*' => 'required|max:255',
                'street.*' => 'required|max:255',
            ]);
            if (!$v->fails()) {

                DB::beginTransaction();
                try {
                    $person = new Person;
                    $person->first_name = $request->first_name;
                    $person->last_name = $request->last_name;
                    if (!empty($request->birthdate)) {
                        $time = strtotime($request->birthdate);
                        $newformat = date('Y-m-d', $time);
                        $person->birthdate = $newformat;
                    }

                    $person->birthplace = $request->birthplace;
                    $person->nationality = $request->nationality;
                    $person->sex = $request->sex;
//            $person->state = $request->state;
//            $person->type = $request->type;
                    $person->user_id = $user->id;
                    $person->save();
                    $person_id = $person->id;
                } catch (ValidationException $e) {
                    // Rollback and then redirect
                    // back to form with errors
                    DB::rollback();
                    return redirect()->back()
                        ->withErrors($e->getErrors())
                        ->withInput();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    logger()->error($exception);
                    return redirect()->back()->with('error', getMessage("wrong"));
                }

            } else
                return redirect()->back()->withErrors($v->errors());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $person = Person::where('user_id', '=', $id)->first();
            $fulladddress = [];
            $getaddress = \DB::table('person_address')
                ->select('cities.id as cid', 'address.province', 'address.street', 'countries.country_name', 'cities.name')
                ->join('address', 'address.id', '=', 'person_address.address_id')
                ->join('countries', 'countries.id', '=', 'address.country_id')
                ->where('person_address.person_id', '=', $person->id)
                ->get()->toArray();
            $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
            foreach ($getaddress as $address_item) {
                $adddress['country'] = $address_item->country_name;
                $adddress['street'] = $address_item->street;
                $adddress['province'] = $address_item->province;
                array_push($fulladddress, $adddress);
            }

            return view('admin.person.edit', compact('fulladddress', 'person', 'id', 'countries'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', getMessage("wrong"));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->isMethod('post'))
            return view('admin.person.edit');
        else {
            try {
                $v = Validator::make($request->all(), [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'birthdate' => 'required|date|date_format:Y-m-d',
                    'nationality' => 'required|max:255',
                    'sex' => 'required|max:15',
                    'countries.*' => 'required|max:255',
                    'provence.*' => 'required|max:255',
                    'street.*' => 'required|max:255',
                ]);
                if (!$v->fails()) {
                    $user = Person::where('user_id', '=', $id)->first();
                    $person = Person::find($user->id);
                    $person->first_name = $request->first_name;
                    $person->last_name = $request->last_name;
                    $person->birthdate = $request->birthdate;
                    $person->nationality = $request->nationality;
                    $person->sex = $request->sex;
                    $person->save();

                    return redirect('admin/person/' . $id . '/edit')->with('success', getMessage("success"));
                } else
                    return redirect()->back()->withErrors($v->errors());
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/person/' . $id . '/edit')->with('error', getMessage("wrong"));
            }
        }
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $type)
    {
        try {
            $p = Person::where('user_id', $id)->get();

            if ($p->isEmpty()) {
                User::where('id', $id)->delete();

            } else {
                $arr = [];
                switch ($type) {
                    case 'applicant':
                        foreach ($p as $index => $person) {
                            $persons = Person::where('id', $person->id)->first();

                            $pm = Proposal::select('id', 'proposal_members')->get();

                            $proposals = [];
                            foreach ($pm as $item) {
                                $js = json_decode($item->proposal_members, true);

                                if (!empty($js['account_id']))
                                    $arr[$item['id']] = $js['account_id'];

                            }

                            $key = array_keys($arr, $person->user_id);

                            if (!empty($arr)) {
                                foreach ($key as $index => $item) {
                                    BudgetItem::where('proposal_id', $item)->delete();
                                    ProposalInstitution::where('proposal_id', $item)->delete();
                                    ProposalReports::where('proposal_id', $item)->delete();
                                    $r = RefereeReport::select('id')->where('proposal_id', $item)->get();
                                    foreach ($r as $ind => $it) {

                                        Score::where('report_id', $it->id)->delete();
                                    }
                                    RefereeReport::where('proposal_id', $item)->delete();
                                    $file_path = storage_path('proposal/prop-' . $item);
//
                                    if (is_dir($file_path))
                                        File::deleteDirectory($file_path);
                                    Proposal::where('id', $item)->delete();
                                }
                            }

                            Person_groups::where('group_person_id', $persons->id)->delete();
                            DegreePerson::where('person_id', $persons->id)->delete();
                            DisciplinePerson::where('person_id', $persons->id)->delete();
                            Email::where('person_id', $persons->id)->delete();
                            Honors::where('person_id', $persons->id)->delete();
                            Address::where('id', $a->address_id)->delete();
                            Meeting::where('person_id', $persons->id)->delete();
                            InstitutionPerson::where('person_id', $persons->id)->delete();
                            Publications::where('person_id', $persons->id)->delete();
                            Recommendations::where('person_id', $persons->id)->delete();
                            Person::where('id', $person->id)->delete();
                            User::where('id', $id)->delete();
                        }
                        break;
                    case 'viewer';
                        $person = Person::where('user_id', $id)->first();
                        $person->delete();
                        User::where('id', $id)->delete();
                        break;
                    case  'admin':
                        $this->deletUser('admin', $id);
                        break;
                    case 'referee':
                        $this->deletUser('referee', $id);
                        break;
                }
            }


            return redirect()->back()->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('wrong', getMessage('wrong'));
        }
    }

    public function deletUser($type, $id)
    {
        $person = Person::select('id')->
        where('user_id', $id)->
        first();

        $members = Proposal::select('id', 'proposal_' . $type . 's')->get()->toArray();
        foreach ($members as $index => $member) {
            if (!empty($member)) {
                $json_member = json_decode($member['proposal_' . $type . 's']);
                $position = array_search((string)$person->id, (array)$json_member);
                $json_member = (array)$json_member;
                if ($position !== false) {
                    unset($json_member[$position]);
                    if (count($json_member) > 0)
                        Proposal::where('id', $member['id'])
                            ->update(['proposal_' . $type . 's' => json_encode($json_member, JSON_FORCE_OBJECT)]);
                    else
                        Proposal::where('id', $member['id'])
                            ->update(['proposal_' . $type . 's' => '']);
                    if ($type === "referee")
                        RefereeReport::where('referee_id', $person->id)->delete();

                    Person::where('id', $person->id)->delete();
                    User::where('id', $id)->delete();

                }
            }
        }

    }

    public function changePassword()
    {

        return view('admin.person.changepassword');
    }

    public function updatePassword(Request $request)
    {
        try {
            $user_id = getUserId('admin');

            $v = Validator::make($request->all(), [
                'oldpassword' => 'required',
                'newpassword' => 'required|min:8',
                'confirmpassword' => 'required|same:newpassword',
            ]);
            if (!$v->fails()) {
                $user = Person::with('user')->find($user_id);

                if (!Hash::check($request->oldpassword, $user->user->password)) {
                    return back()
                        ->with('error', 'The specified password does not match the database password');
                } else {

                    $user->user->password = bcrypt($request->newpassword);
                    $user->user->save();
                    return \Redirect::to('logout')->with('success', getMessage("success"));
                }
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', getMessage("wrong"));
        }
    }
//    public function __construct()
//    {
//
//        $this->middleware('sign_in')->except('logout');
//
//        dd(  $this->middleware('guest:admin'));
////        $this->middleware('guest')->except('logout');
////        $this->middleware('guest:applicant')->except('logout');
////        $this->middleware('guest:admin')->except('logout');
////        $this->middleware('guest:viewer')->except('logout');
////        $this->middleware('guest:referee')->except('logout');
//
//    }
}
