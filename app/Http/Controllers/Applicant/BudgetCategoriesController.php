<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class BudgetCategoriesController extends Controller
{
    public function index()
    {
        $user_id = getUserID();
        return view('applicant.budgetcategories.create');
    }


    public function create(Request $request, $id)
    {
        $user_id = getUserID();
        $p = Proposal::find($id);

        if(!empty($p) && $p->user_id == $user_id) {
            $competition = $p->competition()->first();
            $bc = $competition->budgetcategories()->get();
            $bi = $p->budgetItems()->get();
            $additional = json_decode($competition->additional);

            $sum = 0;
            foreach($bi as $item) {
                if ($item->amount > $item->category->max) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too high; max is $" . $item->category->max . "<br/>");
                if ($item->amount < $item->category->min) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too low; min is $" . $item->category->min . "<br/>");
                $sum += $item->amount;
            }

            $sum += (round($sum * $additional->additional_percentage / 100.0) + $additional->additional_charge);

            $budget = $p->budget();

            $additional_message = $budget["summary"];
            $validation_message = $budget["validation"];

            return view('applicant.budgetcategories.create', compact('bc', 'bi', 'id', 'sum', 'validation_message', 'additional_message'));
        }
        else {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }


    public function store(Request $request)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'bc' => 'required|not_in:0',
            'description' => 'required|min:5',
            'amount' => 'required|greater_than_field:minamount|less_than_field:maxamount',
        ]);


         if (!empty($request->bc)) {
                $budget_item = new BudgetItem();
                $budget_item->budget_cat_id = $request->bc;
                $budget_item->description = $request->description;
                $budget_item->amount = $request->amount;
                $budget_item->proposal_id = $request->prop_id;
                $budget_item->user_id = $user_id;
                $budget_item->save();
        }
        return Redirect::back()->with('success', messageFromTemplate("success"));
        }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        try {
            $this->validate($request, [
                'bc_list.*' => 'required|not_in:0',
                'description_list.*' => 'required|max:255',
                // 'amount_list.*' => 'required|greater_than_field:minamount_list.*|less_than_field:maxamount_list.*',
            ]);
            for ($i = 0; $i <= count($request->bi_list_hidden) - 1; $i++) {
                $bi = BudgetItem::find($request->bi_list_hidden[$i]);
                if ($bi->user_id != $user_id) continue;
                $bi->budget_cat_id = $request->bc_list[$i];
                $bi->description = $request->description_list[$i];
                $bi->amount = $request->amount_list[$i];
                $bi->save();
            }
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }



    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $bi = BudgetItem::where('id','=',$id)
                            ->where('user_id','=',$user_id)
                            ->first();
            $bi->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
