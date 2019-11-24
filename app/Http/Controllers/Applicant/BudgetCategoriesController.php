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
            $competition = $p->competition()->first();
            $bc = $competition->budgetcategories()->get();
            $bi = $p->budgetItems()->get();
            $additional = json_decode($competition->additional);

            $sum = 0;
            $validation_message = "";
            foreach($bi as $item) {
                if ($item->amount > $item->category->max) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too high; max is $" . $item->category->max . "<br/>");
                if ($item->amount < $item->category->min) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too low; min is $" . $item->category->min . "<br/>");
                $sum += $item->amount;
            }

            $additional_message = "";
            if ($additional->additional_charge > 0) $additional_message .= (" + <b>" . $additional->additional_charge_name . ":</b> $" . $additional->additional_charge . "<br/>");
            if ($additional->additional_percentage > 0) $additional_message .= (" + <b>" . $additional->additional_percentage_name . ":</b> " . $additional->additional_percentage . "% x $" . $sum . " = $" . round($sum * $additional->additional_percentage / 100.0) . "<br/>");

            $sum += (round($sum * $additional->additional_percentage / 100.0) + $additional->additional_charge);

            if($competition->max_budget == $competition->min_budget) {
                if(($competition->max_budget - $sum) < 10 || $competition->max_budget < $sum) $validation_message .= ("<b>Error:</b> Total budget amount $" . $sum . " must be lower than and within $10 of $" . $competition->max_budget . "<br/>");
            }
            else {
                if ($sum > $competition->max_budget) $validation_message .= ("<b>Error:</b> Total budget amount $" . $sum . " is too high; max is $" . $competition->max_budget . "<br/>");
                if ($sum < $competition->min_budget) $validation_message .= ("<b>Error:</b> Total budget amount $" . $sum . " is too low; min is $" . $competition->min_budget . "<br/>");
            }

            $additional_message .= ("<br/><b>Total budget:</b> $" . $sum . "<br/>");

            if($validation_message != "") $validation_message .= ("<br/> <b>Your budget has errors:</b> please correct all errors.<br/>");

            return view('applicant.budgetcategories.create', compact('bc', 'bi', 'id', 'sum', 'validation_message', 'additional_message'));
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
            'amount' => 'required|greater_than_field:minamount|less_than_field:maxamount',
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
                // 'amount_list.*' => 'required|greater_than_field:minamount_list.*|less_than_field:maxamount_list.*',
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
