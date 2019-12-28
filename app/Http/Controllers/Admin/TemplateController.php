<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $templates = Template::all();
            return view('admin.template.index', compact('templates'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/template')->with('error', messageFromTemplate("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.template.create');
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
                'name' => 'required|max:255|min:3',
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {
                $templates = new Template;
                $templates->name = $request->name;
                $templates->text = $request->text;
                $templates->save();
                return redirect('admin/template')->with('success', messageFromTemplate("success"));
            } else return redirect()->back()->withErrors($val->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/template')->with('error', messageFromTemplate("wrong"));;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $template = Template::find($id);
            return view('admin.template.edit', compact('template', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/template')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $val = Validator::make($request->all(), [
                'name' => 'required|max:255|min:3',
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {
                $templates = Template::find($id);
                $templates->name = $request->name;
                $templates->text = htmlentities($request->text, ENT_QUOTES);
                $templates->save();
                return redirect('admin/template')->with('success', messageFromTemplate("update"));
            } else return redirect()->back()->withErrors($val->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }

    public function destroy($id)
    {
        try {
            $template = Template::find($id);
            $template->delete();
            return redirect('admin/template')->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/template')->with('wrong', messageFromTemplate('wrong'));
        }
    }
}
