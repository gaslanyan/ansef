<?php

use App\Models\ProposalPerson;
use App\Models\Category;
use App\Models\Proposal;
use App\Models\Person;
use App\Models\Email;
use App\Models\Address;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Degree;
use App\Models\DegreePerson;
use App\Models\InstitutionPerson;
use App\Models\Publication;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\Score;
use App\Models\ScoreType;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

function checkPermission($permissions)
{
    $user = Auth::guard(get_role_cookie())->user();
    if (empty($user)) return false;
    $userAccess = get_role_cookie();
    if ($userAccess == "superadmin") return true;
    foreach ($permissions as $value) {
        if ($value == $userAccess) {
            return true;
        }
    }
    return false;
}

function messageFromTemplate($name)
{
    $message = \App\Models\Template::where('name', '=', $name)->first();
    return $message->text;
}

function get_role_cookie()
{
    if (!empty($_COOKIE['user_role']))
        $user_role = $_COOKIE['user_role'];
    else
        $user_role = basename(url()->current());
    return $user_role;
}

function getEnumValues($table, $column)
{
    $type = \Illuminate\Support\Facades\DB::select(\Illuminate\Support\Facades\DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $enum = array();
    foreach (explode(',', $matches[1]) as $value) {
        $v = trim($value, "'");
        $enum = array_add($enum, $v, $v);
    }
    return $enum;
}

function userHasPerson()
{
    $role = get_role_cookie();
    if (!empty(Auth::guard($role)->user())) {
        $user_id = Auth::guard($role)->user()->id;
        $person = \App\Models\Person::where('user_id', $user_id)
            ->where('type', $role)
            ->first();

        if (!empty($person)) {
            return true;
        } else {
            return view('errors.404');
        }
    } else {
        return view('errors.404');
    }
}

function getPersonIdByRole($role)
{
    if (!empty(Auth::guard(get_role_cookie())->user()->id)) {
        $user_id = Auth::guard(get_role_cookie())->user()->id;
        $person = \App\Models\Person::where('user_id', $user_id)->where('type', $role)->first();
        if (!empty($person)) {
            return $person->id;
        } else {
            return view('errors.404');
        }
    } else {
        return view('errors.404');
    }
}

function randomPassword()
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function getTableNames()
{
    return ['competitions', 'persons', 'proposals', 'referee_reports', 'scores', 'users', 'books', 'publications'];
}

function getTableColumnsName($table)
{
    $coll = [];
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
    $pattern = '/(.*?)(id|at)$/';
    foreach ($columns as $key => $column) {
        if (!preg_match($pattern, $column)) {
            $coll[$key] = $column;
        }
    }
    return $coll;
}

function getTableColumns($items)
{
    foreach ($items as $key => $item) {

        echo "<tr >";
        foreach ($item as $i) {
            echo "<td>$i</td >";
        }
        echo "</tr >";
    }
}

function loggedApplicant()
{
    if (!empty(Auth::guard(get_role_cookie())->user())) {
        $user_id = Auth::guard(get_role_cookie())->user()->id;
        return \App\Models\Person::where('user_id', '=', $user_id)->whereIn('type', ['applicant', 'referee', 'admin', 'superadmin'])->first();
    } else {
        return view('errors.404');
    }
}

function loggedPerson()
{
    if (!empty(Auth::guard(get_role_cookie())->user())) {
        $user_id = Auth::guard(get_role_cookie())->user()->id;
        return \App\Models\Person::where('user_id', '=', $user_id)->whereNotIn('type', ['participants', 'support'])->first();
    } else {
        return view('errors.404');
    }
}

function getUserID()
{
    if (!empty(Auth::guard(get_role_cookie())->user()->id)) {
        $user_id = Auth::guard(get_role_cookie())->user()->id;
        return $user_id;
    } else {
        return view('errors.404');
    }
}

function isPerson($id)
{
    return \App\Models\Person::whereIn('persons.type', ['referee', 'admin', 'viewer', 'applicant'])
        ->where('persons.id', '=', $id)->exists();
}


function getEmailByPersonID($pid)
{
    return \App\Models\Person::find($pid)->emails()->first();
}

function getAddressesByPersonID($pid)
{
    return \App\Models\Person::find($pid)->addresses()->first();
}

function is_dir_empty($dir)
{
    if (!is_readable($dir)) return NULL;
    return (count(scandir($dir)) == 2);
}

function getCategoriesNameByID($id)
{
    //$categories = json_decode($ids);
    $catname = \App\Models\Category::select('title')
        ->where('id', $id)->first();
    return $catname->title . " ";
}

function getSelectedValue($select, $option)
{
    $option_value = "";
    foreach ($select as $index => $item) {
        if ($item->id === $option)
            $option_value = $item->title;
    }
    return $option_value;
}

function getSelectedValueByKey($select, $option)
{
    $option_value = "";
    foreach ($select as $index => $item) {
        if ($index === $option)
            $option_value = $item;
    }
    return $option_value;
}

function exportExcelOrCsv($name, $type)
{
    $queryBuilder = \Illuminate\Support\Facades\DB::table($name)->select('*');

    $title = str_replace('_', ' ', strtoupper($name)) . ' TABLE DATA';

    $meta = [];

    $columns = [];
    $fields = \Illuminate\Support\Facades\Schema::getColumnListing($name);
    foreach ($fields as $index => $field) {
        $columns[str_replace('_', ' ', strtoupper($field))] = $field;
    }

    $export = '';
    if ($type !== "csv")
        $export = \Jimmyjs\ReportGenerator\Facades\ExcelReportFacade::of($title, $meta, $queryBuilder, $columns)
            ->download($name);
    else
        $export = \Jimmyjs\ReportGenerator\Facades\CSVReportFacade::of($title, $meta, $queryBuilder, $columns)
            ->download($name);
    return $export;
}

function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}

function getContentByJSON($content)
{
    $content_json = json_decode($content, true);
    $text = '';
    foreach ($content_json as $index => $item) {
        if (!is_array($item))
            $text .= "$index: $item\n";
        else {
            $val = "";
            foreach ($item as $key => $value) {
                $val .= $value . "\n";
            }
            $text .= "$index: $val\n";
        }
    }
    return $text;
}

function getProposalTag($pid)
{
    $prop = \App\Models\Proposal::where('id', $pid)->first();
    $propcat = json_decode($prop->categories, true);

    $cat = \App\Models\Category::where('id', $propcat['parent'][0])->first()->abbreviation ?? 'None';
    $subcat = \App\Models\Category::where('id', $propcat['sub'][0])->first()->abbreviation ?? 'None';

    $comp = \App\Models\Competition::where('id', $prop['competition_id'])->first();

    return $comp->title . ':' . $cat . '-' . $subcat . '-' . $pid;
}

function getTitleOfCompetition($str)
{
    $pos = strpos($str, ':');
    return $pos ? substr($str, 0, $pos) : $str;
}

function formatDate($str)
{
    $time = strtotime($str);
    $newformat = date('Y-m-d', $time);
    return $newformat;
}

function truncate($string, $length)
{
    if (strlen($string) > $length) {
        $string = substr($string, 0, $length) . '...';
    }

    return $string;
}

function abbreviate($string)
{
    if (strlen($string) > 4) {
        $string = substr($string, 0, 4) . substr($string, -1) . '.';
    }

    return $string;
}

function personidforuser($id)
{
    $person = \App\Models\Person::where('user_id', '=', $id)->whereNotIn('type', ['participant', 'support'])->first();
    return $person->id;
}

function createperson($user_id, $role)
{
    $person = \App\Models\Person::where('user_id', '=', $user_id)->where('type', '=', $role)->first();

    if (empty($person)) {
        \App\Models\Person::firstOrCreate([
            'user_id' => $user_id,
            'type' => $role
        ], [
            'first_name' => '',
            'last_name' => '',
            'specialization' => ''
        ]);
    }
}

function cleanString($text)
{
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function getCleanString($text)
{
    return (!empty($text) && $text != null) ? cleanString(ucwords(mb_strtolower($text))) : '';
}

function overallScore($rid)
{
    $scores = Score::where('report_id', '=', $rid)
        ->join('score_types', 'score_types.id', '=', 'scores.score_type_id')
        ->get();
    if (!empty($scores) && count($scores) > 0) {
        $result = $scores->reduce(function ($carry, $item) {
            return $carry + ($item->weight * 100.0 * $item->value / $item->max);
        });
        $weights = $scores->reduce(function ($carry, $item) {
            return $carry + $item->weight;
        });
        return (int) ($result / $weights);
    } else return 0;
}

function checkproposal($id)
{
    $p = Proposal::find($id);
    $messages = [];
    $warnings = [];
    if (empty($p->title) || $p->title == "") array_push($messages, "Proposal must have a title.");
    if (empty($p->abstract) || $p->abstract == "") array_push($messages, "Proposal must have an abstract.");
    if (empty($p->document) || $p->document == "") array_push($messages, "Proposal must have a document uploaded detailing the project.");
    $pi = ProposalPerson::where('proposal_id', '=', $id)
        ->join('persons', 'persons.id', '=', 'person_id')
        ->where('subtype', '=', 'PI')
        ->first();
    if (empty($pi)) array_push($messages, "Proposal must have one Principal Investigator (PI).");
    else {
        $picount = ProposalPerson::where('proposal_id', '=', $id)
            ->where('subtype', '=', 'PI')
            ->count();
        if ($picount > 1) array_push($messages, "Proposal cannot have more than one Principal Investigator.");

        if ($p->competition()->first()->allow_foreign == "0") {
            if ($pi->state == "foreign")
                array_push($messages, "This competition does not allow a PI (" . $pi->first_name . " " . $pi->last_name . ") that is not based in Armenia.");
        }
        $mindegreeid = $p->competition()->min_level_deg_id;
        $maxdegreeid = $p->competition()->max_level_deg_id;
        $pidegrees = DegreePerson::where('person_id','=',$pi->id)->get()->sortBy('degree_id');
        if($mindegreeid != 1) {
            if(count($pidegrees) == 0)
                array_push($messages, "PI does not have minimum degree requirement.");
            else {
                if($pidegrees[count($pidegrees)-1]->degree_id < $mindegreeid)
                    array_push($messages, "PI does not have minimum degree requirement.");
            }
        }
        if ($maxdegreeid != 1) {
            if(count($pidegrees) > 0) {
                if ($pidegrees[0]->degree_id > $maxdegreeid)
                    array_push($messages, "PI does not have satisfy maximum degree requirement.");
            }
        }
    }
    $members = ProposalPerson::where('proposal_id', '=', $id)
        ->join('persons', 'persons.id', '=', 'person_id')
        ->get();
    foreach ($members as $member) {
        $ecount = Email::where('person_id', '=', $member->person_id)->count();
        if ($ecount == 0)
            array_push($messages, $member->first_name . " " . $member->last_name . " must have at least one email address provided.");
        $addcount = Address::where('addressable_id', '=', $member->person_id)
            ->where('addressable_type', '=', 'App\Models\Person')
            ->count();
        if ($addcount == 0)
            array_push($messages, $member->first_name . " " . $member->last_name . " must have at least one mailing address provided.");

        if ($member->type == "participant") {
            $education = DegreePerson::where('person_id', '=', $member->person_id)->count();
            $employment = InstitutionPerson::where('person_id', '=', $member->person_id)->count();

            if ($education == 0)
                array_push($warnings, "You have not provided an educational history for participant " . $member->first_name . " " . $member->last_name . ". It is highly recommended that the proposal includes an educational history for each project participant.");
            if ($employment == 0)
                array_push($warnings, "You have not provided an employment history for participant " . $member->first_name . " " . $member->last_name . ". It is highly recommended that the proposal includes an employment history for each project participant.");
        }
    }

    $budget = $p->budget();
    if ($budget["validation"] != "")
        array_push($messages, $budget["validation"]);

    $recs = $p->competition->recommendations;
    $missingrecs = [];
    $submittedrecs = [];
    if ($recs > 0) {
        $recommenders = ProposalPerson::where('proposal_id', '=', $id)
            ->where('subtype', '=', 'supportletter')
            ->join('persons', 'persons.id', '=', 'person_id')
            ->get();
        if (count($recommenders) < $recs)
            array_push($messages, "This competition requires that a proposal is accompanied by a minimum of " . $recs . " recommendation letter" . ($recs > 1 ? 's' : '') . " of support. You currently have " . count($recommenders) . " persons added to the proposal in the role of recommenders. Make sure you add at least " . $recs . ".");

        foreach ($recommenders as $recommender) {
            $email = Email::where('person_id', '=', $recommender->person_id)->first();

            if (Recommendation::where('proposal_id', '=', $p->id)->where('document', '!=', null)->where('person_id', '=', $recommender->person_id)->exists()) {
                array_push($submittedrecs, ["id" => $recommender->person_id, "email" => (!empty($email) ? $email->email : ''), "name" => $recommender->first_name . " " . $recommender->last_name]);
            } else {
                if (!empty($email))
                    array_push($missingrecs, ["id" => $recommender->person_id, "email" => $email->email, "name" => $recommender->first_name . " " . $recommender->last_name]);
            }
        }
    }

    return [
        'messages' => $messages,
        'warnings' => $warnings,
        'submittedrecs' => $submittedrecs,
        'missingrecs' => $missingrecs,
        'pi' => $pi
    ];
}

function updateProposalScore($id)
{
    $p = Proposal::find($id);
    $average = RefereeReport::where('proposal_id', '=', $id)->avg('overall_score');

    $p->overall_score = $average;
    $p->save();
}

function updateProposalState($id)
{
    $p = Proposal::find($id);
    $reports = RefereeReport::where('proposal_id', '=', $id)->get();

    if (count($reports) == 0) $p->state = "submitted";
    else {
        $complete = true;
        foreach ($reports as $report) {
            if ($report->state == 'in-progress' || $report->state == 'rejected') {
                $complete = false;
                break;
            }
        }
        if ($complete) $p->state = "complete";
        else $p->state = "in-review";
    }
    $p->save();
}

function proppath($pid)
{
    $path = ppath($pid);
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path, 0775, true);
    }

    return 'app/' . $path;
}

function ppath($pid)
{
    return 'proposals/prop-' . $pid;
}

function getPiData($pids)
{
    $proposals = Proposal::whereIn('id', $pids)->get();
    $ages = collect([]);
    $sexes = collect([]);
    $publications = collect([]);
    $publicationsansef = collect([]);
    $publicationsdom = collect([]);
    $publicationsansefdom = collect([]);
    foreach ($proposals as $p) {
        $pi = $p->pi();
        if (empty($pi)) {
            $ages[$p->id] = 0.0;
            $sexes[$p->id] = (0.0);
            $publications[$p->id] = (0.0);
            $publicationsansef[$p->id] = (0.0);
            $publicationsdom[$p->id] = (0.0);
            $publicationsansefdom[$p->id] = (0.0);
        } else {
            $from = new DateTime($pi->birthdate);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
            $tenyearsago = (int)($to->format('Y'))-10;
            $ages[$p->id] = $age;
            $sexes[$p->id] = ($pi->sex == 'female' ? 1.0 : 0.0);
            $publications[$p->id] = Publication::where('person_id','=',$pi->id)
                                            ->where('year','>=', $tenyearsago)
                                            ->where('domestic', '=', '0')
                                            ->where('ansef_supported', '=', '0')
                                            ->count();
            $publicationsansef[$p->id] = Publication::where('person_id', '=', $pi->id)
                                            ->where('year', '>=', $tenyearsago)
                                            ->where('domestic', '=', '0')
                                            ->where('ansef_supported','=','1')
                                            ->count();
            $publicationsdom[$p->id] = Publication::where('person_id', '=', $pi->id)
                                            ->where('year', '>=', $tenyearsago)
                                            ->where('domestic','=','1')
                                            ->where('ansef_supported', '=', '0')
                                            ->count();
            $publicationsansefdom[$p->id] = Publication::where('person_id', '=', $pi->id)
                                            ->where('year', '>=', $tenyearsago)
                                            ->where('domestic', '=', '1')
                                            ->where('ansef_supported', '=', '1')
                                            ->count();
       }
    }
    return ["ages" => $ages,
            "sexes" => $sexes,
            "publications" => $publications,
            "publications_ansef" => $publicationsansef,
            "publications_dom" => $publicationsdom,
            "publications_ansef_dom" => $publicationsansefdom
        ];
}

function getParticipantData($pids)
{
    $proposals = Proposal::whereIn('id', $pids)->get();
    $avgages = collect([]);
    $avgsexes = collect([]);
    $counts = collect([]);
    $juniorcounts = collect([]);
    $publications = collect([]);
    $publicationsansef = collect([]);
    $publicationsdom = collect([]);
    $publicationsansefdom = collect([]);
    foreach ($proposals as $p) {
        $pps = ProposalPerson::where('proposal_id', '=', $p->id)
            ->whereIn('subtype', ['PI', 'collaborator'])
            ->get();
        $ages = 0.0;
        $sexes = 0.0;
        $count = 0;
        $juniors = 0;
        $pubs = 0;
        $pubsansef = 0;
        $pubsdom = 0;
        $pubsansefdom = 0;
        foreach ($pps as $pp) {
            $person = Person::find($pp->person_id);
            $from = new DateTime($person->birthdate);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y;
            $ages += $age;
            $sexes += ($person->sex == 'female' ? 1.0 : 0.0);
            $count += 1;
            $maxdegree = DegreePerson::where('person_id', '=', $person->id)->max('degree_id');
            if ($maxdegree <= 3) $juniors++;
            $tenyearsago = (int) ($to->format('Y')) - 10;
            $pubs += Publication::where('person_id', '=', $pp->person_id)
                ->where('year', '>=', $tenyearsago)
                ->where('ansef_supported', '=', '0')
                ->where('domestic', '=', '0')
                ->count();
            $pubsansef += Publication::where('person_id', '=', $pp->person_id)
                ->where('year', '>=', $tenyearsago)
                ->where('domestic', '=', '0')
                ->where('ansef_supported', '=', '1')
                ->count();
            $pubsdom += Publication::where('person_id', '=', $pp->person_id)
                ->where('year', '>=', $tenyearsago)
                ->where('ansef_supported', '=', '0')
                ->where('domestic', '=', '1')
                ->count();
            $pubsansefdom += Publication::where('person_id', '=', $pp->person_id)
                ->where('year', '>=', $tenyearsago)
                ->where('domestic', '=', '1')
                ->where('ansef_supported', '=', '1')
                ->count();
        }
        if ($count > 0) {
            $avgages[$p->id] = ($ages / $count);
            $avgsexes[$p->id] = ($sexes / $count);
            $publications[$p->id] = ($pubs / $count);
            $publicationsansef[$p->id] = ($pubsansef / $count);
            $publicationsdom[$p->id] = ($pubsdom / $count);
            $publicationsansefdom[$p->id] = ($pubsansefdom / $count);
        } else {
            $avgages[$p->id] = (0.0);
            $avgsexes[$p->id] = (0.0);
            $publications[$p->id] = (0.0);
            $publicationsansef[$p->id] = (0.0);
            $publicationsdom[$p->id] = (0.0);
            $publicationsansefdom[$p->id] = (0.0);
        }
        $counts[$p->id] = $count;
        $juniorcounts[$p->id] = $juniors;
    }
    return ["avgages" => $avgages,
            "avgsexes" => $avgsexes,
            "counts" => $counts,
            "juniorcounts" => $juniorcounts,
            "part_publications" => $publications,
            "part_publications_ansef" => $publicationsansef,
            "part_publications_dom" => $publicationsdom,
            "part_publications_ansef_dom" => $publicationsansefdom
            ];
}

function getCategoryData($pids)
{
    $proposals = Proposal::whereIn('id', $pids)->get();
    $catmembership = collect([]);
    $subcatmembership = collect([]);
    foreach ($proposals as $p) {
        $propcat = json_decode($p->categories, true);

        $cat = Category::where('id', $propcat['parent'][0])->first()->abbreviation ?? 'none';
        $subcat = Category::where('id', $propcat['sub'][0])->first()->abbreviation ?? 'none';

        $catmembership[$p->id] = $cat;
        $subcatmembership[$p->id] = $subcat;
    }
    return ["catmembership" => $catmembership, "subcatmembership" => $subcatmembership];
}

function collectionToString($c)
{
    return $c->reduce(function ($previous, $value) {
        if (!$previous) return strval($value);
        return $previous . '\n' . strval($value);
    });
}

function getBudgetData($pids)
{
    $bcindexes = ['collaborator' => 0, "travel" => 1, "equipment" => 2, "other" => 3, "pi" => 4];
    $proposals = Proposal::whereIn('id', $pids)->get();
    $budgets = collect([]);
    $pisalaries = collect([]);
    $collabsalaries = collect([]);
    $avgsalaries = collect([]);
    $devsalaries = collect([]);
    $travels = collect([]);
    $equipments = collect([]);
    if (!empty($proposals) && count($proposals) > 0) {
        $budgcats = BudgetCategory::select('id')
            ->where('competition_id', '=', $proposals[0]->competition_id)
            ->get()->sortBy('id');
        // \Debugbar::error('budgcats: ' . collectionToString($budgcats));
        foreach ($proposals as $p) {
            $bis = BudgetItem::where('proposal_id', '=', $p->id)->get();
            $budget = 0.0;
            $pisal = 0.0;
            $collabsal = 0.0;
            $salaries = collect([]);
            $travel = 0.0;
            $equip = 0.0;
            foreach ($bis as $bi) {
                $budget += $bi->amount;
                // \Debugbar::error('$bi->budget_cat_id: ' . $bi->budget_cat_id);
                switch ($bi->budget_cat_id) {
                    case $budgcats[$bcindexes['collaborator']]->id:
                        $collabsal += $bi->amount;
                        $salaries->push($bi->amount);
                        break;
                    case $budgcats[$bcindexes['pi']]->id:
                        $pisal += $bi->amount;
                        $salaries->push($bi->amount);
                        break;
                    case $budgcats[$bcindexes['travel']]->id:
                        $travel += $bi->amount;
                        break;
                    case $budgcats[$bcindexes['equipment']]->id:
                        $equip += $bi->amount;
                        break;
                }
            }
            $budgets[$p->id] = $budget;
            $pisalaries[$p->id] = $pisal;
            $collabsalaries[$p->id] = $collabsal;
            $avg = $salaries->avg();
            $avgsalaries[$p->id] = $avg;
            $devsalaries[$p->id] = $salaries->reduce(function ($carry, $item) use ($avg) {
                return $carry + (($item - $avg) * ($item - $avg));
            });
            if (count($salaries) > 0) $devsalaries[$p->id] = sqrt($devsalaries[$p->id] / count($salaries));
            else $devsalaries[$p->id] = 0.0;
            $travels[$p->id] = $travel;
            $equipments[$p->id] = $equip;
        }
    }
    return [
        "budgets" => $budgets,
        "pisalaries" => $pisalaries,
        "collabsalaries" => $collabsalaries,
        "avgsalaries" => $avgsalaries,
        "devsalaries" => $devsalaries,
        "travels" => $travels,
        "equipments" => $equipments
    ];
}

function getScoreData($pids)
{
    $proposals = Proposal::whereIn('id', $pids)->get();
    $subscores = collect([]);
    $overallscores = collect([]);
    if (!empty($proposals) && count($proposals) > 0) {
        foreach ($proposals as $p) {
            $reports = RefereeReport::where('proposal_id', '=', $p->id)->get();
            $ss = collect([]);
            foreach ($reports as $report) {
                $scores = $report->scores()->get()->sortBy('id');
                foreach ($scores as $score) {
                    $ss->push($score->value);
                }
            }
            $overallscores[$p->id] = $p->overall_score;
            $subscores[$p->id] = $ss;
        }
    }
    return ["subscores" => $subscores, "overallscores" => $overallscores];
}

function propertyInSet($rules, $array)
{
    foreach ($array as $el) {
        if (property_exists($rules, $el)) return true;
    }
    return false;
}

function inbetween($val, $range)
{
    return $val >= $range[0] && $val <= $range[1];
}

function statline($label, $data, $data2, $key)
{
    return '<b>' . $label . ':</b> max ' . round($data[$key]->max()*10.0)/10.0 . "/" . round($data2[$key]->max()*10.0)/10.0 . ', min ' . round($data[$key]->min()*10.0)/10.0 . "/" . round($data2[$key]->min()*10.0)/10.0 . ", avg " . round($data[$key]->avg()*10.0)/10.0 . "/" . round($data2[$key]->avg()*10.0)/10.0 . "<br>";
}
