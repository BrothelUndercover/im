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
//Route::post('login', 'SessionsController@store')->name('login');

use App\Helper\Rsa;

Route::get('test',function(){

    $rsa = new Rsa('/var/www/Im/keys');
    dump($str = 'ssh-test');
    $pub = $rsa->pubEncrypt($str);
    dump($pub);
    $pri = $rsa->privDecrypt($pub);
    dump($pri);
});
