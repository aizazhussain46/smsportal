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
Route::resource('campaign', 'CampaignController');
Route::get('campaign_by_user', 'CampaignController@campaign_by_user');

Route::resource('group', 'GroupController');
Route::get('group_by_user', 'GroupController@group_by_user');

Route::resource('mask', 'MaskController');
Route::get('mask_by_user', 'MaskController@mask_by_user');

Route::resource('contact', 'ContactController');
Route::get('contact_by_user', 'ContactController@contact_by_user');
Route::resource('city', 'CityController');
Route::post('upload_contacts', 'ContactController@upload_contacts');

Route::post('quick_sms', 'ApiController@quick_sms');
Route::get('quick_sms_logs', 'ApiController@quick_sms_logs');
Route::post('send_bulk_sms', 'ApiController@bulk_sms');
Route::post('bulk_sms', 'BulkController@bulk_sms');
Route::get('smsaccountsummary', 'ApiController@account_summary');
Route::get('totalbalance', 'BalanceController@total_balance');
Route::get('loggedinuserbalance', 'BalanceController@loggedin_client_balance');