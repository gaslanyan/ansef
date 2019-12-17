<?php

namespace App\Http\Controllers\Base;

use App\Models\Email;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = Email::all();
        return view('base.email.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('base.email.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            $person = Person::where('user_id', '=', $user->id)->first();

            foreach ($request->email as $item) {
                $email = new Email;
                $email->person_id = $person->id;
                $email->email = $item;
                $email->save();
            }

            return redirect('admin/email')->with('success', messageFromTemplate("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', messageFromTemplate("wrong"));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        echo $id;
        $email = Email::find($id);
        return view('base.email.edit', compact('email', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'email' => 'required|string|email|max:255',
                //|unique:users
            ]);

            $email = Email::find($id);
            $email->email = $request->email;
            $email->save();
            return redirect('admin/email')->with('success', messageFromTemplate("update"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', messageFromTemplate('wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $email = Email::find($id);
            $email->delete();
            return redirect('admin/email')->with('success', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', messageFromTemplate('wrong'));
        }
    }
}
