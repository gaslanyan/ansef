<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        try {
            $institutions = Institution::with(['address'])->get();
            $cities = [];
            foreach ($institutions as $index => $institution) {

                if (!empty($institution->address)) {
                    $city = City::where('id', $institution->address->city_id)->first();
                    $cities[$city->id] = $city->name;
                }
            }
            $address = Country::with('address')->get();
            return view('admin.institution.index', compact('institutions', 'address', 'cities'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
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
            return view('admin.institution.create', compact('countries'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
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
            return view('admin.institution.create');
        else {
            DB::beginTransaction();

            $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'countries' => 'required|max:255',
                'city' => 'required|max:255',
                'provence' => 'required|max:255',
                'street' => 'required|max:255',
            ]);
            if (!$v->fails()) {
//            try {
                $country = Country::where('cc_fips', '=', $request->countries)->first();
                $address = new Address();
                if ((int)$request->city_id === -1) {
                    $city = new City();
                    $city->name = $request->city;
                    $city->cc_fips = $request->countries;
                    $city->save();
                    $city_id = $city->id;
                    $address->city_id = $city_id;
                } else
                    $address->city_id = (int)$request->city_id;
                $address->country_id = (int)$country->id;
//                $address->city_id = (int)$request->city[$key];
                $address->province = $request->provence;
                $address->street = $request->street;
                $address->save();
                $id = $address->id;
//            } catch
//            (ValidationException $e) {
//                // Rollback and then redirect
//                // back to form with errors
//                DB::rollback();
//                return Redirect::to('/form')
//                    ->withErrors($e->getErrors())
//                    ->withInput();
//            } catch (\Exception $exception) {
//                DB::rollBack();
//                logger()->error($exception);
//                return redirect('admin/institution')->with('error', getMessage("wrong"));
//            }
//            try {
                $institutions = new Institution();
                $institutions->content = $request->name;
                $institutions->address_id = (int)$id;
                $institutions->save();
//            } catch (ValidationException $e) {
//                DB::rollback();
//                return Redirect::to('/form')
//                    ->withErrors($e->getErrors())
//                    ->withInput();
//
//            } catch (\Exception $exception) {
//                DB::rollBack();
//                logger()->error($exception);
////            throw $exception;
//                return redirect('admin/institution')->with('error', getMessage("wrong"));
//            }

                DB::commit();
                return redirect('admin/institution')->with('success', getMessage("success"));
            } else
                return redirect()->back()->withErrors($v->errors());
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
        try {
            $institution = Institution::with('address')->find($id);
            $address = Country::with('address')->find($institution->address->country_id);
            $city = City::with('address')->find($institution->address->city_id);

            return view('admin.institution.view', compact('institution', 'address', 'city'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
        $institution = Institution::with('address')->find($id);
        $address = Country::with('address')->find($institution->address->country_id);
        $city = City::with('address')->find($institution->address->city_id);
        $cities = City::where('cc_fips', '=', $address->cc_fips)->get()->toArray();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
//        dd($address);
        return view('admin.institution.edit', compact('institution', 'address', 'city', 'countries', 'cities', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->isMethod('post'))
            return view('admin.institution.edit');
        else {
        try {
            $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'countries.*' => 'required|max:255',
                'city.*' => 'required|max:255',
                'provence.*' => 'required|max:255',
                'street.*' => 'required|max:255',
            ]);
            if (!$v->fails()) {

                $institutions = Institution::find($id);
                $address = Address::find($institutions['address_id']);
                $country = Country::where('cc_fips', '=', $request->countries[0])->first();
//                $address = new Address();
                $address->country_id = (int)$country->id;
                foreach ($request->countries as $key => $val) {
                    if ((int)$request->city_id[$key] === -1) {
                        $city = new City();
                        $city->name = $request->city[$key];
                        $city->cc_fips = $request->countries[$key];
                        $city->save();
                        $city_id = $city->id;
                        $address->city_id = $city_id;
                    } else
                        $address->city_id = (int)$request->city_id[$key];
                    $address->province = $request->provence[$key];
                    $address->street = $request->street[$key];
                    $address->save();
                    $address_id = $address->id;
                }
                if (!empty($request->name)) {
                    $institutions->content = $request->name;
                }
                $institutions->save();
                return redirect('admin/institution')->with('success', getMessage("update"));
            } else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
//            throw $exception;
            return redirect('admin/institution')->with('error', getMessage("wrong"));

        }
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
        DB::beginTransaction();
        try {
            $template = Institution::find($id);
            $address = Address::find($template->address_id);
            $template->delete();
            $address->delete();
            DB::commit();
            return redirect('admin/institution')->with('success', getMessage('deleted'));
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors($e->getErrors())
                ->withInput();


        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
