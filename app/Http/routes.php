<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




// Rotas de autenticação
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'auth'], function() {
    /* Página inicial */
    Route::resource('home','DashboardController');  
    Route::resource('/','DashboardController');  

    /* Usuários */
    Route::resource('usuario','UsuarioController');   

    /* Pessoas */
    Route::resource('pessoa','PessoaController');
    Route::get('pessoa-ajax', 'PessoaController@ajax');

    /* Filiais */
    Route::resource('filial','FilialController');
    Route::get('filial-ajax', 'FilialController@ajax');
    
    
});