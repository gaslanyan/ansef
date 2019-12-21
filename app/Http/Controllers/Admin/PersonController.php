<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\DegreePerson;
use App\Models\Email;
use App\Models\Honor;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\Publication;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    public function edit()
    {
        try {
            $user_id = getUserId();
            $countries = Country::all()->sort();

            $person = Person::firstOrCreate(
                [
                    'user_id' => $user_id,
                    'type' => get_role_cookie()
                ],
                []
            );
            $person->save();

            $address = Address::firstOrCreate([
                'addressable_id' => $person->id,
                'addressable_type' => 'App\Models\Person'
            ], []);
            $address->save();

            $id = $person->id;

            return view('admin.person.create', compact('countries', 'person', 'address', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update(Request $request)
    {
        $user_id = getUserId();
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'country' => 'required|not_in:0'
        ]);
        try {
            $person = Person::where('user_id', '=', $user_id)->first();
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            $person->birthdate = $request->birthdate;
            $person->birthplace = $request->birthplace;
            $person->nationality = $request->nationality;
            $person->sex = $request->sex;
            $person->save();

            $address = Address::where('addressable_id', '=', $person->id)
                ->where('addressable_type', '=', 'App\Models\Person')
                ->first();
            $address = $person->addresses()->first();
            $address->street = $request->street;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->country_id = $request->country;
            $address->save();

            return \Redirect::back()->with('success', 'Data saved.');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', 'Could not save')->withInput();
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

                            // Find proposals of person
                            // if (!empty($arr)) {
                            //     foreach ($key as $index => $item) {
                            //         BudgetItem::where('proposal_id', $item)->delete();
                            //         ProposalInstitution::where('proposal_id', $item)->delete();
                            //         ProposalReports::where('proposal_id', $item)->delete();
                            //         $r = RefereeReport::select('id')->where('proposal_id', $item)->get();
                            //         foreach ($r as $ind => $it) {

                            //             Score::where('report_id', $it->id)->delete();
                            //         }
                            //         RefereeReport::where('proposal_id', $item)->delete();
                            //         $file_path = storage_path('proposal/prop-' . $item);
                            //         //
                            //         if (is_dir($file_path))
                            //             File::deleteDirectory($file_path);
                            //         Proposal::where('id', $item)->delete();
                            //     }
                            // }

                            DegreePerson::where('person_id', $persons->id)->delete();
                            Email::where('person_id', $persons->id)->delete();
                            Honor::where('person_id', $persons->id)->delete();
                            Address::where('id', $a->address_id)->delete();
                            Meeting::where('person_id', $persons->id)->delete();
                            InstitutionPerson::where('person_id', $persons->id)->delete();
                            Publication::where('person_id', $persons->id)->delete();
                            Recommendation::where('person_id', $persons->id)->delete();
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
                        $this->deleteUser('admin', $id);
                        break;
                    case 'referee':
                        $this->deleteUser('referee', $id);
                        break;
                }
            }


            return redirect()->back()->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/person')->with('wrong', messageFromTemplate('wrong'));
        }
    }

    public function deleteUser($type, $id)
    {
        $person = Person::select('id')->where('user_id', $id)->first();

        $members = Proposal::select('id', 'proposal_' . $type . 's')->get()->toArray();
        foreach ($members as $index => $member) {
            if (!empty($member)) {
                $json_member = json_decode($member['proposal_' . $type . 's']);
                $position = array_search((string) $person->id, (array) $json_member);
                $json_member = (array) $json_member;
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
            $user_id = getPersonIdByRole('admin');

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
                    return \Redirect::to('logout')->with('success', messageFromTemplate("success"));
                }
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate("wrong"));
        }
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
                $user = User::find((int) $id);
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
}
