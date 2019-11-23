<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
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
            $institutions = Institution::all();
            return view('admin.institution.index', compact('institutions'));
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
            $countries = Country::all()->sortBy('country_name')->keyBy('id');
            return view('admin.institution.create', compact('countries'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'country' => 'required|not_in:0',
            'city' => 'required|max:255'
        ]);
        if (!$v->fails()) {
            try {
                $address = new Address();
                $address->country_id = $request->country;
                $address->province = $request->provence;
                $address->street = $request->street;
                $address->city = $request->city;
                $address->save();
                $institution = new Institution();
                $institution->content = $request->name;
                $institution->save();
                $institution->addresses()->save($address);
            } catch (ValidationException $e) {
                DB::rollback();
                return Redirect::to('/form')->withErrors($e->getErrors())->withInput();
            } catch (\Exception $exception) {
                DB::rollBack();
                logger()->error($exception);
                return redirect('admin/institution')->with('error', getMessage("wrong"));
            }
            DB::commit();
            return redirect('admin/institution')->with('success', getMessage("success"));
        } else
            return redirect()->back()->withErrors($v->errors())->withInput();
        // }
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
            $institution = Institution::find($id);
            $countries = Country::all()->sortBy('country_name')->keyBy('id');

            return view('admin.institution.view', compact('institution', 'countries'));
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
        try {
            $institution = Institution::find($id);
            $address = $institution->addresses()->first();
            $countries = Country::all()->sortBy('country_name')->keyBy('id');

            return view('admin.institution.edit', compact('institution', 'address', 'countries', 'id'));
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
        try {
            $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'country' => 'required|not_in:0',
                'city' => 'required|max:255'
            ]);
            if (!$v->fails()) {
                $institution = Institution::find($id);
                $institution->content = $request->name;
                $institution->save();
                $address = $institution->addresses()->first();
                $address->country_id = $request->country;
                $address->city = $request->city;
                $address->street = $request->street;
                $address->province = $request->provence;
                $address->save();
                return redirect('admin/institution')->with('success', getMessage("update"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('admin/institution')->with('error', getMessage("wrong"));
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
            $ins = Institution::find($id);
            $addresses = $ins->addresses()->get();
            foreach ($addresses as $address) {
                $address->delete();
            }
            $ins->delete();
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
