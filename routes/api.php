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
Route::post('/news', ['uses'=>'newsController@create' ]);
Route::post('/news_update', ['uses'=>'newsController@update' ]);
Route::delete('/news/{id}', ['uses' => 'newsController@delete' ]);


/*
|--------------------------------------------------------------------------
|Testimonies Controller Routes API
|--------------------------------------------------------------------------
*/
Route::post('/Testimony', ['uses'=>'TestimoniesController@create']);
Route::get('/Testimonies', ['uses'=>'TestimoniesController@getAllTestimonies']);
Route::put('/Testimony/{id}', ['uses'=>'TestimoniesController@approve' ]);
Route::delete('/Testimony/{id}', ['uses' => 'TestimoniesController@delete' ]);