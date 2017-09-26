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
Auth::routes();
Route::get('/', function () {
    return view('site/index');
});
// Route::get('/users', 'UsersController@index');
// Route::get('/users/signup', 'UsersController@create');
// Route::post('/post', 'UsersController@store');

// Route::get('/users/login', 'UsersController@logincreate');

// Route::post('/signup', 'UsersController@signup');
Route::post('/signup',[
    'as' => 'user.signup',
    'uses' => 'UsersController@signup'
]);
// Route::post('/signup', array('before' => 'csrf',
//             'as' => 'user.signup',
//             'uses' => 'UsersController@signup'
// ));

Route::post('/login', 'UsersController@login');
// Route::get('/logout', 'UsersController@logout');

// Route::get('/theme_manage', 'UsersController@theme_manage');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>'auth'],function(){
    Route::get('/theme_manage', 'UsersController@theme_manage');
    Route::get('/logout', 'UsersController@logout');
    Route::post('/themeAdd',[
        'as' => 'theme.add',
        'uses' => 'UsersController@theme_add'
    ]);
    Route::get('/classification_manage', 'UsersController@classification_manage');
    Route::get('/article_manage', 'UsersController@article_manage');
});
