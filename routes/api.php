<?php

use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$basePathController = 'App\Http\Controllers\\';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () use ($basePathController) {
    Route::post('sign-in', $basePathController.'AuthController@signIn')->name('auth.sign-in');
    Route::post('check-auth', $basePathController.'AuthController@checkAuth')->name('auth.check-auth')->middleware(['api_access']);
    Route::post('forgot-password', $basePathController.'AuthController@forgotPassword')->name('passwords.sent');
    Route::post('check-password-reset-token', $basePathController.'AuthController@checkPasswordResetToken');
    Route::post('reset-password', $basePathController.'AuthController@resetPassword');
});






Route::get('get-countries', $basePathController.'CountriesController@index');
Route::get('get-all-roles', $basePathController.'EntityPropertiesController@getAllRoles');

Route::get('test', $basePathController.'TestController@index');


Route::get('properties/{id}', $basePathController.'PropertiesController@show');
Route::group(['middleware' => ['api_access']], function () use ($basePathController) {
    Route::get('users/resend-signup-email/{id}', $basePathController.'UsersController@resendSignUpEmail');
    Route::resource('users', $basePathController.'UsersController');
    Route::post('students/register-payment/{id}', $basePathController.'StudentsController@registerPayment');
    Route::get('students/payment-control/{studentID}/{year}', $basePathController.'StudentsController@getPaymentControl');
    Route::resource('students', $basePathController.'StudentsController');

    Route::get('get-all-levels', $basePathController.'LevelsController@getAll');
});


Route::post('payment-control/update-month-price', $basePathController.'PaymentsController@updateMonthPrice');
Route::get('payment-control/get-years', $basePathController.'PaymentsController@getYears');
Route::get('payment-control/get-month-price', $basePathController.'PaymentsController@getMonthPrice');
Route::get('payment-control/get-payments-summary-by-year/{year}', $basePathController.'PaymentsController@getPaymentsSummaryByYear');
Route::delete('payment-control/{id}', $basePathController.'PaymentsController@destroy');
Route::get('payment-control/get-payments-by-month/{year}/{month}', $basePathController.'PaymentsController@getPaymentByMonth');

Route::post('users/complete-signup/{token}', $basePathController.'UsersController@completeSignUp');

Route::get('brokers/get-all', $basePathController.'BrokersController@getAll');

Route::get('dolar-bcv', $basePathController.'ProceduresController@dolarBCV');


Route::get('suma', $basePathController.'SumaController@suma');
