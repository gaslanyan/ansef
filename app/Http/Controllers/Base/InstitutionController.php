<?php

namespace App\Http\Controllers\Base;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = \Auth::guard(get_Cookie())->user()->id;

        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        //$phones= [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            //$phones = Phone::where('person_id', $p_id)->get()->toArray();
        }

        $institutions = Institution::with('address')->get();
        $address = Country::with('address')->get();
        $cities = City::with('address')->get();
        return view('institution.index', compact('institutions', 'address', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
         $ins_array = [];
         $institutions_list = Institution::all()->toArray();
        //dd($institutions_list);
         $institution_person = InstitutionPerson::select('*')
             ->join('institutions', 'institutions.id','=','institutions_persons.institution_id')
             ->where('institutions_persons.person_id','=',$id)->get()->toArray();


        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $person = Person::where('id',$id )->get()->toArray();
        return view('applicant.institution.create', compact('id','institutions_list','person','ins_array','institution_person'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        try {
            foreach ($request->institution as $key => $val) {
                $institution = new InstitutionPerson();
                $institution->person_id = $request->institution_creare_hidden;
                $institution->institution_id = (int)$request->institution[$key];;
                $institution->title = $request->i_title[$key];
                $institution->type = $request->i_type[$key];
                $institution->start = $request->start[$key];
                $institution->end = $request->end[$key];
                $institution->save();
            }
            return Redirect::back()->with('success', getMessage("success"));
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', getMessage("wrong"))->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('applicant/person')->with('wrong', getMessage("wrong"))->withInput();
        }





       /* DB::beginTransaction();
        try {
            $country = Country::where('cc_fips', '=', $request->countries[0])->first();
            $address = new Address();
            $address->country_id = (int)$country->id;
            $address->city_id = (int)$request->city[0];
            $address->province = $request->provence[0];
            $address->street = $request->street[0];
            $address->save();
            $addr_id = $address->id;
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::to('/form')
                ->withErrors($e->getErrors())
                ->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return getMessage("wrong");
        }
        if (!empty($addr_id)) {
            $pa = new PersonAddress();
            $pa->person_id = $request->institution_creare_hidden;
            $pa->address_id = $addr_id;
            $pa->save();
        }


        try {
            $institutions = new Institution();
            $institutions->content = $request->name;
            $institutions->address_id = (int)$addr_id;
            $institutions->save();
        } catch (ValidationException $e) {
            DB::rollback();
            return Redirect::to('/form')
                ->withErrors($e->getErrors())
                ->withInput();

        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
//            throw $exception;
            return getMessage("wrong");
        }

        DB::commit();
        return redirect('admin/institution')->with('success', getMessage("success"));*/

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $institution = Institution::with('address')->find($id);
        $address = Country::with('address')->find($institution->address->country_id);
        $city = City::with('address')->find($institution->address->city_id);

        return view('institution.view', compact('institution', 'address', 'city'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $institution = Institution::with('address')->find($id);
        $address = Country::with('address')->find($institution->address->country_id);
        $city = City::with('address')->find($institution->address->city_id);
        $cities = City::where('cc_fips', '=', $address->cc_fips)->get()->toArray();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        return view('institution.edit', compact('institution', 'address', 'city', 'countries', 'cities', 'id'));
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
        dd($id);
        try {
            $this->validate($request, [
                'content' => 'required|min:3',
            ]);

            $institutions = Institution::find($id);

            foreach ($request->institution as $key => $val) {
                $institution = new InstitutionPerson();
                $institution->person_id = $request->institution_creare_hidden;
                $institution->institution_id = (int)$request->institution[$key];;
                $institution->title = $request->i_title[$key];
                $institution->type = $request->i_type[$key];
                $institution->start = $request->start[$key];
                $institution->end = $request->end[$key];
                $institution->save();
            }

//            $address = Address::find($institutions['address_id']);
////            $country = Country::where('cc_fips', '=', $request->countries[0])->first();
////
////            $address->country_id = $country->id;
////            $address->city_id = $request->city[0];
////            $address->province = $request->provence[0];
////            $address->street = $request->street[0];
////            $address->save();
////
////           // $institutions->content = $request->content;
////            $institutions->save();
            return redirect('admin/institution')->with('success', getMessage("update"));

        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
//            throw $exception;
            return redirect('admin/institution')->with('error', getMessage("wrong"))->withInput();

        }
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
            $template = Institution::find($id);
            $template->delete();
            return redirect('admin/institution')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
