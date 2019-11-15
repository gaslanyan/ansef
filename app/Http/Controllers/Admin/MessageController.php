<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $messages = Message::all();
//            dd($messages);
            return view('admin.message.index', compact('messages'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/message')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $val = Validator::make($request->all(), [
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {
                $messages = new Message();
                $messages->text = $request->text;
                $messages->save();
                return redirect('admin/message')->with('success', getMessage("success"));
            }
            else return redirect()->back()->withErrors($val->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/message')->with('error', getMessage("wrong"));
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
        //
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
            $message = Message::find($id);
            return view('admin.message.edit', compact('message', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/message')->with('error', getMessage("wrong"));
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
            $val = Validator::make($request->all(), [
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {

                $messages = Message::find($id);
                $messages->text = $request->text;
                $messages->save();
                return redirect('admin/message')->with('success', getMessage("update"));
            }
            else return redirect()->back()->withErrors($val->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/message')->with('error', getMessage("wrong"));
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
        try {
            $message = Message::find($id);
            $message->delete();
            return redirect('admin/message')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/message')->with('error', getMessage("wrong"));
        }
    }
}
