<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Models\Address;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Degree;
use App\Models\DegreePerson;
use App\Models\Email;
use App\Models\Honors;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Models\PersonType;
use App\Models\Phone;
use App\Models\Proposal;
use App\Models\ProposalReports;
use App\Models\Publications;
use App\Models\RefereeReport;
use App\Models\Role;
use App\Models\Score;
use App\Models\ScoreType;
use App\Models\User;

class MigrateANSEF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $proposal_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($proposal_id)
    {
        $this->proposal_id = $proposal_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $proposal = DB::connection('mysqlold')->table('proposals')
            ->where('id', '=', $this->proposal_id)->first();

        $mindegree = Degree::where('text', '=', 'High school')->first();
        $nodegree = Degree::where('text', '=', 'None')->first();
        $maxdegree = Degree::where('text', '=', 'Doctoral')->first();

        // Read roles
        $applicant_role = Role::where('name', '=', 'applicant')->first();
        $admin_role = Role::where('name', '=', 'admin')->first();
        $referee_role = Role::where('name', '=', 'referee')->first();

        $accounts = DB::connection('mysqlold')->table('accounts')
            ->get()->keyBy('id');

        $expense_types = DB::connection('mysqlold')->table('expense_types')
            ->get()->keyBy('id');
        $collaboration_types = DB::connection('mysqlold')->table('collaboration_types')
            ->get()->keyBy('id');

        $administrators = DB::connection('mysqlold')->table('administrators')
            ->get()->keyBy('id');
        $referees = DB::connection('mysqlold')->table('referees')
            ->get()->keyBy('id');
        $investigators = DB::connection('mysqlold')->table('investigators')
            ->get()->keyBy('id');

        $proposals = DB::connection('mysqlold')->table('proposals')
            ->get()->keyBy('id');


        // Add competition
        // \Debugbar::error('Processing proposal id ' . $this->proposal_id);
        $propyear = date('Y', strtotime($proposal->date));
        $compyear = $propyear + 1;

        $additional = [];
        $additional['additional_charge_name'] = '';
        $additional['additional_charge'] = 0;
        $additional['additional_percentage_name'] = 'Percentage overhead';
        $additional['additional_percentage'] = 5;

        $maincategories = Category::where('parent_id', '=', null);
        $compcategories = '[';
        foreach ($maincategories as $mc) {
            $compcategories .= ('"' . $mc->abbreviation . '",');
        }
        $compcategories = substr($compcategories, 0, -1) . "]";
        $competition = Competition::firstOrCreate(
            ['title' => substr(strval($compyear), -2) . 'AN'],
            [
                'description' => $compyear . ' traditional ANSEF competition',
                'submission_start_date' => $propyear . '-06-01',
                'submission_end_date' => $propyear . '-09-01',
                'announcement_date' => $propyear . '-05-20',
                'project_start_date' => $compyear . '-01-01',
                'duration' => 12,
                'min_budget' => 5000,
                'max_budget' => 5000,
                'min_level_deg_id' => $mindegree->id,
                'max_level_deg_id' => $nodegree->id,
                'min_age' => 19,
                'max_age' => 100,
                'allow_foreign' => '1',
                'first_report' => $compyear . '-06-01',
                'second_report' => $compyear . '-11-01',
                'state' => 'enable',
                'recommendations' => 0,
                'categories' => $compcategories,
                'additional' => json_encode($additional),
                'instructions' => 'Traditional ANSEF competition'
            ]
        );
        // \Debugbar::error('Added competition.');
        // Add score types
        $scoretype = [];
        $scoretype['Significance'] = ScoreType::firstOrCreate(['name' => 'Significance'], [
            'description' => 'Does this study address an important problem?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['Approach'] = ScoreType::firstOrCreate(['name' => 'Approach'], [
            'name' => 'Approach',
            'description' => 'Are the concepts and design of methods and analysis adequately developed and appropriate to the aim of the project?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['Innovation'] = ScoreType::firstOrCreate(['name' => 'Innovation'], [
            'name' => 'Innovation',
            'description' => 'Does the project employ novel concepts, approaches or methods? Are the aims original and innovative? Does the project challenge existing paradigms or develop new methodologies or technologies?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['Investigator'] = ScoreType::firstOrCreate(['name' => 'Investigator'], [
            'description' => 'Is the investigator appropriately trained and well-suited to carry out this work? Is the work proposed appropriate to the experience level of the principal investigator and other researchers?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['Budget'] = ScoreType::firstOrCreate(['name' => 'Budget'], [
            'description' => 'Is the budget appropriate for the proposed project?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['Proposal'] = ScoreType::firstOrCreate(['name' => 'Proposal'], [
            'description' => 'How well conceived and organized is the proposed activity? Is the review of the current state of knowledge in the field adequate?',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        $scoretype['OverallScore'] = ScoreType::firstOrCreate(['name' => 'Overall Score'], [
            'description' => 'How would you rate the proposal overall? Please note that ANSEF grants are very competitive. It is rare that a proposal that is not deemed Outstanding in this category would get funded. On the other hand, there should be good reason to consider a proposal Outstanding, based on your assessment of the previous six criteria.',
            'min' => 0,
            'max' => 7,
            'weight' => 1,
            'competition_id' => $competition->id
        ]);
        // \Debugbar::error('Added score types.');

        // Add budget categories
        foreach ($expense_types as $expense_type) {
            BudgetCategory::firstOrCreate(['name' => $expense_type->label], [
                'min' => 0,
                'max' => 5000,
                'weight' => 1,
                'competition_id' => $competition->id,
                'comments' => 'Amount in dollars'
            ]);
        }
        // \Debugbar::error('Added budget categories.');

        // Add user and person for pi

        $investigator = null;
        $account = null;
        if (Arr::exists($investigators, $proposal->investigator_id)) {
            $investigator = $investigators[$proposal->investigator_id];
            if (Arr::exists($accounts, $investigator->account_id))
                $account = $accounts[$investigator->account_id];
        }
        $generate_password = randomPassword();
        if($account != null)
        $user = User::firstOrCreate(
            [
                'email' => $account->username
            ],
            [
                'password' => bcrypt($generate_password),
                'password_salt' => 10,
                'remember_token' => null,
                'role_id' => $applicant_role->id,
                'requested_role_id' => 0,
                'confirmation' => "1",
                'state' => 'active'
            ]
        );
        else $user = User::firstOrCreate(
            [
                'email' => 'applicant@ansef.org'
            ],
            [
                'password' => bcrypt($generate_password),
                'password_salt' => 10,
                'remember_token' => null,
                'role_id' => $applicant_role->id,
                'requested_role_id' => 0,
                'confirmation' => "1",
                'state' => 'active'
            ]
        );

        if($investigator != null)
        Person::firstOrCreate(
            [
                'user_id' => $user->id,
                'type' => null
            ],
            [
                'birthdate' => !empty($investigator->birthdate) ? date($investigator->birthdate) : null,
                'birthplace' => ucfirst($investigator->birthplace),
                'sex' => 'neutral',
                'state' => 'domestic',
                'first_name' => getCleanString($investigator->first_name),
                'last_name' => getCleanString($investigator->last_name),
                'nationality' => ucfirst($investigator->nationality),
                'type' => null,
                'specialization' => ($investigator->primary_specialization . ", " . $investigator->secondary_specialization),
                'user_id' => $user->id
            ]
        );
        else Person::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => null
                ],
                [
                    'birthdate' => date('1970-07-02'),
                    'birthplace' => '',
                    'sex' => 'neutral',
                    'state' => 'domestic',
                    'first_name' => '',
                    'last_name' => '',
                    'nationality' => 'Armenia',
                    'type' => null,
                    'specialization' => 'None',
                    'user_id' => $user->id
                ]
            );

        if ($investigator != null)
        $pi = Person::create([
            'birthdate' => !empty($investigator->birthdate) ? date($investigator->birthdate) : null,
            'birthplace' => ucfirst($investigator->birthplace),
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => getCleanString($investigator->first_name),
            'last_name' => getCleanString($investigator->last_name),
            'nationality' => ucfirst($investigator->nationality),
            'type' => 'participant',
            'specialization' => ($investigator->primary_specialization . ", " . $investigator->secondary_specialization),
            'user_id' => $user->id
        ]);
        else $pi = Person::create([
            'birthdate' => date('1970-07-02'),
            'birthplace' => 'Yerevan',
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => 'Applicant',
            'last_name' => 'Applicantian',
            'nationality' => 'Armenia',
            'type' => 'participant',
            'specialization' => 'None',
            'user_id' => $user->id
        ]);
        // \Debugbar::error('Added pi user and person.');

        if ($investigator != null)
        Phone::create([
            "person_id" => $pi->id,
            "country_code" => 0,
            "number" => $investigator->phone
        ]);

        if ($investigator != null)
        Email::create([
            "person_id" => $pi->id,
            "email" => $investigator->email
        ]);
        else Email::create([
            "person_id" => $pi->id,
            "email" => 'applicant@ansef.org'
        ]);

        if ($investigator != null)
        Address::create([
            'country_id' => 8,
            'province' => '',
            'street' =>  $investigator->address,
            'addressable_id' => $pi->id,
            'addressable_type' => 'App\Models\Person',
            'city' => ''
        ]);
        // \Debugbar::error('Added pi phone, email, and address.');

        // Add pi CV data
        if ($investigator != null) {
        $honors = DB::connection('mysqlold')->table('honors')
            ->where('investigator_id', '=', $investigator->id)->get();
        $grants = DB::connection('mysqlold')->table('grants')
            ->where('investigator_id', '=', $investigator->id)->get();
        $employments = DB::connection('mysqlold')->table('employments')
            ->where('investigator_id', '=', $investigator->id)->get();
        $publications = DB::connection('mysqlold')->table('publications')
            ->where('investigator_id', '=', $investigator->id)->get();
        $degrees = DB::connection('mysqlold')->table('degrees')
            ->where('investigator_id', '=', $investigator->id)->get();
        $ansefpublications = DB::connection('mysqlold')->table('ansefpublications')
            ->where('investigator_id', '=', $investigator->id)->get();

        foreach ($honors as $honor) {
            Honors::create([
                'description' => getCleanString($honor->hon_title),
                'year' => strval($honor->hon_year),
                'person_id' => $pi->id
            ]);
        }
        // \Debugbar::error('Added pi honors.');
        foreach ($grants as $grant) {
            Honors::create([
                'description' => $grant->grant_title . ", " . $grant->grant_type,
                'year' => $grant->grant_year,
                'person_id' => $pi->id
            ]);
        }
        // \Debugbar::error('Added pi grants.');
        foreach ($employments as $employment) {
            InstitutionPerson::create([
                'person_id' => $pi->id,
                'institution_id' => 0,
                'institution' => ' ',
                'title' => ($employment->employment_position),
                'start' => date($employment->employment_start_year . '-07-01'),
                'end' => !empty($employment->employment_end_year) ? date($employment->employment_end_year . '-07-01') : null,
                'type' => 'employment'
            ]);
        }
        // \Debugbar::error('Added pi employments.');
        foreach ($degrees as $degree) {
            DegreePerson::create([
                'person_id' => $pi->id,
                'degree_id' => $maxdegree->id,
                'year' => $degree->degree_year,
                'institution_id' => 0,
                'institution' => $degree->degree_institution
            ]);
        }
        // \Debugbar::error('Added pi degrees.');
        foreach ($publications as $publication) {
            Publications::create([
                'person_id' => $pi->id,
                'journal' => $publication->publication_reference,
                'title' => getCleanString($publication->publication_title),
                'year' => $publication->publication_year,
                'domestic' => '0',
                'ansef_supported' => strval($publication->publication_ansef)
            ]);
        }
        // \Debugbar::error('Added pi publications.');
        foreach ($ansefpublications as $ansefpublication) {
            Publications::create([
                'person_id' => $pi->id,
                'journal' => $ansefpublication->reference . ", " . $ansefpublication->authors . ": " . $ansefpublication->link,
                'title' => getCleanString($ansefpublication->title),
                'year' => 0,
                'domestic' => '0',
                'ansef_supported' => '1'
            ]);
        }
        }
        // \Debugbar::error('Added pi ansefpublications.');

        // Add director person
        $director = Person::create([
            'birthdate' => null,
            'birthplace' => '',
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => getCleanString($proposal->director_first_name),
            'last_name' => getCleanString($proposal->director_last_name),
            'nationality' => '',
            'type' => 'support',
            'specialization' => '',
            'user_id' => $user->id
        ]);
        // \Debugbar::error('Added director.');

        // Add user and person for admin
        $propadmin = $administrators[4];
        $adminaccount = $accounts[922];
        if (Arr::exists($administrators, $proposal->administrator_id)) {
            $propadmin = $administrators[$proposal->administrator_id];
            if (Arr::exists($accounts, $propadmin->account_id)) {
                $adminaccount = $accounts[$propadmin->account_id];
            } else {
                $propadmin = $administrators[4];
                $adminaccount = $accounts[922];
            }
        }
        $adminuser = User::firstOrCreate(
            [
                'email' => $adminaccount->username
            ],
            [
                'password' => bcrypt($generate_password),
                'password_salt' => 10,
                'remember_token' => null,
                'role_id' => $admin_role->id,
                'requested_role_id' => 0,
                'confirmation' => "1",
                'state' => 'active'
            ]
        );
        $administrator = Person::firstOrCreate(
            [
                'first_name' => getCleanString($propadmin->first_name),
                'last_name' => getCleanString($propadmin->last_name),
                'type' => 'admin'
            ],
            [
                'birthdate' => null,
                'birthplace' => '',
                'sex' => 'neutral',
                'state' => 'foreign',
                'nationality' => '',
                'type' => 'admin',
                'specialization' => '',
                'user_id' => $adminuser->id
            ]
        );
        // \Debugbar::error('Added admin user and person.');

        // Add proposal
        $state = 'unsuccessfull';
        if ($proposal->awardee == 1) $state = 'approved 2';

        $categories = DB::connection('mysqlold')->table('categories')
            ->get()->keyBy('id');
        $subcategories = DB::connection('mysqlold')->table('subcategories')
            ->get()->keyBy('id');

        $subcat = Category::where('abbreviation', '=', $subcategories[$proposal->subcategory_id]->label)->first();
        $secsubcat = Category::where('abbreviation', '=', $subcategories[$proposal->secondary_subcategory_id]->label)->first();
        $psubcat = Category::find($subcat->parent_id);
        $psecsubcat = Category::find($secsubcat->parent_id);
        $cat = [];
        $cat["parent"] = [strval($psubcat->id)];
        $cat["sub"] = [strval($subcat->id)];
        $cat["sec_parent"] = [strval($psecsubcat->id)];
        $cat["sec_sub"] = [strval($secsubcat->id)];

        $p = Proposal::create([
            'title' => getCleanString($proposal->title),
            'abstract' => $proposal->abstract,
            'document' => $proposal->document_full_url,
            'overall_score' => $proposal->score,
            'state' => $state,
            'comment' => '' . $this->proposal_id,
            'rank' => 0,
            'competition_id' => $competition->id,
            'categories' => json_encode($cat),
            'proposal_admin' => $administrator->id,
            'user_id' => $user->id
        ]);
        // \Debugbar::error('Added proposal.');

        // Associate PI and director
        PersonType::create([
            "person_id" => $pi->id,
            "proposal_id" => $p->id,
            "subtype" => 'PI'
        ]);

        PersonType::create([
            "person_id" => $director->id,
            "proposal_id" => $p->id,
            "subtype" => 'director'
        ]);
        // \Debugbar::error('Added associations.');

        // Add collaborators
        $collaborators = DB::connection('mysqlold')->table('collaborators')
            ->where('proposal_id', '=', $this->proposal_id)->get();
        foreach ($collaborators as $collaborator) {
            // \Debugbar::error('Adding collaborator id ' . $collaborator->id);
            $per = Person::create([
                'birthdate' => !empty($collaborator->birthdate) ? date($collaborator->birthdate) : null,
                'birthplace' => '',
                'sex' => 'neutral',
                'state' => 'domestic',
                'first_name' => getCleanString($collaborator->first_name),
                'last_name' => getCleanString($collaborator->last_name),
                'nationality' => $collaborator->foreign_status == 1 ? '' : 'Armenia',
                'type' => 'participant',
                'specialization' => '',
                'user_id' => null
            ]);

            PersonType::create([
                "person_id" => $per->id,
                "proposal_id" => $p->id,
                "subtype" => 'collaborator'
            ]);

            Phone::create([
                "person_id" => $per->id,
                "country_code" => 0,
                "number" => $collaborator->phone
            ]);

            Email::create([
                "person_id" => $per->id,
                "email" => $collaborator->email
            ]);

            Address::create([
                'country_id' => 8,
                'province' => '',
                'street' =>  $collaborator->address,
                'addressable_id' => $per->id,
                'addressable_type' => 'App\Models\Person',
                'city' => ''
            ]);
        }
        // \Debugbar::error('Added collaborators.');

        // Add referee reports
        $reports = DB::connection('mysqlold')->table('reports')
            ->where('proposal_id', '=', $this->proposal_id)->get();

        foreach ($reports as $report) {
            // \Debugbar::error('Adding report id ' . $report->id);
            $referee = $referees[4];
            $refaccount = $accounts[396];
            if (Arr::exists($referees, $report->referee_id)) {
                $referee = $referees[$report->referee_id];
                if (Arr::exists($accounts, $referee->account_id)) {
                    $refaccount = $accounts[$referee->account_id];
                } else {
                    $referee = $referees[4];
                    $refaccount = $accounts[396];
                }
            }

            $refuser = User::firstOrCreate(
                [
                    'email' => $refaccount->username
                ],
                [
                    'password' => bcrypt($generate_password),
                    'password_salt' => 10,
                    'remember_token' => null,
                    'role_id' => $referee_role->id,
                    'requested_role_id' => 0,
                    'confirmation' => "1",
                    'state' => 'active'
                ]
            );
            if ($refuser->email == 'lilit@ansef.org')
                \Debugbar::error('3 - Created user ' . $refuser->id . ' with role ' . $refuser->role_id);

            $ref = Person::firstOrCreate(
                [
                    'first_name' => getCleanString($referee->first_name),
                    'last_name' => getCleanString($referee->last_name),
                    'type' => 'referee'
                ],
                [
                    'birthdate' => null,
                    'birthplace' => '',
                    'sex' => 'neutral',
                    'state' => 'foreign',
                    'nationality' => '',
                    'specialization' => $referee->comments,
                    'user_id' => $refuser->id
                ]
            );

            $rep = RefereeReport::create([
                "private_comment" => $report->private_comments,
                "public_comment" => $report->public_comments,
                "state" => 'complete',
                "proposal_id" => $p->id,
                "competition_id" => $competition->id,
                "due_date" => date($compyear . "-12-30"),
                "overall_score" => $report->score,
                "referee_id" => $ref->id
            ]);

            Score::create([
                'score_type_id' => $scoretype['Significance']->id,
                'value' => $report->significance,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Approach']->id,
                'value' => $report->approach,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Innovation']->id,
                'value' => $report->innovation,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Investigator']->id,
                'value' => $report->investigator,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Budget']->id,
                'value' => $report->budget,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Proposal']->id,
                'value' => $report->proposal_score,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['OverallScore']->id,
                'value' => $report->score,
                'report_id' => $rep->id
            ]);
        }
        // \Debugbar::error('Added reports.');

        // Add budget items
        $budget_items = DB::connection('mysqlold')->table('budget_items')
            ->where('proposal_id', '=', $this->proposal_id)->get();
        foreach ($budget_items as $budget_item) {
            // \Debugbar::error('Adding budget item id ' . $budget_item->id);
            $budcat = BudgetCategory::where('name', '=', $budget_item->expense_type)->first();
            if (empty($budcat)) $budcat = BudgetCategory::first();
            BudgetItem::create([
                'budget_cat_id' => $budcat->id,
                'description' => $budget_item->detail,
                'amount' => $budget_item->amount,
                'proposal_id' => $p->id
            ]);
        }
        // \Debugbar::error('Added budget items.');

        // // Add proposal reports
        $award = DB::connection('mysqlold')->table('awards')
            ->where('proposal_id', '=', $this->proposal_id)->first();
        if (!empty($award)) {
            // \Debugbar::error('Adding award id ' . $award->id);
            $compyear = date('Y', strtotime($p->date)) + 1;
            ProposalReports::create([
                'description' => 'Midterm report',
                'document' => $award->midterm_full_url,
                'proposal_id' => $p->id,
                'due_date' => date($compyear . '-07-01'),
                'approved' => '1'
            ]);
            ProposalReports::create([
                'description' => 'Final report',
                'document' => $award->final_full_url,
                'proposal_id' => $p->id,
                'due_date' => date($compyear . '-12-15'),
                'approved' => '1'
            ]);
            $p->state = 'approved 2';
            $p->save();
            // \Debugbar::error('Added proposal reports.');
        }
    }
}
