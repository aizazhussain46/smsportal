<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('me', 'AuthController@me');

Route::post('login', 'AuthController@login');

Route::get('demo', function(){
    echo "Api working";
});
Route::resource('user', 'UserController');
Route::get('admins', 'UserController@admins');
Route::get('user_2be_assigned/{id}/{master}/{role_id?}', 'UserController@user_2be_assigned');
Route::post('change_password/{id}', 'UserController@change_password');
Route::resource('role', 'RoleController');
Route::resource('status', 'StatusController');
Route::get('created_by_users', 'UserController@created_by_users');
Route::get('assigned_to_users', 'UserController@assigned_to_users');

Route::resource('topup', 'TopupController');

Route::post('quick_sms', 'ApiController@quick_sms');
Route::get('smsaccountsummary', 'ApiController@account_summary');
Route::get('totalbalance', 'BalanceController@total_balance');
Route::get('loggedinuserbalance', 'BalanceController@loggedin_client_balance');