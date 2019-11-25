<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user_id = getUserIdByRole('admin');
            $emails = Email::where('person_id', $user_id)->get();
            return view('admin.email.index', compact('emails'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', getMessage("wrong"));

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.email.create');
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
            return view('admin.email.create');
        else {
            try {
                $pp_id = getUserIdByRole('admin');
                foreach ($request->email as $item) {
                    $email = new Email;
                    $email->person_id = $pp_id;
                    $email->email = $item;
                    $email->save();
                }

                return redirect('admin/email')->with('success', getMessage("success"));

            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/email')->with('error', getMessage("wrong"));

            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Email $email
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Email $email
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = Email::find($id);
        return view('admin.email.edit', compact('email', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Email $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->isMethod('post'))
            return view('admin.email.edit');
        else {
            try {
                $this->validate($request, [
                    'email' => 'required|string|email|max:255',
                    //|unique:users
                ]);

                $email = Email::find($id);
                $email->email = $request->email;
                $email->save();
                return redirect('admin/email')->with('success', getMessage("update"));

            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/email')->with('error', getMessage('wrong'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Email $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $email = Email::find($id);
            $email->delete();
            return redirect('admin/email')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', getMessage('wrong'));
        }
    }
}
