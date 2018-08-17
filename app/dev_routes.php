<?php

Route::get('/moo', 'MooController@moo');
Route::get('/baa', 'MooController@baa');
Route::get('/woo', 'MooController@woo');
Route::get('/killsession', ['before'=>'pvs_auth','uses'=>'MooController@killSession']);
Route::get('/speciesimport', 'MooController@readSpecies');
Route::get('/sessdump', 'MooController@sessdump');
