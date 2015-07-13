<?php

Route::get('/', [
    'as' => 'login',
    'uses' => function () {
        return View::make('auth.login');
    }
]);

Route::controller('/auth', 'Auth\AuthController');

Route::group([
    'prefix' => '/vote'
], function () {
    Route::get('/', [
        'as' => 'vote.index',
        'uses' => 'VoteController@index'
    ]);
    Route::post('/register', [
        'as' => 'vote.register',
        'uses' => 'VoteController@save'
    ]);

    Route::get('/confirm', [
        'as' => 'vote.confirm',
        'uses' => 'VoteController@confirm'
    ]);
});