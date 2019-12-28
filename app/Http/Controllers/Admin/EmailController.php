<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index()
    {
        try {
            $user_id = getPersonIdByRole('admin');
            $emails = Email::where('person_id', $user_id)->get();
            return view('admin.email.index', compact('emails'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/email')->with('error', messageFromTemplate("wrong"));
        }
    }


    public function create()
    {
        return view('admin.email.create');
    }

    public function store(Request $request)
    {
        if (!$request->isMethod('post'))
            return view('admin.email.create');
        else {
            try {
                $pp_id = getPersonIdByRole('admin');
                foreach ($request->email as $item) {
                    $email = new Email;
                    $email->person_id = $pp_id;
                    $email->email = $item;
                    $email->user_id = getUserID();
                    $email->save();
                }

                return redirect('admin/email')->with('success', messageFromTemplate("success"));
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/email')->with('error', messageFromTemplate("wrong"));
            }
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $email = Email::find($id);
        return view('admin.email.edit', compact('email', 'id'));
    }

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
                return redirect('admin/email')->with('success', messageFromTemplate("update"));
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/email')->with('error', messageFromTemplate('wrong'));
            }
        }
    }

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
