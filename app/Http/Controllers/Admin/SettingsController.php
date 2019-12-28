<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function lock(Request $request)
    {
        if ($request->_token === Session::token()) {
            if (!empty($request->lock)) {
                $lock['lock'] = $request->lock;

                Storage::disk('local')->put('lock.json', json_encode($lock));
            }
        }
        return $request->lock;
    }

    public function exportForm()
    {
        try {
            return view('admin.settings.export');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('settings/export')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function export(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'type' => 'required|max:15'
        ]);
        if (!$v->fails()) {
            exportExcelOrCsv($request->name, $request->type);
        } else
            return redirect()->back()->withErrors($v->errors())->withInput();
    }

    public function sql()
    {
        try {
            return view('admin.settings.sql');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('settings/sql')->with('error', messageFromTemplate("wrong"));
        }
    }
}
