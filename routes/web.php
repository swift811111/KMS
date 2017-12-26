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
    
    //管理頁面
    Route::get('/classification_manage', 'UsersController@classification_manage');//分類管理頁面
    Route::get('/article_manage', 'UsersController@article_manage');//文章管理頁面
    Route::get('/create_article_page', 'UsersController@create_article_page'); //新增文章頁面
    Route::get('/article_edit_data{article_unqid}', 'UsersController@article_edit_data');  //拿到所有文章資料
    Route::get('/article_view_data{unqid}', 'UsersController@article_view_data'); //觀看文章頁面

    //post data
    Route::post('/post/create_theme',[  //新增主題 & 群組
        'as' => 'create_theme.add',
        'uses' => 'UsersController@create_theme'
    ]);
    Route::post('/post/delete_theme',[  //刪除主題 & 群組
        'as' => 'delete_theme.delete',
        'uses' => 'UsersController@delete_theme'
    ]);
    Route::post('/post/theme_update',[  //更新主題
        'as' => 'theme.update',
        'uses' => 'UsersController@theme_update'
    ]);
    Route::post('/post/group_checkbox',[  //get theme checkbox value
        'as' => 'group_checkbox.checkbox',
        'uses' => 'UsersController@group_checkbox'
    ]);
    Route::post('/post/group_update',[  //更新群組
        'as' => 'group_update.update',
        'uses' => 'UsersController@group_update'
    ]);
    Route::post('/edit_theme_cls',[  //編輯當前主題分類
        'as' => 'edit_theme_cls.edit',
        'uses' => 'UsersController@edit_theme_cls'
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
    Route::post('/post/update_child_cls',[  //在父分類底下更新子分類
        'as' => 'update_child_cls.update',
        'uses' => 'UsersController@update_child_cls'
    ]);
    Route::post('/post/delete_father_cls',[  //刪除父分類
        'as' => 'delete_father_cls.delete',
        'uses' => 'UsersController@delete_father_cls'
    ]);
    Route::post('/post/create_article',[  //新增文章
        'as' => 'create_article.create',
        'uses' => 'UsersController@create_article'
    ]);
    Route::post('/post/delete_article',[  //刪除文章
        'as' => 'delete_article.delete',
        'uses' => 'UsersController@delete_article'
    ]);
    Route::post('/post/update_article',[  //更新文章
        'as' => 'update_article.update',
        'uses' => 'UsersController@update_article'
    ]);
    Route::post('/post/create_new_opinion',[  //新增意見
        'as' => 'create_new_opinion.create',
        'uses' => 'UsersController@create_new_opinion'
    ]);


    //get data
    Route::get('/data/theme_data', 'UsersController@theme_data');  //拿到主題資料
    Route::get('/data/theme_data/{theme_unqid}', 'UsersController@theme_data_name');  //拿到主題名稱
    Route::get('/data/theme_data_arrange/{perpage}/{page}/{theme_column}/{theme_arrangement}', 'UsersController@theme_data_arrange');  //拿到主題排列完的資料
    Route::get('/data/theme_group_data', 'UsersController@theme_group_data');  //拿到合併主題資料
    Route::get('/data/theme_group_data_arrange/{perpage}/{page}/{theme_column}/{theme_arrangement}', 'UsersController@theme_group_data_arrange');  //拿到合併主題排列完的資料
    Route::get('/data/classification_data/{fathername}', 'UsersController@classification_data');  //拿到父分類資料
    Route::get('/data/childclassification_data/{fathername}', 'UsersController@childclassification_data');  //拿到子分類資料
    Route::get('/data/article_data/{perpage}/{page}/{column}/{arrangement}', 'UsersController@article_data');  //拿到特定頁文章資料
    Route::get('/data/article_all_data', 'UsersController@article_all_data');  //拿到所有文章資料
    Route::get('/data/get_opinion_data/{article_unqid}', 'UsersController@get_opinion_data');  //拿到某篇文章的意見資料
    
});
