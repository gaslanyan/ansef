<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proposals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'abstract', 'state', 'document', 'overall_score', 'user_id',
        'comment', 'rank', 'competition_id', 'categories', 'proposal_refeeres','proposal_admin'
         ];

    public function competition()
    {
        return $this->belongsTo(Competition::class , 'competition_id', 'id');
    }

    function report()
    {
        return $this->hasOne('App\Models\RefereeReport');
    }

    function proposalReport()
    {
        return $this->hasOne(ProposalReport::class);
    }

    function proposalReports()
    {
        return $this->hasMany(ProposalReport::class);
    }

    function budgetItems()
    {
        return $this->hasMany(BudgetItem::class);
    }

    function reports()
    {
        return $this->hasMany('App\Models\RefereeReport');
    }

    function propreports()
    {
        return $this->hasMany('App\Models\ProposalReport');
    }

    function persons()
    {
        return $this->belongsToMany(Person::class , 'proposal_persons', 'proposal_id', 'person_id')->withPivot('subtype');
    }

    function user() {
        return $this->belongsTo(User::class);
    }

    function institutions()
    {
        return $this->belongsToMany(Institution::class, 'proposal_institutions', 'proposal_id', 'institution_id');
    }

    function institution()
    {
        if(!empty($this->institutions()->first())) return $this->institutions()->first()->content;
        $propins = ProposalInstitution::where('proposal_id','=',$this->id)->first();
        return !empty($propins) ? $propins->institutionname : '';
    }

    function pi() {
        $p = ProposalPerson::where('proposal_id','=',$this->id)
            ->where('subtype','=','PI')
            ->first();
        return !empty($p) ? Person::find($p->person_id) : null;
    }

    function referees() {
        $reps = RefereeReport::where('proposal_id','=',$this->id)
            ->join('persons','persons.id','=', 'referee_reports.referee_id')
            ->get();
        $referees = [];
        foreach($reps as $r) {
            $referees.push(['id' => $r->referee_id, 'name' => $r->last_name]);
        }
        return $referees;
    }

    function refereesasstring()
    {
        $reps = RefereeReport::where('proposal_id', '=', $this->id)
            ->join('persons', 'persons.id', '=', 'referee_reports.referee_id')
            ->get();
        $referees = '';
        foreach ($reps as $r) {
            $referees .= (truncate($r->last_name, 6) . " ");
        }
        return $referees;
    }

    function admin() {
        return $this->belongsTo(Person::class, 'proposal_admin', 'id');
    }

    function budget()
    {
        $competition = $this->competition;
        $additional = json_decode($competition->additional);

        $sum = 0;
        $validation_message = "";
        $bi = $this->budgetItems()->get();
        foreach ($bi as $item) {
            if ($item->amount > $item->category->max) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too high; max is $" . $item->category->max . "<br/>");
            if ($item->amount < $item->category->min) $validation_message .= ("<b>Error:</b> Amount $" . $item->amount . " too low; min is $" . $item->category->min . "<br/>");
            $sum += $item->amount;
        }

        $additional_message = "";
        if ((int)$additional->additional_charge > 0) $additional_message .= (" + <b>" . $additional->additional_charge_name . ":</b> $" . $additional->additional_charge . "<br/>");
        if ((int)$additional->additional_percentage > 0) $additional_message .= (" + <b>" . $additional->additional_percentage_name . ":</b> " . $additional->additional_percentage . "% x $" . $sum . " = $" . round($sum * $additional->additional_percentage / 100.0) . "<br/>");

        $sum += (round($sum * $additional->additional_percentage / 100.0) + $additional->additional_charge);

        if ($competition->max_budget == $competition->min_budget) {
            if (($competition->max_budget - $sum) > 10 || $competition->max_budget < $sum) $validation_message .= ("Total budget amount $" . $sum . " must be lower than and within $10 of $" . $competition->max_budget . "<br/>");
        } else {
            if ($sum > $competition->max_budget) $validation_message .= ("Total budget amount $" . $sum . " is too high; max is $" . $competition->max_budget . "<br/>");
            if ($sum < $competition->min_budget) $validation_message .= ("Total budget amount $" . $sum . " is too low; min is $" . $competition->min_budget . "<br/>");
        }

        $additional_message .= ("<br/><b>Total budget:</b> $" . $sum . "<br/>");

        if ($validation_message != "") $validation_message .= ("<b>Your budget has errors:</b> please correct all errors.");

        return ["summary" => $additional_message, "validation" => $validation_message];
    }
}
