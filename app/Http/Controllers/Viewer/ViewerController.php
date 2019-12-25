<?php

namespace App\Http\Controllers\Viewer;

use App\Models\Competition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewerController extends Controller
{
    protected $guard = 'viewer';
    public function index()
    {
        ini_set('memory_limit', '512M');
        try {
            $proposals = Competition::with('proposalsCount')->get();

            return view('viewer.dashboard', compact('proposals'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('viewer/index')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
