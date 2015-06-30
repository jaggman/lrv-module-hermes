<?php

Route::group(['prefix' => 'hermes', 'namespace' => 'Modules\Hermes\Http\Controllers'], function()
{
	//Route::get('/', 'HermesController@index');
        Route::controller('/', 'HermesController');
        //Route::post('/in', ['before' => 'ccsrf','HermesController@postIn']);
});