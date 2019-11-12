<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
            return redirect('settings/export')->with('error', getMessage("wrong"));
        }
    }

    public function export(Request $request)
    {
//        try {
            $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'type'=> 'required|max:15'
            ]);
            if (!$v->fails()) {
                exportExcelOrCsv($request->name, $request->type);
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('settings/export')->with('error', getMessage("wrong"));
//        }
    }

    public function sql()
    {
        try {
            return view('admin.settings.sql');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('settings/sql')->with('error', getMessage("wrong"));
        }
    }

    public function backup(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'sql' => 'required|max:1024',
            ]);
            if (!$v->fails()) {

                $data = DB::select($request->sql);
                $new_data = [];

                if (!empty($data)) {
                    foreach ($data as $index => $item) {
                        foreach ($item as $key => $value) {
                            $isJSON = isJSON($value);
                            if (!$isJSON) {
                                $new_data[$index][strtoupper(str_replace('_', ' ', $key))] = $value;
                            } else {
                                $new_data[$index][strtoupper(str_replace('_', ' ', $key))] = getContentByJSON($value);
                            }

                        }
                    }
                }

                return Excel::create('backup by query', function ($excel) use ($new_data) {
                    $excel->sheet('backup by query', function ($sheet) use ($new_data) {
                        $sheet->fromArray($new_data);
                    });
                })->download('xlsx');
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('settings/sql')->with('error', getMessage("wrong"));
        }
    }
}
