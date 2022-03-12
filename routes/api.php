<?php

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


//Auth routes
Route::post('login', 'App\Http\Controllers\API\AuthController@login');
Route::post('register', 'App\Http\Controllers\API\AuthController@register');

Route::middleware('auth:api')->group(function(){
    Route::post('loan_store', 'App\Http\Controllers\API\LoanDetailController@loanStore');
    Route::post('approval_loan_by_admin', 'App\Http\Controllers\API\LoanDetailController@approvalLoanByAdmin');
    Route::post('paid_loan', 'App\Http\Controllers\API\LoanDetailController@paidLoan');
    Route::get('show_loan_details/{id?}', 'App\Http\Controllers\API\LoanDetailController@showLoanDetails');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');

});
