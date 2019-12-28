<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;

class AdminController extends Controller
{
    protected $guard = 'admin';
    public function index()
    {
        ini_set('memory_limit', '512M');
        try {
            $proposals = Competition::with('proposalsCount')->get()->sortByDesc('created_at');

            return view('admin.dashboard', compact('proposals'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/index')->with('error', messageFromTemplate("wrong"));
        }
    }


    public function __construct()
    {
    }
}
