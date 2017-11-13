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
Route::post('/signup',[
    'as' => 'user.signup',
    'uses' => 'UsersController@signup'
]);
// Route::post('/signup', array('before' => 'csrf',
//             'as' => 'user.signup',
//             'uses' => 'UsersController@signup'
// ));
Route::post('/login', 'UsersController@login');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>'auth'],function(){

    Route::get('/theme_manage', 'UsersController@theme_manage');
    Route::get('/logout', 'UsersController@logout');
    
    Route::get('/classification_manage', 'UsersController@classification_manage');
    Route::get('/article_manage', 'UsersController@article_manage');

    //post data
    Route::post('/post/themeAdd',[  //新增主題
        'as' => 'theme.add',
        'uses' => 'UsersController@theme_add'
    ]);
    Route::post('/post/classificationAdd',[  //新增父分類
        'as' => 'classification.add',
        'uses' => 'UsersController@classification_add'
    ]);
    Route::post('/post/Child_Classification_add',[  //在父分類底下新增子分類
        'as' => 'Child_Classification.add',
        'uses' => 'UsersController@Child_Classification_add'
    ]);
    Route::post('/post/delete_child_cls',[  //在父分類底下刪除子分類
        'as' => 'delete_child_cls.delete',
        'uses' => 'UsersController@delete_child_cls'
    ]);
    Route::post('/post/delete_theme',[  //刪除主題
        'as' => 'delete_theme.delete',
        'uses' => 'UsersController@delete_theme'
    ]);
    Route::post('/post/delete_father_cls',[  //刪除主題
        'as' => 'delete_father_cls.delete',
        'uses' => 'UsersController@delete_father_cls'
    ]);

    //get data
    Route::get('/data/theme_data', 'UsersController@theme_data');  //拿到主題資料
    Route::get('/data/classification_data/{fathername}', 'UsersController@classification_data');  //拿到父分類資料
    Route::get('/data/childclassification_data/{fathername}', 'UsersController@childclassification_data');  //拿到子分類資料
    
});
