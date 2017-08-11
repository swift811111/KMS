<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users ;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Hash ;
use Validator, Input, Redirect, Auth; 

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
    public function create()
    {
        return View::make('site/signtest')
            ->with('title', '註冊');
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
            'username' => 'required' ,
            'password' => 'required' ,
            'email' => 'required|email'
        ] ;
        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            $user = users::firstOrCreate(array(
            'username' => $username ,
            'password' => \Hash::make($password) ,
            'email' => $email ,
            'unqid' => $request->sign_unqid       
            ));

            Auth::attempt(array('username' => $username ,'password' => $password)) ;
            return Redirect::to('/') ;

        }
        else {
            // return Redirect::to('/')->withErrors($validator);
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

    public function logincreate()
    {
        return View::make('site/logintest')
            ->with('title', '登入');
    }

    public function logout()
    {
        Auth::logout() ;
        return Redirect::to('/') ;
    }
}
