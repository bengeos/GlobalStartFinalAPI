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

Route:: post('/register',[ 'uses' => 'UserController@register' ]);
Route:: post('/authenticate',[ 'uses' => 'UserController@authenticate' ]);
Route:: get('/role',[ 'uses' => 'UserController@getRole' ]);
Route:: put('/status/{id}',[ 'uses' => 'UserController@activateUser' ]);

/*
|--------------------------------------------------------------------------
| Users Controller Routes API
|--------------------------------------------------------------------------
*/
Route::post('/user',[ 'uses' => 'UserController@register' ]);
Route::get('/users',[ 'uses' => 'UserController@getUsers' ]);
Route::put('/user',[ 'uses' => 'UserController@update' ]);
Route::delete('/user/{id}',[ 'uses' => 'UserController@delete' ]);


/*
|--------------------------------------------------------------------------
| News Controller Routes API
|--------------------------------------------------------------------------
*/
Route::get('/news', ['uses'=>'newsController@getAllNews']);
// Route::get('/news_today', ['uses'=>'newsController@getTodayNews']);
// Route::get('/news_old', ['uses'=>'newsController@getOldNews']);
Route::post('/news', ['uses'=>'newsController@create' ]);
Route::put('/news', ['uses'=>'newsController@update' ]);
Route::delete('/news/{id}', ['uses' => 'newsController@delete' ]);