<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Applicant\BudgetCategoriesController;
use App\Models\Address;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Competition;
use App\Models\Degree;
use App\Models\DegreePerson;
use App\Models\Honors;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\PersonType;
use App\Models\Proposal;
use App\Models\ProposalReports;
use App\Models\Publications;
use App\Models\RefereeReport;
use App\Models\ScoreType;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Auth::routes();
Route::view('/', 'home')->name('home');
Route::view('/home', 'home')->name('home');
//Auth::routes();


Route::get('/login/admin', 'Auth\LoginController@showLoginForm')->name('login.admin');
Route::get('/login/superadmin', 'Auth\LoginController@showLoginForm')->name('login.superadmin');
Route::get('/login/applicant', 'Auth\LoginController@showLoginForm')->name('login.applicant');
Route::get('/login/viewer', 'Auth\LoginController@showLoginForm')->name('login.viewer');
Route::get('/login/referee', 'Auth\LoginController@showLoginForm')->name('login.referee');
Route::get('/register/admin', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/applicant', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/viewer', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/referee', 'Auth\RegisterController@showRegistrationForm');


Route::post('/login/admin', 'Auth\LoginController@doLogin');
Route::post('/login/superadmin', 'Auth\LoginController@doLogin');
Route::post('/login/applicant', 'Auth\LoginController@doLogin');
Route::post('/login/viewer', 'Auth\LoginController@doLogin');
Route::post('/login/referee', 'Auth\LoginController@doLogin');

Route::post('/register/applicant', 'Auth\RegisterController@register');
Route::post('/register/viewer', 'Auth\RegisterController@register');
Route::post('/register/referee', 'Auth\RegisterController@register');
Route::post('/register/admin', 'Auth\RegisterController@register');

//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
//Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.token');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/admin/portfolio', 'Admin\AdminController@portfolio')->name('user.admin');

Route::get('/verify-user/{email}/code/{code}', 'Auth\RegisterController@activateUser')->name('activate.user');

//permission
//
//    Route::group(['middleware' => 'auth'], function () {
//        Route::get('admin', ['middleware' => 'check-role:admin', 'uses' => 'Admin\AdminController@dashboard'])->name('user.admin');
//        Route::get('applicant', ['middleware' => 'check-role:applicant|admin', 'uses' => 'HomeController@applicant'])->name('user.applicant');
//        Route::get('viewer', ['middleware' => 'check-role:viewer|admin', 'uses' => 'HomeController@viewer'])->name('user.viewer');
//        Route::get('referee', ['middleware' => 'check-role:referee|admin', 'uses' => 'HomeController@referee'])->name('user.referee');
//    });

//

Route::resource('/admin', 'Admin\AdminController', [
    'only' => ['index'],
    'middleware' => 'check-role:admin'
]);
Route::resource('/superadmin', 'Admin\AdminController', [
    'only' => ['index'],
    'middleware' => 'check-role:superadmin'
]);

Route::resource('/viewer', 'Viewer\ViewerController', [
    'only' => ['index'],
    'middleware' => 'check-role:viewer'
]);

Route::resource('/referee', 'Referee\RefereeController', [
    'only' => ['index'],
    'middleware' => 'check-role:referee|admin|superadmin'
]);

Route::get('/referee/sign/{id}', 'Referee\RefereeController@index', [
    'only' => ['index'],
    'middleware' => 'check-role:referee|admin|superadmin'
]);


Route::resource('/applicant', 'Applicant\ApplicantController', [
    'only' => ['index'],
    'middleware' => 'check-role:applicant'
]);

Route::get('/applicant/sign/{id}', 'Applicant\ApplicantController@index', [
    'only' => ['index'],
    'middleware' => 'check-role:applicant|admin|superadmin'
]);

//base ajax
Route::post('/getcity', 'Base\AjaxController@city');
Route::post('/getsub', 'Base\AjaxController@subcategories');
Route::post('/lock', 'Admin\SettingsController@lock');
Route::post('/export', 'Admin\SettingsController@export');
Route::post('/updatePerson', 'Base\AjaxController@updatePerson');
Route::post('/updateAccount', 'Base\AjaxController@updateAccount');
Route::post('/updateAcc', 'Base\AjaxController@updateAcc');
Route::post('/updateCategory', 'Base\AjaxController@updateCategory');
Route::post('/updateCom', 'Base\AjaxController@updateCompetition');
Route::post('/updateProposal', 'Base\AjaxController@updateProposal');
Route::post('/addUsers', 'Base\AjaxController@addUsers');
Route::post('/updateProposalState', 'Base\AjaxController@updateProposalState');
Route::post('/copyItems', 'Base\AjaxController@copyItems');
Route::post('/deleteProposal', 'Base\AjaxController@deleteProposal');
Route::post('/deleteCats', 'Base\AjaxController@deleteCats');
Route::post('/deleteScores', 'Base\AjaxController@deleteScores');
Route::post('/deleteRule', 'Base\AjaxController@deleteRule');
Route::post('/deleteReport', 'Base\AjaxController@deleteReport');
Route::post('/deleteBudgets', 'Base\AjaxController@deleteBudgets');
Route::post('/duplicateCats', 'Base\AjaxController@duplicateCats');
Route::post('/sendEmail', 'Base\AjaxController@sendEmail');
Route::post('/changeState', 'Base\AjaxController@changeState');
Route::post('/getProposalByApplicant', 'Base\AjaxController@getProposalByApplicant');
Route::post('/getProposalByReferee', 'Base\AjaxController@getProposalByReferee');
Route::post('/getBudgetByCategory', 'Base\AjaxController@getBudgetByCategory');
Route::post('/getProposal', 'Base\AjaxController@getProposal');
Route::post('/getProposalByCategory', 'Base\AjaxController@getProposalByCategory');
Route::post('/getSTypeCount', 'Base\AjaxController@getSTypeCount');
Route::post('/getRR', 'Base\AjaxController@getRR');

Route::post('/approve', 'Base\AjaxController@approve');
Route::get('/ajax_report', 'Base\AjaxController@report');
Route::post('/gclfs', 'Base\AjaxController@getCompetitionsListForStatistics');
//admin


Route::post('/admin/updatePassword', 'Admin\PersonController@updatePassword');
Route::get('/admin/changePassword', 'Admin\PersonController@changePassword');
Route::get('/admin/person/disable', 'Admin\PersonController@disable');
Route::get('/admin/export', 'Admin\SettingsController@exportForm');
Route::get('/admin/sql', 'Admin\SettingsController@sql');
Route::post('/admin/backup', 'Admin\SettingsController@backup');

Route::resource('/admin/person', 'Admin\PersonController');
Route::delete('/admin/person/{id}/{type}', 'Admin\PersonController@destroy');
Route::get('/admin/account/mailreferee/{id}', 'Admin\AccountController@mailreferee');
Route::get('/admin/account/mailviewer/{id}', 'Admin\AccountController@mailviewer');
Route::resource('/admin/account', 'Admin\AccountController');

//Route::group(['middleware'=>'auth'], function () {
//    Route::get('/admin/account/create',['middleware'=>'check-role:superadmin','uses'=>'Admin\AccountController@create']);
//    Route::get('admin/category/create',['middleware'=>'check-role:superadmin','uses'=>'Admin\CategoryController@create']);
//
//});

//Route::get('/admin/account/create', 'Admin\AccountController@create', [
//    'only' => ['index'],
//    'middleware' => 'check-role:superadmin'
//]);
Route::resource('/admin/institution', 'Admin\InstitutionController');
//only superadmin
Route::resource('/admin/template', 'Admin\TemplateController');

Route::resource('/admin/message', 'Admin\MessageController');
Route::resource('/admin/category', 'Admin\CategoryController');
Route::resource('/admin/competition', 'Admin\CompetitionController');
Route::resource('/admin/degree', 'Admin\DegreeController');
Route::resource('/admin/budget', 'Admin\BudgetCategoryController');
Route::resource('/admin/score', 'Admin\ScoreTypeController');
Route::resource('/admin/rank', 'Admin\RankingRuleController');
Route::get('/admin/execute', 'Admin\RankingRuleController@execute');
Route::post('/admin/execute', 'Admin\RankingRuleController@executeQuery');
Route::resource('/admin/proposal', 'Admin\ProposalController');
Route::get('/admin/approve', 'Admin\ReportController@approve');
Route::resource('/admin/report', 'Admin\ReportController');


Route::get('/admin/show/{person}', 'Admin\AccountController@account', [
    'only' => ['index'],
    'middleware' => 'check-role:admin']);

//InvitationController
Route::get('/admin/invitation', 'Admin\InvitationController@create');
Route::get('/admin/send', 'Admin\InvitationController@send');
Route::post('/admin/invitation', 'Admin\InvitationController@store');

Route::post('/admin/password/{id}', 'Admin\AccountController@generatePassword');

//Route::resource('/admin/account/show/{id}', 'Admin\AccountController@show');

Route::resource('/admin/discipline', 'Admin\DisciplineController');
Route::resource('/admin/email', 'Admin\EmailController');
Route::resource('/admin/phone', 'Admin\PhoneController');


//applicant
Route::resource('/applicant/person', 'Applicant\PersonController');

Route::resource('/applicant/book', 'Base\BookController');
Route::resource('/applicant/meeting', 'Base\MeetingController');
Route::resource('/applicant/honors', 'Base\HonorsController');
Route::resource('/applicant/email', 'Applicant\EmailController');
Route::resource('/applicant/address', 'Applicant\AddressController');
Route::resource('/applicant/info', 'Applicant\InfoController');
Route::resource('/applicant/phone', 'Applicant\PhoneController');
Route::resource('/applicant/budgetcategories', 'Applicant\BudgetCategoriesController');
Route::resource('/applicant/publication', 'Base\PublicationsController');
Route::resource('/applicant/degree', 'Applicant\DegreePersonController');
Route::get('/applicant/institution/destroyemployment/{id}', 'Base\InstitutionController@destroyemployment');
Route::resource('/applicant/institution', 'Base\InstitutionController');
Route::resource('/applicant/account', 'Applicant\AccountController');
Route::get('/applicant/proposal/updatepersons/{id}', 'Applicant\ProposalController@updatepersons');
Route::get('/applicant/proposal/check/{id}', 'Applicant\ProposalController@check');
Route::get('/applicant/proposal/savepersons/{id}', 'Applicant\ProposalController@savepersons');
Route::get('/applicant/proposal/addperson/{id}', 'Applicant\ProposalController@addperson');

Route::resource('/applicant/discipline', 'Applicant\DisciplineController');
Route::post('/applicant/updatePassword', 'Applicant\PersonController@updatePassword');
Route::get('/applicant/changePassword', 'Applicant\PersonController@changePassword');
Route::get('/applicant/person/delete/{id}', 'Applicant\PersonController@destroy');
Route::get('/applicant/email/create/{id}', 'Applicant\EmailController@create');
Route::get('/applicant/phone/create/{id}', 'Applicant\PhoneController@create');
Route::get('/applicant/address/create/{id}', 'Applicant\AddressController@create');
Route::get('/applicant/address/delete/{id}', 'Applicant\AddressController@destroy');
Route::get('/applicant/email/delete/{id}', 'Applicant\EmailController@destroy');
Route::get('/applicant/phone/delete/{id}', 'Applicant\PhoneController@destroy');
Route::get('/applicant/institution/create/{id}', 'Base\InstitutionController@create');
Route::get('/applicant/degree/create/{id}', 'Applicant\DegreePersonController@create');
Route::get('/applicant/degree/delete/{id}', 'Applicant\DegreePersonController@destroy');
Route::get('/applicant/discipline/create/{id}', 'Applicant\DisciplineController@create');
Route::get('/applicant/discipline/delete/{id}', 'Applicant\DisciplineController@destroy');
Route::get('/applicant/instructions/{id}', 'Applicant\ProposalController@instructions');

Route::get('/applicant/budgetcategories/create/{id}', 'Applicant\BudgetCategoriesController@create');
Route::get('/applicant/budgetcategories/delete/{id}', 'Applicant\BudgetCategoriesController@destroy');

Route::get('/applicant/book/create/{id}', 'Base\BookController@create');
Route::get('/applicant/book/delete/{id}', 'Base\BookController@destroy');
Route::get('/applicant/publications/create/{id}', 'Base\PublicationsController@create');
Route::get('/applicant/publications/delete/{id}', 'Base\PublicationsController@destroy');
Route::get('/applicant/meeting/create/{id}', 'Base\MeetingController@create');
Route::get('/applicant/meeting/delete/{id}', 'Base\MeetingController@destroy');
Route::get('/applicant/honors/create/{id}', 'Base\HonorsController@create');
Route::get('/applicant/honors/delete/{id}', 'Base\HonorsController@destroy');

Route::get('/applicant/download/{id}', 'Applicant\PersonController@download');

/*File Upload routes*/
Route::get('file-upload/{id}', 'Applicant\FileUploadController@index');
Route::post('file-upload/upload', 'Applicant\FileUploadController@upload')->name('upload');
Route::post('file-upload/uploadreport', 'Applicant\FileUploadController@uploadreport')->name('uploadreport');
Route::get('file-upload/remove/{id}', 'Applicant\FileUploadController@remove');
Route::get('file-upload/removereport/{id}', 'Applicant\FileUploadController@removereport');

/*File Upload routs*/


// Proposal
Route::resource('/applicant/proposal', 'Applicant\ProposalController');
Route::get('/applicant/activeProposal', 'Applicant\ProposalController@activeProposal');
Route::get('/applicant/pastProposal', 'Applicant\ProposalController@pastProposal');
Route::get('/applicant/downloadPDF', 'Applicant\ProposalController@downloadPDF');
Route::get('/applicant/proposal/delete/{id}', 'Applicant\ProposalController@destroy');
Route::get('/applicant/researchboard/{type}', 'Applicant\ResearchBoardController@index');
Route::post('/applicant/send', 'Applicant\ResearchBoardController@send');
Route::post('/applicant/sendtoadmin', 'Applicant\ResearchBoardController@sendtoadmin');
Route::get('/applicant/proposal/generatePDF/{id}', 'Applicant\ProposalController@generatePDF');
Route::get('/applicant/person/download/{id}', 'Applicant\PersonController@download');

Route::get('/support/{prop_id}/{person_id}', 'Applicant\SupportController@index');
Route::post('/support/save/{person_id}', 'Applicant\SupportController@save');

//Viewer
Route::resource('/viewer/person', 'Viewer\PersonController');
Route::resource('/viewer/proposal', 'Viewer\ProposalController');
Route::post('/getProposalByCompByID', 'Viewer\ProposalController@getProposalByCompByID');
Route::get('/viewer/proposal/generatePDF/{id}', 'Viewer\ProposalController@generatePDF');
Route::resource('/viewer/statistics', 'Viewer\StatisticsController');
Route::post('/viewer/statistics/chart','Viewer\StatisticsController@chart');
Route::post('/viewer/statistics/y_result','Viewer\StatisticsController@y_result');

Route::get('/viewer/show/{id}', 'Viewer\PersonController@show');
Route::post('/gccbi', 'Base\AjaxController@getCompetitionContentByID');


Route::resource('/referee/person', 'Referee\PersonController');
Route::post('/referee/updatePassword', 'Referee\PersonController@updatePassword');
Route::get('/referee/changePassword', 'Referee\PersonController@changePassword');
Route::resource('/referee/reports', 'Referee\ReportController');
Route::get('/referee/report/{state}', 'Referee\ReportController@state');
Route::get('/referee/generatePDF/{id}', 'Referee\ReportController@generatePDF');
Route::get('/referee/sendEmail/{id}', 'Referee\SendEmailController@showEmail');
Route::post('/referee/send/{id}', 'Referee\SendEmailController@sendEmail');

Route::get('/admin/migrate', function() {
    // Create degrees
    Degree::create(['text' => 'None']);
    Degree::create(['text' => 'High school']);
    Degree::create(['text' => 'Bachelor (college)']);
    Degree::create(['text' => 'Masters']);
    Degree::create(['text' => 'Doctoral']);
    Degree::create(['text' => 'Post-doctoral']);

    $mindegree = Degree::where('text', '=', 'High school')->first();
    $nodegree = Degree::where('text', '=', 'None')->first();
    $maxdegree = Degree::where('text', '=', 'Doctoral')->first();

    // Read roles
    $applicant_role = Role::where('name', '=', 'applicant')->first();
    $admin_role = Role::where('name', '=', 'admin')->first();
    $referee_role = Role::where('name', '=', 'referee')->first();

    // Migrate categories
    $categories = DB::connection('mysqlold')->table('categories')
        ->get()->keyBy('id');
    $subcategories = DB::connection('mysqlold')->table('subcategories')
        ->get()->keyBy('id');

    foreach($categories as $category) {
        Category::create([
            'abbreviation' => $category->label,
            'title' => $category->description,
            'weight' => 1
            ]);
    }

    foreach ($subcategories as $subcategory) {
        $parentcategory = $categories[$subcategory->category_id];
        $pc = Category::where('abbreviation','=', $parentcategory->label)->first();
        Category::create([
            'abbreviation' => $subcategory->label,
            'title' => $subcategory->description,
            'weight' => 1,
            'parent_id' => $pc->id
        ]);
    }

    // Migrate Institutions
    $affiliations = DB::connection('mysqlold')->table('affiliations')
        ->get()->keyBy('id');
    foreach($affiliations as $affiliation){
        $address = Address::create([
            'country_id' => 8,
            'province' => '',
            'street' => '',
            'addressable_type' => 'App\Models\Institution',
            'city' => ''
        ]);
        $i = Institution::create([
            'content' => $affiliation->institution,
            'address_id' => $address->id
        ]);
        $address->addressable_id = $i->id;
        $address->save();
    }

    $accounts = DB::connection('mysqlold')->table('accounts')->get();

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


    // Process proposals
    foreach($proposals as $proposal) {
        // Add competition
        $propyear = date('Y', strtotime($p->date));
        $compyear = $propyear + 1;

        $additional['additional_charge_name'] = '';
        $additional['additional_charge'] = 0;
        $additional['additional_percentage_name'] = 'Percentage overhead';
        $additional['additional_percentage'] = 5;

        $maincategories = Category::where('parent_id','=',null);
        $compcategories = '[';
        foreach($maincategories as $mc) {
            $compcategories .= ('"' . $mc->abbreviation . '",');
        }
        $compcategories = substr($compcategories, 0, -1) . "]";
        $competition = Competition::firstOrCreate(
            ['created_at' => (date($propyear . '-05-01'))],
            [
                'title' => $compyear . 'ANSEF',
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
                'allow_foreign' => false,
                'first_report' => $compyear . '-06-01',
                'second_report' => $compyear . '-11-01',
                'state' => 'enabled',
                'recommendations' => 0,
                'categories' => $compcategories,
                'additional' => json_encode($additional),
                'updated_at' => date($propyear . '-05-01'),
                'instructions' => 'Traditional ANSEF competition'
            ]
        );
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

        // Add budget categories
        foreach($expense_types as $expense_type) {
            BudgetCategory::firstOrCreate([], [
                'name' => $expense_type->label,
                'min' => 0,
                'max' => 5000,
                'weight' => 1,
                'competition_id' => $competition->id,
                'comments' => ''
            ]);
        }

        // Add user and person for pi
        $investigator = $investigators[$proposal->investigator_id];
        $account = $accounts[$investigator->account_id];
        $generate_password = randomPassword();
        $user = User::firstOrCreate([
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
        ]);

        Person::firstOrCreate([
            'user_id' => $user->id,
            'type' => null
        ],
        [
            'birthdate' => date('Y-m-d', $investigator->birthdate),
            'birthplace' => ucfirst($investigator->birthplace),
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => $investigator->first_name,
            'last_name' => $investigator->last_name,
            'nationality' => ucfirst($investigator->nationality),
            'type' => null,
            'specialization' => ($investigator->primary_specialization . ", " . $investigator->secondary_specialization),
            'user_id' => $user->id
        ]);

        $pi = Person::create([
            'birthdate' => date('Y-m-d', $investigator->birthdate),
            'birthplace' => ucfirst($investigator->birthplace),
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => $investigator->first_name,
            'last_name' => $investigator->last_name,
            'nationality' => ucfirst($investigator->nationality),
            'type' => 'participant',
            'specialization' => ($investigator->primary_specialization . ", " . $investigator->secondary_specialization),
            'user_id' => $user->id
        ]);

        Phone::create([
            "person_id" => $pi->id,
            "country_code" => 0,
            "number" => $pi->phone
        ]);

        Email::create([
            "person_id" => $pi->id,
            "email" => $pi->email
        ]);

        Address::create([
            'country_id' => 8,
            'province' => '',
            'street' =>  $pi->address,
            'addressable_id' => $pi->id,
            'addressable_type' => 'App\Models\Person',
            'city' => ''
        ]);

        // Add pi CV data
        $honors = DB::connection('mysqlold')->table('honors')
                ->where('investigator_id','=',$investigator->id)->get();
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
                'description' => $honor->hon_title,
                'year' => $honor->hon_year,
                'person_id' => $pi->id
            ]);
        }
        foreach ($grants as $grant) {
            Honors::create([
                'description' => $grant->grant_title . ", " . $grant->grant_type,
                'year' => $grant->grant_year,
                'person_id' => $pi->id
            ]);
        }
        foreach ($employments as $employment) {
            InstitutionPerson::create([
                'person_id' => $pi->id,
                'institution_id' => 0,
                'institution' => '',
                'title' => $employment->employment_position,
                'start' => date($employment->employment_start_year . '07-01'),
                'end' => date($employment->employment_end_year . '07-01'),
                'type' => 'employment'
            ]);
        }
        foreach ($degrees as $degree) {
            DegreePerson::create([
                'person_id' => $pi->id,
                'degree_id' => $maxdegree->id,
                'year' => $degree->degree_year,
                'institution_id' => 0,
                'institution' => $degree->degree_institution
            ]);
        }
        foreach ($publications as $publication) {
            Publications::create([
                'person_id' => $pi->id,
                'journal' => $publication->publication_reference,
                'title' => $publication->publication_title,
                'year' => $publication->publication_year,
                'domestic' => '0',
                'ansef_supported' => $publication->publication_ansef
            ]);
        }
        foreach ($ansefpublications as $ansefpublication) {
            Publications::create([
                'person_id' => $pi->id,
                'journal' => $ansefpublication->reference . ", " . $ansefpublication->authors . ": " . $ansefpublication->link,
                'title' => $ansefpublication->title,
                'year' => 0,
                'domestic' => '0',
                'ansef_supported' => '1'
            ]);
        }

        // Add director person
        $director = Person::create([
            'birthdate' => null,
            'birthplace' => '',
            'sex' => 'neutral',
            'state' => 'domestic',
            'first_name' => $proposal->director_first_name,
            'last_name' => $proposal->director_last_name,
            'nationality' => '',
            'type' => 'support',
            'specialization' => '',
            'user_id' => $user->id
        ]);

        // Add user and person for admin
        $propadmin = $administrators[$proposal->administrator_id];
        $adminaccount = $accounts[$propadmin->account_id];
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
        $administrator = Person::firstOrCreate([
            'first_name' => $propadmin->first_name,
            'last_name' => $propadmin->last_name
        ],
        [
            'birthdate' => null,
            'birthplace' => '',
            'sex' => 'neutral',
            'state' => 'foreign',
            'nationality' => '',
            'type' => 'admin',
            'specialization' => ($investigator->primary_specialization . ", " . $investigator->secondary_specialization),
            'user_id' => $adminuser->id
        ]);

        // Add proposal
        $state = 'unsuccessfull';
        if ($proposal->awardee == 1) $state = 'approved 2';

        $psc = Category::where('abbreviation', '=', $subcategories[$proposal->subcategory_id]->label)->get();
        $pssc = Category::where('abbreviation', '=', $subcategories[$proposal->secondary_subcategory_id]->label)->get();
        $pc = Category::find($psc->parent_id);
        $psc = Category::find($pssc->parent_id);
        $cat["parent"] = $pc->id;
        $cat['sub'] = $psc->id;
        $cat["sec_parent"] = $sc->id;
        $cat["sec_sub"] = $pssc->id;

        $p = Proposal::create([
            'title' => $proposal->title,
            'abstract' => $proposal->abstract,
            'document' => $proposal->document_full_url,
            'overall_score' => $proposal->score,
            'state' => $state,
            'comment' => '' . $proposal->id,
            'rank' => '',
            'competition_id' => $competition->id,
            'categories' => json_encode($cat),
            'proposal_admins' => $administrator->id,
            'user_id' => $user->id,
            'created_at' => $proposal->date,
            'updated_at' => $proposal->date
        ]);

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

        // Add collaborators
        $collaborators = DB::connection('mysqlold')->table('collaborators')
            ->where('proposal_id', '=', $proposal->id)->get();
        foreach($collaborators as $collaborator) {
            $per = Person::create([
                'birthdate' => date('Y-m-d', $collaborator->birthdate),
                'birthplace' => '',
                'sex' => 'neutral',
                'state' => 'domestic',
                'first_name' => $collaborator->first_name,
                'last_name' => $collaborator->last_name,
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

        // Add referee reports
        $reports = DB::connection('mysqlold')->table('reports')
            ->where('proposal_id', '=', $proposal->id)->get();

        foreach($reports as $report) {
            $referee = $referees[$report->referee_id];
            $refaccount = $accounts[$referee->account_id];
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
            $ref = Person::firstOrCreate(
                [
                    'first_name' => $referee->first_name,
                    'last_name' => $referee->last_name
                ],
                [
                    'birthdate' => null,
                    'birthplace' => '',
                    'sex' => 'neutral',
                    'state' => 'foreign',
                    'nationality' => '',
                    'type' => 'referee',
                    'specialization' => $referee->comments,
                    'user_id' => $refuser->id
                ]
            );

            $rep = RefereeReport::create([
                "private_comment" => $report->private_comments,
                "public_comment" => $report->public_comments,
                "state" => 'complete',
                "proposal_id" => $p->id,
                "due_date" => date('Y-m-d', $compyear . "-12-30"),
                "overall_score" => $report->score,
                "referee_id" => $ref->id
            ]);

            Score::create([
                'score_type_id' => $scoretype['Significance'],
                'value' => $rep->significance,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Approach'],
                'value' => $rep->approach,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Innovation'],
                'value' => $rep->innovation,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Investigator'],
                'value' => $rep->investigator,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Budget'],
                'value' => $rep->budget,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['Proposal'],
                'value' => $rep->proposal_score,
                'report_id' => $rep->id
            ]);
            Score::create([
                'score_type_id' => $scoretype['OverallScore'],
                'value' => $rep->score,
                'report_id' => $rep->id
            ]);
        }

        // Add budget items
        $budget_items = DB::connection('mysqlold')->table('budget_items')
            ->where('proposal_id', '=', $proposal->id)->get();
        foreach ($budget_items as $budget_item) {
            $budcat = BudgetCategory::where('name','=',$budget_item->expense_type)->first();
            BudgetItem::create([
                'budget_cat_id' => $budcat->id,
                'description' => $budget_item->detail,
                'amount' => $budget_item->amount,
                'proposal_id' => $p->id
            ]);
        }
    }

    // Add proposal reports
    $awards = DB::connection('mysqlold')->table('awards')->get();

    foreach($awards as $award) {
        $pp = Proposal::where('comment','=',''. $award->proposal_id);
        $compyear = date('Y', strtotime($pp->date))+1;
        ProposalReports::create([
            'description' => 'Midterm report',
            'document' => $award->midterm_full_url,
            'proposal_id' => $pp->id,
            'due_date' => date('Y-m-d', $compyear . '-07-01'),
            'approved' => '1'
        ]);
        ProposalReports::create([
            'description' => 'Final report',
            'document' => $award->final_full_url,
            'proposal_id' => $pp->id,
            'due_date' => date('Y-m-d', $compyear . '-12-15'),
            'approved' => '1'
        ]);
    }

    return  'Proposals: ' . count($proposals) . '; generated: ' . Proposal::all()->count()
            . '<br/>Accounts:' . count($accounts)
            . '<br/>Investigators:' . count($investigators)
            . '<br/>Administrators:' . count($administrators)
            . '<br/>Referees:' . count($referees);
});
