<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\MigrateANSEF;
use App\Jobs\MigrateBase;

class JobsController extends Controller
{
    public function migrate() {
        $this->dispatch(new MigrateBase());
        $proposals = DB::connection('mysqlold')->table('proposals')
            ->orderBy('date', 'asc')->get()->pluck('id')->toArray();
        $proposalchunks = array_chunk($proposals, 20);
        $id = -1;
        return view('admin.migrate', compact('proposalchunks','id'));
    }

    public function dochunk($id) {
        $proposals = DB::connection('mysqlold')->table('proposals')
            ->orderBy('date', 'asc')->get()->pluck('id')->toArray();
        $proposalchunks = array_chunk($proposals, 20);

        \Debugbar::error('Processing 20 items...');
        foreach ($proposalchunks[$id] as $proposal_id) {
            $this->dispatch(new MigrateANSEF($proposal_id));
        }

        return view('admin.migrate', compact('proposalchunks','id'));
    }
}
