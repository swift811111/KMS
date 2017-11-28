<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users , App\themes , App\classification ,App\childclassification,App\theme_group,App\create_article,
    App\search_association;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Hash ;
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
        $themes_my = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();

        $themes_group_my = DB::table('theme_group')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();

        return view('site/theme_manage')
            ->with('themes_my',$themes_my)
            ->with('themes_group_my',$themes_group_my);
        // return $themes;
    }

    //刪除主題
    public function delete_theme(Request $request)
    {
        
        $unqids = $request->themes_data ; //get what theme need delete
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
        }

        $themes_my = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();
        
        // return view('site/theme_manage')
        //     ->with('themes_my',$themes_my);
        return Redirect::to('/theme_manage')->with('themes_my',$themes_my);
    }

    // 新增資料
    //主題
    public function theme_add(Request $request)
    {
        $input = $request->all() ;
        $theme_name = $request->theme_name ;
        // $theme_creater = $request->theme_creater ;
        // return $input ;

        //驗證-----------------
        $rules = [
            'theme_name' => 'required' ,
            'theme_creater' => 'required'
        ] ;
        
        $validator = Validator::make($input, $rules);
        if ($validator->passes()) {

            $theme_add = themes::firstOrCreate(array(
                'themename' => $theme_name ,
                'foundername' => Auth::user()->username ,
                'foundername_unqid' =>Auth::user()->unqid ,
                'unqid' => md5( uniqid(mt_rand(),true) ),
                'public' => 1       
                ));

                return Redirect::to('/theme_manage') ;
            
            // else {
                
            //     return View::make('site/index')
            //         ->with('err','Username or Password is wrong!');
                // return response()->json(['response' => 'This is get method']);
        }

        
        else {
            
            return 'error';
            
        }
         
        //return $input ;
    }

    //建立群組
    public function create_theme_group(Request $request)
    {
        $input = $request->all() ;

        $theme_group = theme_group::firstOrCreate(array(
            'name' => $request->name ,
            'foundername' => Auth::user()->username ,
            'foundername_unqid' => Auth::user()->unqid ,
            'theme_name_json' => $request->theme_select_json,
            'unqid' => md5( uniqid(mt_rand(),true) ),
            // md5( uniqid(mt_rand(),true) )
            ));

        return $input ;
    }
    //刪除群組
    public function delete_theme_group(Request $request)
    {
        $input = $request->all() ;

        // $theme_group = theme_group::firstOrCreate(array(
        //     'name' => $request->name ,
        //     'foundername' => Auth::user()->username ,
        //     'theme_name_json' => $request->theme_select_json,
        //     'unqid' => md5( uniqid(mt_rand(),true) ),
        //     ));
        return $input;
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

    //顯示分類管理頁面
    public function classification_manage()
    {
        return view('site/classification_manage')
            ->with('themes_my',null);
    }
    //顯示文章管理頁面
    public function article_manage()
    {
        return view('site/article_manage') ;
    }

    //data
    // theme data
    public function theme_data()
    {
        $themes_my = DB::table('themes')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($themes_my);
    }

    // theme_group data
    public function theme_group_data()
    {
        $theme_group_data = DB::table('theme_group')
            ->where('foundername_unqid', Auth::user()->unqid)
            ->orderBy('id', 'DESC')
            ->get();
        //return response()->json(['themes_my' => $themes_my]) ;
        return response()->json($theme_group_data);
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

}
