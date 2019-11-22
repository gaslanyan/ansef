<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class BudgetCategoriesController extends Controller
{
    public function index()
    {
        return view('applicant.budgetcategories.create');
    }


    public function create(Request $request, $id)
    {
        $p = Proposal::find($id);

        if(!empty($p)) {
        $bc = $p->competition()->first()->budgetcategories()->get();
        $bi = $p->budgetItems()->get();

        return view('applicant.budgetcategories.create', compact('bc', 'bi', 'id'));
        }
        else {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bc' => 'required|not_in:0',
            'b_description' => 'required|min:5',
            // 'amount' => 'required|not_in:choosecompetition',
        ]);


         if (!empty($request->bc)) {
                // $bc_val = explode('**',$val);

                $budget_item = new BudgetItem();
                $budget_item->budget_cat_id = $request->bc;
                $budget_item->description = $request->b_description;
                $budget_item->amount = $request->amount;
                $budget_item->proposal_id = $request->prop_id;
                $budget_item->save();
        }
        return Redirect::back()->with('success', getMessage("success"));
        }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'bc_list.*' => 'required|not_in:0',
                'description_list.*' => 'required|max:255',
                'amount_list.*' => 'required',
            ]);
            for ($i = 0; $i <= count($request->bi_list_hidden) - 1; $i++) {
                $bi = BudgetItem::find($request->bi_list_hidden[$i]);
                $bi->budget_cat_id = $request->bc_list[$i];
                $bi->description = $request->description_list[$i];
                $bi->amount = $request->amount_list[$i];
                $bi->save();
            }
            return Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $bi = BudgetItem::find($id);
            $bi->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }
}
