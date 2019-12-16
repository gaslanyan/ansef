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
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

// ---------------------------------- Authentication routes ----------------------------------

Auth::routes();
Route::view('/', 'home')->name('home');
Route::view('/home', 'home')->name('home');

Route::get('/login/admin', 'Auth\LoginController@showLoginForm')->name('login.admin');
Route::get('/login/superadmin', 'Auth\LoginController@showLoginForm')->name('login.superadmin');
Route::get('/login/applicant', 'Auth\LoginController@showLoginForm')->name('login.applicant');
Route::get('/login/viewer', 'Auth\LoginController@showLoginForm')->name('login.viewer');
Route::get('/login/referee', 'Auth\LoginController@showLoginForm')->name('login.referee');
Route::post('/login/admin', 'Auth\LoginController@doLogin');
Route::post('/login/superadmin', 'Auth\LoginController@doLogin');
Route::post('/login/applicant', 'Auth\LoginController@doLogin');
Route::post('/login/viewer', 'Auth\LoginController@doLogin');
Route::post('/login/referee', 'Auth\LoginController@doLogin');

Route::get('/register/admin', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/applicant', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/viewer', 'Auth\RegisterController@showRegistrationForm');
Route::get('/register/referee', 'Auth\RegisterController@showRegistrationForm');
Route::post('/register/applicant', 'Auth\RegisterController@register');
Route::post('/register/viewer', 'Auth\RegisterController@register');
Route::post('/register/referee', 'Auth\RegisterController@register');
Route::post('/register/admin', 'Auth\RegisterController@register');

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/verify-user/{email}/code/{code}', 'Auth\RegisterController@activateUser')->name('activate.user');

Route::resource('/admin', 'Admin\AdminController', [
    'only' => ['index'],
    'middleware' => 'check-role:admin'
]);
Route::resource('/superadmin', 'Admin\AdminController', [
    'only' => ['index'],
    'middleware' => 'check-role:superadmin'
]);
Route::resource('/applicant', 'Applicant\ApplicantController', [
    'only' => ['index'],
    'middleware' => 'check-role:applicant'
]);
Route::resource('/referee', 'Referee\RefereeController', [
    'only' => ['index'],
    'middleware' => 'check-role:referee|admin|superadmin'
]);
Route::resource('/viewer', 'Viewer\ViewerController', [
    'only' => ['index'],
    'middleware' => 'check-role:viewer'
]);

Route::get('/applicant/sign', 'Applicant\ApplicantController@index', [
    'only' => ['index'],
    'middleware' => 'check-role:applicant|admin|superadmin'
]);
Route::get('/referee/sign', 'Referee\RefereeController@index', [
    'only' => ['index'],
    'middleware' => 'check-role:referee|admin|superadmin'
]);


// ---------------------------------- Admin routes ----------------------------------
   Route::group(['middleware' => ['check-role:admin|superadmin']], function () {
    Route::get('/admin/portfolio', 'Admin\AdminController@portfolio')->name('user.admin');
    Route::get('/admin/export', 'Admin\SettingsController@exportForm');
    Route::get('/admin/sql', 'Admin\SettingsController@sql');
    Route::post('/admin/backup', 'Admin\SettingsController@backup');
    Route::get('/admin/profile/edit', 'Admin\PersonController@edit');
    Route::post('/admin/profile/update', 'Admin\PersonController@update');
    Route::post('/admin/password/update', 'Admin\PersonController@updatePassword');
    Route::get('/admin/password/change', 'Admin\PersonController@changePassword');
    Route::delete('/admin/person/delete/{id}/{type}', 'Admin\PersonController@destroy');
    Route::get('/admin/person/disable', 'Admin\PersonController@disable');
    Route::get('/admin/account/mailreferee/{id}', 'Admin\AccountController@mailreferee');
    Route::get('/admin/account/mailviewer/{id}', 'Admin\AccountController@mailviewer');
    Route::resource('/admin/account', 'Admin\AccountController');
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
    Route::get('/admin/proposal/list/{id}', 'Admin\ProposalController@list')->name('proposal_list');
    Route::post('/admin/proposal/display', 'Admin\ProposalController@display');
    Route::resource('/admin/proposal', 'Admin\ProposalController');
    Route::get('/admin/approve', 'Admin\ReportController@approve');
    Route::get('/admin/report/list/{id}', 'Admin\ReportController@list')->name('report_list');
    Route::resource('/admin/report', 'Admin\ReportController');

    Route::get('/admin/show/{person}', 'Admin\AccountController@account', [
        'only' => ['index'],
        'middleware' => 'check-role:admin'
    ]);

    Route::get('/admin/invitation', 'Admin\InvitationController@create');
    Route::get('/admin/send', 'Admin\InvitationController@send');
    Route::post('/admin/invitation', 'Admin\InvitationController@store');
    Route::post('/admin/password/{id}', 'Admin\AccountController@generatePassword');
    Route::resource('/admin/email', 'Admin\EmailController');
    Route::resource('/admin/phone', 'Admin\PhoneController');
    Route::post('/admin/lock', 'Admin\SettingsController@lock');
    Route::post('/export', 'Admin\SettingsController@export');
    Route::post('/admin/updatePerson', 'Admin\PersonController@updatePerson');
    Route::post('/admin/updateAcc', 'Admin\AccountController@updateAcc');

    Route::post('/admin/addUsers', 'Admin\ProposalController@addUsers');
    Route::post('/admin/deleteProposal', 'Admin\ProposalController@deleteProposal');
    Route::post('/admin/checkProposal', 'Admin\ProposalController@checkProposal');
    Route::post('/admin/deleteReport', 'Admin\ReportController@deleteReport');
    Route::post('/admin/sendEmail', 'Admin\ProposalController@sendEmail');
    Route::post('/admin/changeState', 'Admin\ProposalController@changeState');
    Route::get('/admin/listreports/{id}', 'Admin\ReportController@listreports');
    Route::get('/admin/listproposals/{id}', 'Admin\ProposalController@listproposals');
    Route::post('/admin/updateCom', 'Admin\CompetitionController@updateCompetition');

    Route::get('/admin/migrate', 'JobsController@migrate');
    Route::get('/admin/dochunk/{id}', 'JobsController@dochunk');
});

// ---------------------------------- Applicant routes ----------------------------------
Route::group(['middleware' => ['check-role:applicant|admin|superadmin']], function () {
    Route::resource('/applicant/person', 'Applicant\PersonController');
    Route::resource('/applicant/book', 'Applicant\BookController');
    Route::resource('/applicant/meeting', 'Applicant\MeetingController');
    Route::resource('/applicant/honors', 'Applicant\HonorsController');
    Route::resource('/applicant/email', 'Applicant\EmailController');
    Route::resource('/applicant/address', 'Applicant\AddressController');
    Route::resource('/applicant/phone', 'Applicant\PhoneController');
    Route::resource('/applicant/budgetcategories', 'Applicant\BudgetCategoriesController');
    Route::resource('/applicant/publication', 'Applicant\PublicationsController');
    Route::resource('/applicant/degree', 'Applicant\DegreePersonController');

    Route::get('/applicant/institution/destroyemployment/{id}', 'Applicant\InstitutionController@destroyemployment');
    Route::resource('/applicant/institution', 'Applicant\InstitutionController');
    Route::resource('/applicant/account', 'Applicant\AccountController');

    Route::get('/applicant/proposal/updatepersons/{id}', 'Applicant\ProposalController@updatepersons');
    Route::get('/applicant/proposal/check/{id}', 'Applicant\ProposalController@check');
    Route::get('/applicant/proposal/savepersons/{id}', 'Applicant\ProposalController@savepersons');
    Route::post('/applicant/proposal/addperson/{id}', 'Applicant\ProposalController@addperson');
    Route::get('/applicant/proposal/removeperson/{id}', 'Applicant\ProposalController@removeperson');
    Route::get('/applicant/proposal/emailrecommenders/{id}', 'Applicant\ProposalController@notifyrecommenders');

    Route::post('/applicant/updatePassword', 'Applicant\PersonController@updatePassword');
    Route::get('/applicant/changePassword', 'Applicant\PersonController@changePassword');
    Route::get('/applicant/person/delete/{id}', 'Applicant\PersonController@destroy');
    Route::get('/applicant/email/create/{id}', 'Applicant\EmailController@create');
    Route::get('/applicant/phone/create/{id}', 'Applicant\PhoneController@create');
    Route::get('/applicant/address/create/{id}', 'Applicant\AddressController@create');
    Route::get('/applicant/address/delete/{id}', 'Applicant\AddressController@destroy');
    Route::get('/applicant/email/delete/{id}', 'Applicant\EmailController@destroy');
    Route::get('/applicant/phone/delete/{id}', 'Applicant\PhoneController@destroy');
    Route::get('/applicant/institution/create/{id}', 'Applicant\InstitutionController@create');
    Route::get('/applicant/degree/create/{id}', 'Applicant\DegreePersonController@create');
    Route::get('/applicant/degree/delete/{id}', 'Applicant\DegreePersonController@destroy');
    Route::get('/applicant/instructions/{id}', 'Applicant\ProposalController@instructions');

    Route::get('/applicant/budgetcategories/create/{id}', 'Applicant\BudgetCategoriesController@create');
    Route::get('/applicant/budgetcategories/delete/{id}', 'Applicant\BudgetCategoriesController@destroy');

    Route::get('/applicant/book/create/{id}', 'Applicant\BookController@create');
    Route::get('/applicant/book/delete/{id}', 'Applicant\BookController@destroy');
    Route::get('/applicant/publications/create/{id}', 'Applicant\PublicationsController@create');
    Route::get('/applicant/publications/delete/{id}', 'Applicant\PublicationsController@destroy');
    Route::get('/applicant/meeting/create/{id}', 'Applicant\MeetingController@create');
    Route::get('/applicant/meeting/delete/{id}', 'Applicant\MeetingController@destroy');
    Route::get('/applicant/honors/create/{id}', 'Applicant\HonorsController@create');
    Route::get('/applicant/honors/delete/{id}', 'Applicant\HonorsController@destroy');

    Route::resource('/applicant/proposal', 'Applicant\ProposalController');
    Route::get('/applicant/activeProposal', 'Applicant\ProposalController@activeProposal')->name('currentproposals');
    Route::get('/applicant/pastProposal', 'Applicant\ProposalController@pastProposal')->name('pastproposals');
    Route::get('/applicant/downloadPDF', 'Applicant\ProposalController@downloadPDF');
    Route::get('/applicant/proposal/delete/{id}', 'Applicant\ProposalController@destroy');
    Route::get('/applicant/researchboard/{type}', 'Applicant\ResearchBoardController@index');
    Route::post('/applicant/send', 'Applicant\ResearchBoardController@send');
    Route::post('/applicant/sendtoadmin', 'Applicant\ResearchBoardController@sendtoadmin');
    Route::get('/applicant/proposal/generatePDF/{id}', 'Applicant\ProposalController@generatePDF');
    Route::get('/applicant/person/download/{id}', 'Applicant\PersonController@download');
    Route::post('/applicant/getsub', 'Applicant\ProposalController@subcategories');

    Route::get('file-upload/{id}', 'Applicant\FileUploadController@docfile');
    Route::post('file-upload/upload', 'Applicant\FileUploadController@upload')->name('upload');
    Route::get('file-upload/remove/{uuid}', 'Applicant\FileUploadController@remove')->name('deletefile');
    Route::get('file-upload/download/{uuid}', 'Applicant\FileUploadController@downloadfile')->name('download');

    Route::get('report-upload/{id}', 'Applicant\FileUploadController@reportfile');
    Route::post('report-upload/upload', 'Applicant\FileUploadController@uploadreport')->name('uploadreport');
    Route::get('report-upload/remove/{uuid}', 'Applicant\FileUploadController@removereport')->name('deletereport');
    Route::get('report-upload/download/{uuid}', 'Applicant\FileUploadController@downloadreport')->name('downloadreport');

    Route::get('/support/{prop_id}/{person_id}', 'Applicant\SupportController@index');
    Route::post('/support/save/{person_id}', 'Applicant\SupportController@save');
});

// ---------------------------------- Referee routes ----------------------------------
Route::group(['middleware' => ['check-role:referee|admin|superadmin']], function () {
    Route::get('/referee/person/{id}', 'Referee\PersonController@edit');
    Route::post('/referee/update/{id}', 'Referee\PersonController@update');
    Route::post('/referee/updatePassword', 'Referee\PersonController@updatePassword');
    Route::get('/referee/changePassword', 'Referee\PersonController@changePassword');
    Route::resource('/referee/reports', 'Referee\ReportController');
    Route::get('/referee/report/{state}', 'Referee\ReportController@state');
    Route::get('/referee/generatePDF/{id}', 'Referee\ReportController@generatePDF');
    Route::get('/referee/sendEmail/{id}', 'Referee\SendEmailController@showEmail');
    Route::get('/referee/sendRejectedEmail/{id}', 'Referee\SendEmailController@showRejectedEmail');
    Route::post('/referee/send/{id}', 'Referee\SendEmailController@sendEmail');
});

// ---------------------------------- Viewer routes ----------------------------------
Route::group(['middleware' => ['check-role:viewer|admin|superadmin']], function () {
    //Viewer
    Route::resource('/viewer/person', 'Viewer\PersonController');
    Route::resource('/viewer/proposal', 'Viewer\ProposalController');
    Route::get('/viewer/proposal/generatePDF/{id}', 'Viewer\ProposalController@generatePDF');
    Route::resource('/viewer/statistics', 'Viewer\StatisticsController');
    Route::post('/viewer/statistics/chart', 'Viewer\StatisticsController@chart');
    Route::post('/viewer/statistics/y_result', 'Viewer\StatisticsController@y_result');

    Route::get('/viewer/show/{id}', 'Viewer\PersonController@show');
    Route::post('/viewer/getProposalByCompByID', 'Viewer\ProposalController@getProposalByCompByID');
});

Route::post('letter-upload/upload', 'Applicant\FileUploadController@uploadletter')->name('uploadletter');
Route::get('letter-upload', 'Applicant\FileUploadController@letterfile')->name('submit-letter');
Route::get('letter-upload/remove/{uuid}', 'Applicant\FileUploadController@removeletter')->name('deleteletter');
Route::get('letter-upload/download/{uuid}', 'Applicant\FileUploadController@downloadletter')->name('downloadletter');
Route::get('letter-upload/done', function () {
    return 'Thanks';
})->name('uploadthanks');

//base ajax

Route::post('/admin/updateCategory', 'Admin\CategoryController@updateCategory');
Route::post('/admin/copyItems', 'Base\AjaxController@copyItems');

Route::post('/deleteCats', 'Base\AjaxController@deleteCats');
Route::post('/deleteScores', 'Base\AjaxController@deleteScores');
Route::post('/deleteRule', 'Base\AjaxController@deleteRule');
Route::post('/deleteBudgets', 'Base\AjaxController@deleteBudgets');
Route::post('/duplicateCats', 'Base\AjaxController@duplicateCats');

Route::post('/getProposalByApplicant', 'Base\AjaxController@getProposalByApplicant');
Route::post('/getProposalByReferee', 'Base\AjaxController@getProposalByReferee');
Route::post('/getBudgetByCategory', 'Base\AjaxController@getBudgetByCategory');
Route::post('/getProposal', 'Base\AjaxController@getProposal');
Route::post('/getProposalByCategory', 'Base\AjaxController@getProposalByCategory');
Route::post('/getSTypeCount', 'Base\AjaxController@getSTypeCount');
Route::post('/getRR', 'Base\AjaxController@getRR');
Route::post('/approve', 'Base\AjaxController@approve');
Route::post('/gclfs', 'Base\AjaxController@getCompetitionsListForStatistics');
Route::post('/gccbi', 'Base\AjaxController@getCompetitionContentByID');
