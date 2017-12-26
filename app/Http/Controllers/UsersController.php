<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users , App\themes , App\classification ,App\childclassification,App\theme_group,App\create_article,
    App\search_association,App\opinion,App\theme_cls_group;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Hash ;
use Illuminate\Database\Eloquent\Collection;
use Validator, Input, Redirect, Auth, DB; 

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = users::all() ;
        return $users ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //新增文章頁面
    public function create_article_page()
    {
        return View::make('site/create_article_page');
            
    }
    //新增文章
    public function create_article(Request $request){

        $article_unqid = md5( uniqid(mt_rand(),true) ) ;
        
        //文章所有內容存入所屬資料庫
        $article = create_article::firstOrCreate(array(
            'article_author' => Auth::user()->username ,
            'author_unqid' => Auth::user()->unqid ,
            'article_title' => $request->article_title ,
            'article_source' => $request->article_source ,
            'article_summary' => $request->article_summary,
            'article_content' => $request->article_textarea,
            'article_unqid' => $article_unqid,
            ));
        
        //將文章所關聯的主題跟分類放進資料庫
        $checkbox_value = $request->checkbox_value;
        $checkbox_value_array = preg_split("/,/", $checkbox_value);

        foreach ($checkbox_value_array as $item) {
            
            $cls_f_unqid = substr(trim($item),0,32); //父分類unqid
            $cls_c_unqid = substr(trim($item),32,64); //子分類unqid

            $theme_unqid =  DB::table('classification')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('unqid', $cls_f_unqid)
            ->get();

            $sotre_data = search_association::firstOrCreate(array(
            'user' => Auth::user()->username ,
            'user_unqid' => Auth::user()->unqid ,
            'article_unqid' => $article_unqid ,
            'theme_unqid' => $theme_unqid[0]->fathername ,
            'cls_f_unqid' => $cls_f_unqid ,
            'cls_c_unqid' => $cls_c_unqid,       
            ));
        }      
        return Redirect::to('/article_manage');
    }
    //刪除文章
    public function delete_article(Request $request)
    {
        $delete_data = $request->article_delete_array ;

        foreach ($delete_data as $data) {
            $delete_article = DB::table('article')
            ->where('author_unqid', Auth::user()->unqid)
            ->where('article_unqid', $data)
            ->delete();

            $delete_association = DB::table('search_association')
            ->where('user_unqid', Auth::user()->unqid)
            ->where('article_unqid', $data)
            ->delete();

            $delete_opinion = DB::table('opinion')
            ->where('user_unqid', Auth::user()->unqid)
            ->where('article_unqid', $data)
            ->delete();
        }
        return $delete_data ;
    }
    //編輯文章頁面
    public function article_edit_data($article_unqid){
        $article_data = DB::table('article')
            ->where('author_unqid', Auth::user()->unqid)
            ->where('article_unqid', $article_unqid)
            ->get();
        // return $article_data ;
        // return $article_data[0]->article_unqid ;

        //查詢子分類選項有哪些 丟進array
        $article_cls_c_unqid = DB::table('search_association')
            ->where('user_unqid', Auth::user()->unqid)
            ->where('article_unqid', $article_unqid)
            ->get();
        //儲存子分類選項的陣列
        $cls_c_unqid_array = "" ;
        $theme_unqid_array = "" ;
        foreach ($article_cls_c_unqid as $unqid) {
            $combie = $unqid->cls_f_unqid.$unqid->cls_c_unqid ;
            $theme_unqid_array .= $unqid->theme_unqid ;
            $cls_c_unqid_array .= $combie ;
        }

        return View::make('site/article_edit_page')
                ->with('article_author',$article_data[0]->article_author)
                ->with('article_title',$article_data[0]->article_title)
                ->with('article_source',$article_data[0]->article_source)
                ->with('article_summary',$article_data[0]->article_summary)
                ->with('article_editor',$article_data[0]->article_editor)
                ->with('article_textarea',$article_data[0]->article_content)
                ->with('article_unqid',$article_data[0]->article_unqid)
                ->with('cls_c_unqid_array',$cls_c_unqid_array)
                ->with('theme_unqid_array',$theme_unqid_array);

    }
    //更新文章
    public function update_article(Request $request)
    {   
        //文章所有內容存入所屬資料庫
        $article_update = create_article::where('article_unqid', $request->article_unqid)
            ->update(array(
                'article_title' => $request->article_title ,
                'article_source' => $request->article_source ,
                'article_editor' => $request->article_editor,
                'article_summary' => $request->article_summary,
                'article_content' => $request->article_textarea,
            ));
        //刪除舊有checkbox選項
        $delete_article_checkbox = DB::table('search_association')
            ->where('article_unqid', $request->article_unqid)
            ->delete();

        //作者unqid
        $author = DB::table('article')
            ->where('article_unqid', $request->article_unqid)
            ->get();

        //將文章所關聯的主題跟分類放進資料庫
        $checkbox_value = $request->checkbox_value;
        $checkbox_value_array = preg_split("/,/", $checkbox_value);

        foreach ($checkbox_value_array as $item) {
            
            $cls_f_unqid = substr(trim($item),0,32); //父分類unqid
            $cls_c_unqid = substr(trim($item),32,64); //子分類unqid    

            $theme_unqid =  DB::table('classification')
                ->where('foundername_unqid', Auth::user()->unqid)
                ->where('unqid', $cls_f_unqid)
                ->get();

            $sotre_data = search_association::firstOrCreate(array(
            'user' => $author[0]->article_author ,
            'user_unqid' => $author[0]->author_unqid ,
            'article_unqid' => $request->article_unqid ,
            'theme_unqid' => $theme_unqid[0]->fathername ,
            'cls_f_unqid' => $cls_f_unqid ,
            'cls_c_unqid' => $cls_c_unqid,       
            ));
        }      
        return Redirect::to('/article_manage');
    }
    //觀看文章頁面
    public function article_view_data($unqid)
    {   
        $article_data =  DB::table('article')
                ->where('article_unqid', $unqid)
                ->get();
        return View::make('site/article_view_page')
                ->with('article_data',$article_data);
        // return $unqid ;    
    }
    //新增意見
    public function create_new_opinion(Request $request)
    {   
        $opinion = opinion::firstOrCreate(array(
            'username' =>  Auth::user()->username,
            'user_unqid' => Auth::user()->unqid ,
            'article_unqid' => $request->article_unqid ,
            'opinion_content' => $request->opinion_content ,
            'best' => $request->best ,
            'bad' => $request->bad,       
            ));
        return $request;
    }
    //得到文章的意見
    public function get_opinion_data($article_unqid)
    {   
         $opinion_data =  DB::table('opinion')
                ->where('article_unqid', $article_unqid)
                ->get();
        
        return $opinion_data;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = users::firstOrCreate(array(
            'username' => $request->username ,
            'password' => \Hash::make($request->password) ,
            'email' => $request->email ,
            'unqid' => $request->unqid       
            ));
        return $user ;
        // return users::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function signup(Request $request)
    {
        //輸入資料
        $input = $request->all() ;
        $username = $request->sign_username ;
        $password = $request->sign_password ;
        $email = $request->sign_email ;

        //驗證
        $rules = [
            'sign_username' => 'required' ,
            'sign_password' => 'required' ,
            'sign_email' => 'required'
        ] ;
        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            $user = users::firstOrCreate(array(
            'username' => $username ,
            'password' => \Hash::make($password) ,
            'email' => $email ,
            'unqid' =>  md5( uniqid(mt_rand(),true) ),       
            ));

            Auth::attempt(array('username' => $username ,'password' => $password)) ;
            return Redirect::to('/') ;

        }
        else {
            return 'error' ;
        }

    }

    public function login(Request $request)
    {
        $input = $request->all() ;
        $username = $request->username ;
        $password = $request->password ;
        // return $input ;

        //驗證-----------------
        $rules = [
            'username' => 'required' ,
            'password' => 'required'
        ] ;
        
        $validator = Validator::make($input, $rules);
        if ($validator->passes()) {

            if (Auth::attempt(array('username' => $username ,'password' => $password))){
                return Redirect::to('/') ;
            }
            else {
                
                return View::make('site/index')
                    ->with('err','Username or Password is wrong!');
                // return response()->json(['response' => 'This is get method']);
            }

        }
        else {
            // return View::make('site/index')
            //     ->with('err','Username and Password are need!');
            return Redirect::to('/')->withErrors($validator);
            
        }
         
        //return $input ;
    }

    //登出
    public function logout()
    {
        Auth::logout() ;
        return Redirect::to('/') ;
    }

    //主題管理
    public function theme_manage()
    {
        return view('site/theme_manage');
    }

    //刪除主題 & 群組
    public function delete_theme(Request $request)
    {
        if($request->theme_various == 'theme'){
            $unqids = $request->theme_delete_array ; //get what theme need delete
            $delete_array = [] ;   //儲存所有等待刪除的子分類

            //找出主題所擁有的父分類quid 用來刪除父分類底下的所有子分類
            foreach ($unqids as $unqid) {

                $delete_select_classification = DB::table('themes')

                ->join('classification', function($join) use($unqid)
                {
                    $join->on('themes.unqid', '=', 'classification.fathername')
                        ->where('classification.fathername', '=', $unqid);
                })
                ->select('classification.unqid')
                ->get();

                foreach ($delete_select_classification as $item){
                    array_push($delete_array,$item) ;
                }
                
            }

            //刪除主題與其底下的所有父分類 子分類
            foreach ($unqids as $unqid) { //主題
                $delete_theme = DB::table('themes')
                ->where('foundername_unqid', Auth::user()->unqid)
                ->where('unqid', $unqid)
                ->delete();

                $delete_clsfather = DB::table('classification') //父分類
                ->where('foundername_unqid', Auth::user()->unqid)
                ->where('fathername', $unqid)
                ->delete();

                foreach ($delete_array as $item) {  //子分類
                    $delete_clschild = DB::table('childclassifications')
                    ->where('foundername_unqid', Auth::user()->unqid)
                    ->where('clssificationfathername', $item->unqid)
                    ->delete();
                }

                //刪除關係
                $delete_association = DB::table('search_association')
                ->where('user_unqid', Auth::user()->unqid)
                ->where('theme_unqid', $unqid)
                ->delete();

                //刪除群組裡的主題
                $delete_group = DB::table('theme_cls_group')
                ->where('user_unqid', Auth::user()->unqid)
                ->where('theme_unqid', $unqid)
                ->delete();
            }

            $themes_my = DB::table('themes')
                ->where('foundername_unqid', Auth::user()->unqid)
                ->orderBy('id', 'DESC')
                ->get();
            
            // return view('site/theme_manage')
            //     ->with('themes_my',$themes_my);
            return $themes_my;
        }else {
            $input = $request->all() ;

            foreach ($request->theme_group_delete_array as $item) {
                $theme_group_delete = DB::table('theme_group')
                ->where('user_unqid', Auth::user()->unqid)
                ->where('theme_group_unqid', $item)
                ->delete();

                $theme_cls_group_delete = DB::table('theme_cls_group')
                ->where('user_unqid', Auth::user()->unqid)
                ->where('theme_group_unqid', $item)
                ->delete();
            }            
            return $input;
        }
    }

    // 新增資料
    //新增主題OR群組
    public function create_theme(Request $request)
    {
        $input = $request->all() ;

        if($request->theme_various == 'theme'){
            //驗證-----------------
            $rules = [
                'theme_name' => 'required' ,
                'theme_creater' => 'required'
            ] ;
            
            $validator = Validator::make($input, $rules);
            if ($validator->passes()) {

                $theme_add = themes::firstOrCreate(array(
                    'themename' => urlencode($request->theme_name) ,
                    'foundername' => Auth::user()->username ,
                    'foundername_unqid' =>Auth::user()->unqid ,
                    'unqid' => md5( uniqid(mt_rand(),true) ),
                    'public' => 1       
                    ));

                    return Redirect::to('/theme_manage') ;
            }else {            
                return 'error';           
            }
        }else {
            $input = $request->all() ;
            $theme_group_unqid = md5( uniqid(mt_rand(),true) ) ;
            
            foreach ($request->theme_in_group_array as $item) {
                $theme_unqid = substr(trim($item),0,32);
                $theme_name = substr(trim($item),32) ;

                $theme_cls_group = theme_cls_group::firstOrCreate(array(
                'username' => Auth::user()->username  ,
                'user_unqid' => Auth::user()->unqid ,
                'theme_group_name' => $request->theme_group_name,
                'theme_group_unqid' => $theme_group_unqid,
                'theme_name' => urlencode($theme_name),
                'theme_unqid' => $theme_unqid,
                ));
            }
            $theme_group = theme_group::firstOrCreate(array(
                'username' => Auth::user()->username  ,
                'user_unqid' => Auth::user()->unqid ,
                'theme_group_name' => urlencode($request->theme_group_name),
                'theme_group_unqid' => $theme_group_unqid,
                // md5( uniqid(mt_rand(),true) )
                ));
        }      
        return $input ;
    }
    //更新主題 and 群組名稱
    public function theme_update(Request $request)
    {
        if($request->table_name == 'theme'){
            $themeupdate = themes::where('unqid', $request->theme_unqid)
            ->update(array(
                'themename' => $request->theme_name ,
            ));
        }else {
            $theme_group = theme_group::where('theme_group_unqid', $request->theme_unqid)
            ->update(array(
                'theme_group_name' => $request->theme_name ,
            ));
            $theme_cls_group_update = theme_cls_group::where('theme_group_unqid', $request->theme_unqid)
            ->update(array(
                'theme_group_name' => $request->theme_name ,
            ));
        }
        
        return $request->theme_name;
    }
    //get theme checkbox value
    public function group_checkbox(Request $request)
    {
        $group_checkbox = DB::table('theme_cls_group')
            ->where('user_unqid', Auth::user()->unqid)
            ->where('theme_group_unqid', $request->group_unqid)
            ->get();
        //decode
        foreach ($group_checkbox as &$value) {
           $value->theme_name = urldecode($value->theme_name) ;
        }
       
        return $group_checkbox ;
    }
    //更新群組
    public function group_update(Request $request)
    {
        //更新群組名稱
        $group_name = theme_group::where('theme_group_unqid', $request->theme_group_unqid)
            ->update(array(
                'theme_group_name' => urlencode($request->theme_group_name) ,
            ));

        $group_delet = DB::table('theme_cls_group')
            ->where('user_unqid', Auth::user()->unqid)
            ->where('theme_group_unqid', $request->theme_group_unqid)
            ->delete();

        foreach ($request->theme_in_group_array as $item) {
                $theme_unqid = substr(trim($item),0,32);
                $theme_name = substr(trim($item),32) ;

                $theme_cls_group = theme_cls_group::firstOrCreate(array(
                'username' => Auth::user()->username  ,
                'user_unqid' => Auth::user()->unqid ,
                'theme_group_name' => urlencode($request->theme_group_name),
                'theme_group_unqid' => $request->theme_group_unqid,
                'theme_name' => urlencode($theme_name),
                'theme_unqid' => $theme_unqid,
                ));
            }
    }
    //編輯某主題的分類
    public function edit_theme_cls(Request $request)
    {        
        return view('site/classification_manage')
            ->with('themesname',$request->themesname)
            ->with('themesnuqid',$request->themesunqid);
    }

    //父分類
    public function classification_add(Request $request)
    {
        $input = $request->all() ;

        $classification_add = classification::firstOrCreate(array(
            'fathername' => $request->fathername ,
            'foundername' => $request->foundername ,
            'foundername_unqid' => Auth::user()->unqid ,
            'unqid' => md5( uniqid(mt_rand(),true) ),
            'name' => $request->name,     
            ));

        return $input ;
    }
    //移除父分類
    public function delete_father_cls(Request $request)
    {
        $input = $request->unqid ;

        foreach( $input as $item){
            $delete_father_cls = DB::table('classification')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('unqid', $item)
            ->delete();

            $delete_child_cls = DB::table('childclassifications')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('clssificationfathername', $item)
            ->delete();
        }
        // return $input;
        
    }

    //子分類
    public function Child_Classification_add(Request $request)
    {
        $input = $request->all() ;

        $classification_add = childclassification::firstOrCreate(array(
            'clssificationfathername' => $request->fathername ,
            'foundername' => $request->foundername ,
            'foundername_unqid' => Auth::user()->unqid ,
            'unqid' => md5( uniqid(mt_rand(),true) ),
            'name' => $request->name,     
            ));

        return $input ;
    }
    //移除子分類
    public function delete_child_cls(Request $request)
    {
        $input = $request->all() ;

        $delete_child_cls = DB::table('childclassifications')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('unqid', $input)
            ->delete();
    }
    //更新子分類
    public function update_child_cls(Request $request)
    {
        $update_child_cls = childclassification::where('unqid', $request->unqid)
            ->update(array(
                'name' => urlencode($request->name) ,
            ));
        return $request->unqid;
    }
    //顯示分類管理頁面
    public function classification_manage()
    {
        return view('site/classification_manage')
            ->with('themes_my',null);
    }
    //顯示文章管理頁面
    public function article_manage()
    {
        // $article = DB::table('article')
        //     ->where('author_unqid', Auth::user()->unqid)
        //     ->orderBy('article_id', 'DESC')
        //     ->paginate(1);

        $article = DB::table('article')
            ->where('author_unqid', Auth::user()->unqid)
            ->orderBy('article_id', 'DESC')
            ->get();
        return view('site/article_manage')->with('articles',$article); ;
    }

    //data
    // theme data
    public function theme_data()
    {
        $themes_my = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();

            //decode
            foreach ($themes_my as &$value) {
            $value->themename = urldecode($value->themename) ;
            }
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($themes_my);
    }
    public function theme_data_name($unqid)
    {
        $themes_unqid = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('unqid', $unqid)
            ->get();
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($themes_unqid);
    }
    //得到已排序主題資料
    public function theme_data_arrange($perpage,$page,$theme_column,$theme_arrangement)
    {
        $themes_unqid = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy($theme_column, $theme_arrangement)
            ->skip($perpage*($page-1))
            ->take($perpage)
            ->get();

        //decode
        foreach ($themes_unqid as &$value) {
           $value->themename = urldecode($value->themename) ;
        }
        //return response()->json(['themes_my' => $themes_my]) ;
        return $themes_unqid;
    }
    //得到已排序合併主題資料
    public function theme_group_data_arrange($perpage,$page,$theme_column,$theme_arrangement)
    {
        $theme_group_unqid = DB::table('theme_group')
            ->where('user_unqid', Auth::user()->unqid)
            ->orderBy($theme_column, $theme_arrangement)
            ->skip($perpage*($page-1))
            ->take($perpage)
            ->get();

        //decode
        foreach ($theme_group_unqid as &$value) {
           $value->theme_group_name = urldecode($value->theme_group_name) ;
        }
        //return response()->json(['themes_my' => $themes_my]) ;
        return $theme_group_unqid;
    }

    // theme_group data
    public function theme_group_data()
    {
        $theme_group_data = DB::table('theme_group')
            ->where('user_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();

        //decode
        foreach ($theme_group_data as &$value) {
           $value->theme_group_name = urldecode($value->theme_group_name) ;
        }
        //return response()->json(['themes_my' => $themes_my]) ;
        return $theme_group_data;
    }

    public function classification_data( $fathername )
    {
        $classification = DB::table('classification')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->where('fathername', $fathername)
            ->orderBy('id', 'DESC')
            ->get();

        $unqidcollect = [] ;
        foreach ($classification as $cla){
            $childclassification = DB::table('childclassifications')
                ->where('foundername_unqid', Auth::user()->unqid)
                ->where('clssificationfathername', $cla->unqid)
                ->orderBy('id', 'DESC')
                ->get();
            //decode
            foreach ($childclassification as &$value) {
            $value->name = urldecode($value->name) ;
            }
            array_push($unqidcollect,$childclassification) ;
            
        }
        
        $merge = [] ; //合兩個陣列
        array_push($merge,$classification) ;
        array_push($merge,$unqidcollect) ;
        // $merge = json_encode(array_merge(json_decode($classification, true),$unqidcollect)) ;
        //return response()->json(['themes_my' => $themes_my]) ;
        return $merge;
        // return response()->json($classification[0]->name);
    }

    //article data
    public function article_data($perpage,$page,$column,$arrangement)
    {
        $article_data = DB::table('article')
            ->where('author_unqid', Auth::user()->unqid)
            ->orderBy($column,$arrangement)
            ->skip($perpage*($page-1))
            ->take($perpage)
            ->get();
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($article_data);
    }
    public function article_all_data()
    {
        $article_all_data = DB::table('article')
            ->where('author_unqid', Auth::user()->unqid)
            ->orderBy('article_id', 'ASC')
            ->get();
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($article_all_data);
    }

}
