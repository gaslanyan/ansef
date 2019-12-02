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
Route::post('/addUsers', 'Admin\ProposalController@addUsers');
Route::post('/updateProposalState', 'Base\AjaxController@updateProposalState');
Route::post('/copyItems', 'Base\AjaxController@copyItems');
Route::post('/deleteProposal', 'Admin\ProposalController@deleteProposal');
Route::post('/deleteCats', 'Base\AjaxController@deleteCats');
Route::post('/deleteScores', 'Base\AjaxController@deleteScores');
Route::post('/deleteRule', 'Base\AjaxController@deleteRule');
Route::post('/deleteReport', 'Admin\ReportController@deleteReport');
Route::post('/deleteBudgets', 'Base\AjaxController@deleteBudgets');
Route::post('/duplicateCats', 'Base\AjaxController@duplicateCats');
Route::post('/sendEmail', 'Admin\ProposalController@sendEmail');
Route::post('/changeState', 'Admin\ProposalController@changeState');
Route::post('/getProposalByApplicant', 'Base\AjaxController@getProposalByApplicant');
Route::post('/getProposalByReferee', 'Base\AjaxController@getProposalByReferee');
Route::post('/getBudgetByCategory', 'Base\AjaxController@getBudgetByCategory');
Route::post('/getProposal', 'Base\AjaxController@getProposal');
Route::post('/getProposalByCategory', 'Base\AjaxController@getProposalByCategory');
Route::post('/getSTypeCount', 'Base\AjaxController@getSTypeCount');
Route::post('/getRR', 'Base\AjaxController@getRR');

Route::post('/approve', 'Base\AjaxController@approve');
Route::get('/ajax_report/{id}', 'Admin\ReportController@listreports');
Route::get('/ajax_proposal/{id}', 'Admin\ProposalController@listproposals');
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
Route::post('/applicant/proposal/addperson/{id}', 'Applicant\ProposalController@addperson');

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
Route::get('/admin/migrate', 'JobsController@migrate');
Route::get('/admin/dochunk/{id}', 'JobsController@dochunk');
