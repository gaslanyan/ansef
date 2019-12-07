<?php

/**
 * Created by PhpStorm.
 * User: Gtech-pc
 * Date: 07.02.2019
 * Time: 15:59
 */



function checkPermission($permissions)
{
    $guard = basename(url()->current());
    $obj = \Illuminate\Support\Facades\Auth::guard($guard)->user();

    if (empty($obj) && isset($_COOKIE['c_user']) && $_COOKIE['c_user'] !== "admin")
        return false;

    if (empty($obj)) {
        $admin = ['admin', 'superadmin'];
        $role = \App\Models\Role::whereIn('name', $admin)->first();
        $role_id = $role->id;
    } else  $role_id = $obj->role_id;

    $userAccess = getMyPermission($role_id);
//dd($userAccess);
    foreach ($permissions as $key => $value) {
//        dd($userAccess);
        if ($value == $userAccess
            || $userAccess == "superadmin") {
            return true;
        }
    }
    return false;
}

function getMyPermission($id)
{
    $get_role = \App\Models\Role::find($id);

    $name = "";
    switch ($get_role->name) {
        case 'admin':
            $name = $get_role->name;
            break;
        case 'superadmin':
            $name = $get_role->name;
            break;
        case 'referee':
            $name = $get_role->name;
            break;
        case 'viewer':
            $name = $get_role->name;
            break;
        case 'applicant':
            $name = $get_role->name;
            break;
    }
    return $name;
}

function getMessage($name)
{
    $message = \App\Models\Template::where('name', '=', $name)->first();
    return $message->text;
}

function get_Cookie()
{
    if (!empty($_COOKIE['c_user']))
        $c_user = $_COOKIE['c_user'];
    else
        $c_user = basename(url()->current());
    return $c_user;
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

/* Check and find if user_id is exists */
function checkUserId($role)
{
    if (!empty(Auth::guard(get_Cookie())->user()->id)) {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $table = \App\Models\Person::where('user_id', $user_id)->where('type', null)->first();

        if (!empty($table)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getUserIdByRole($role)
{
    if (!empty(Auth::guard(get_Cookie())->user()->id)) {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $table = \App\Models\Person::where('user_id', $user_id)->where('type', $role)->first();

        if (!empty($table)) {
            return $table->id;

        } else {
            return false;
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

// function signUser()
// {
//     return \App\Models\Person::select('persons.*', 'persons.id as pid', 'sessions.*')->where('persons.user_id', '=', \Illuminate\Support\Facades\Session::get('u_id'))
//         ->join('sessions', 'sessions.user_id', '=', 'persons.user_id')
//         ->first();
// }

function signedApplicant() {
    $user_id = \Auth::guard(get_Cookie())->user()->id;
    return \App\Models\Person::where('user_id','=', $user_id)->where('type', '=', null)->first();
}

function signedPerson()
{
    $user_id = \Auth::guard(get_Cookie())->user()->id;
    return \App\Models\Person::where('user_id', '=', $user_id)->whereIn('type', ['admin','referee','viewer'])->first();
}

function cookieSign_id()
{
    if (!empty($_COOKIE['sign_id'])) {
        $c_user_id = $_COOKIE['sign_id'];
        $user = \App\Models\Person::with('user.role')
            ->where('user_id', $c_user_id)->first();

        return $user;
    }
    return null;
}

function getUserID()
{
    if (empty($_COOKIE['sign_id']))
        $user_id = \Auth::guard(get_Cookie())->user()->id;
    else
        $user_id = $_COOKIE['sign_id'];
    return $user_id;
}

function getPerson($id)
{
    $person = \App\Models\Person::select('persons.*', 'users.email')
        ->join('users', 'users.id', '=', 'persons.user_id')
        ->whereIn('persons.type',['referee','admin','viewer',null])
        ->where('persons.id', '=', $id)->first();
    if ($person)
        return $person;
    else
        return false;
}

function getPersonNameByPI($id)
{

    $pi = \App\Models\Person::select('persons.first_name', 'persons.last_name')
        ->where('persons.id', '=', $id)->first();
    if (!empty($pi)) {
        return $pi->first_name . " " . $pi->last_name;
    } else {
        return null;
    }

}

function getEmailByID($id)
{

    $pi = \App\Models\User::select('email')
        ->where('id', '=', $id)->first();
    if (!empty($pi)) {
        return $pi->email;
    } else {
        return null;
    }

}

function getEmailByPersonID($pid)
{
    $pi = \App\Models\Email::select('email')
        ->where('person_id', '=', $pid)->first();
    if (!empty($pi)) {
        return $pi->email;
    } else {
        return null;
    }

}

// VVS
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

function getAddressByPivot($id)
{
    $address = \App\Models\Person::find($id)
        ->address()
        ->with('person')->first();
    if (!empty($address))
        return $address;
    return null;
}

function getLocationInfoByIp($ip)
{
    $details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
    dd($details);
    return $details->country;
}

function getScoreTypeNames()
{
    return ['Significance', 'Approach', 'Innovation',
        'Investigator', 'Budget', 'Proposal', 'Overall Score'];
}

function getScoreTypeValues()
{
    return [0 => 'Select a value', 1 => 'None', 2 => 'Poor', 3 => 'Fair',
        4 => 'Good', 5 => 'Very Good',
        6 => 'Excellent', 7 => 'Outstanding'];
}

function printUser($user, $accounts)
{

    $add = getAddressByPivot($user['id']);
    $email = \App\Models\User::select('email')->where('id', '=', $accounts->person_pi_id)->first();
    return '
            <div class="col-md-12">
                <strong><i class="fa fa-user-check margin-r-5"></i>'
        . $user["type"] . ':</strong>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-4">
                            <div class="col-md-12">
                                <strong>Name:</strong>
                            </div>
                            <div class="col-md-12">
                                <p>' . $user["last_name"] . " " . $user["first_name"] . '</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-md-12">
                                <strong>Address:</strong>
                            </div>
                            <div class="col-md-12">

                                <p>' . $add["province"] . "," . $add["street"] . '</p>
                            </div>
                        </div>
                        <div class="col-4">

                            <div class="col-md-12">
                                <strong>Phone:</strong>
                            </div>
                            <div class="col-md-12">
                                <p>+' . $user["phone"]["country_code"] .
        "-" . $user["phone"]["number"] . '</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-md-12">
                                <strong>Email:</strong>
                            </div>
                            <div class="col-md-12">
                                <p>' . $email['email'] . '</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-md-12">
                                <strong>Birthdate:</strong>
                            </div>
                            <div class="col-md-12">
                                <p>' . $user["birthdate"] . '</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-md-12">
                                <strong>Type:</strong>
                            </div>
                            <div class="col-md-12">
                                <p>' . $user["state"] . '</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

}

//get selected value for filter and search into datatable
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

//export excel or csv
function exportExcelOrCsv($name, $type)
{
    $queryBuilder = \Illuminate\Support\Facades\DB::table($name)->select('*');

//    $fromDate = $request->input('from_date');
//    $toDate = $request->input('to_date');
//    $sortBy = $request->input('sort_by');

    $title = str_replace('_', ' ', strtoupper($name)) . ' TABLE DATA.';

    $meta = [ // For displaying filters description on header
//        'Registered on' => $fromDate . ' To ' . $toDate,
//        'Sort By' => $sortBy
    ];

//    $queryBuilder = User::select(['name', 'balance', 'registered_at']); // Do some querying..
//    ->whereBetween('registered_at', [$fromDate, $toDate])
//        ->orderBy($sortBy);
    $columns = [];
    $fields = \Illuminate\Support\Facades\Schema::getColumnListing($name);
    foreach ($fields as $index => $field) {
        $columns[str_replace('_', ' ', strtoupper($field))] = $field;
    }

    // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
    $export = '';
    if ($type !== "csv")
        $export = \Jimmyjs\ReportGenerator\Facades\ExcelReportFacade::of($title, $meta, $queryBuilder, $columns)
//        ->editColumn('Registered At', [ // Change column class or manipulate its data for displaying to report
//            'displayAs' => function ($result) {
//                return $result->registered_at->format('d M Y');
//            },
//            'class' => 'left'
//        ])
//        ->editColumns(['Total Balance', 'Status'], [ // Mass edit column
//            'class' => 'right bold'
//        ])
//        ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
//            'Total Balance' => 'point' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
//        ])
            ->limit(20)// Limit record to be showed
            ->download($name);
    else

        $export = \Jimmyjs\ReportGenerator\Facades\CSVReportFacade::of($title, $meta, $queryBuilder, $columns)
//        ->editColumn('Registered At', [ // Change column class or manipulate its data for displaying to report
//            'displayAs' => function ($result) {
//                return $result->registered_at->format('d M Y');
//            },
//            'class' => 'left'
//        ])
//        ->editColumns(['Total Balance', 'Status'], [ // Mass edit column
//            'class' => 'right bold'
//        ])
//        ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
//            'Total Balance' => 'point' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
//        ])
//            ->limit(20)// Limit record to be showed
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

function abbreviate($string) {
    if (strlen($string) > 4) {
        $string = substr($string, 0, 4) . substr($string, -1) . '.';
    }

    return $string;
}

function personidforuser($id) {
    $person = \App\Models\Person::where('user_id','=',$id)->whereNotIn('type', ['participant', 'support'])->first();
    return $person->id;
}

function createperson($user_id) {
    $person = \App\Models\Person::where('user_id','=',$user_id)->where('type','=',null)->first();

    if(empty($person)) {
        \App\Models\Person::create(['user_id' => $user_id,
                        'first_name' => '',
                        'last_name' => '',
                        'specialization' => '']);
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

function getCleanString($text) {
    return (!empty($text) && $text != null) ? cleanString(ucwords(mb_strtolower($text))) : '';
}
