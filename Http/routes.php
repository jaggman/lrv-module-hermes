<?php

Route::group(['prefix' => 'hermes', 'namespace' => 'Modules\Hermes\Http\Controllers'], function()
{
	//Route::get('/', 'HermesController@index');
        Route::controller('/', 'HermesController');
        //Route::post('/in', ['before' => 'ccsrf','HermesController@postIn']);
});
        Route::post('/in', 'Modules\Hermes\Http\Controllers\InController@postIn');
        //Route::post('/test/in', 'Modules\Hermes\Http\Controllers\InController@postInTest');
        Route::post('/test/in', 'Modules\Hermes\Http\Controllers\InControllerTest@postIn');
        //Route::get('/in', 'Modules\Hermes\Http\Controllers\HermesController@postIn');
