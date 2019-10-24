<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function(){
    //短信验证
    Route::post('verificationCodes', 'VerificationCodesController@store')->name('api.verificationCodes.store');
    //用户注册
    Route::get('users','UsersController@store')->name('users.store');
});
