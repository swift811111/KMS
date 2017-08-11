@extends('MainBoard')
@section('BodyContent')
<!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Sign Up</h4>
                </div>
                <div class="modal-body sign_input_group">
                    <!-- sign up form -->
                    <form action="{{ route('user.signup') }}" method="post" id="SignForm" role="form" data-toggle="validator">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="sign_username" id="sign_username" placeholder="create username" required >
                            <small class="text-muted"> 請輸入符合格式的字串 </small>    
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="sign_password" id="sign_password" placeholder="create password" required >  
                            <small class="text-muted"> 請輸入符合格式的字串 </small>     
                        </div>
                        <div class="form-group">
                            <label for="">Password Again</label>
                            <input type="password" class="form-control" name="sure_sign_password" id="sure_sign_password" placeholder="enter password again" required >
                            <small class="text-muted"> 與密碼不符 </small>    
                        </div>
                        <div class="form-group">
                            <label for="">E-mail</label>
                            <input type="email" class="form-control" name="sign_email" id="sign_email" placeholder="your e-mail" required >
                            <small class="text-muted"> 請輸入正確的email </small>    
                        </div>
                        <input type="hidden" name='sign_unqid' value="<?php echo uniqid() ?>">
                    </form>       
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="sign_btn" class="btn btn-primary" disabled="true">Submit</button>
                </div>
            </div>
        </div>
    </div>

<!-- header menu -->
    <header>
        <div class="menu">
            <div class="menu_click ">
                <span>MENU</span>
                <img class="menu_img" src="../resources/assets/image/icon/menu.png" alt="">
            </div>
            <!-- <div class="Logout_btn ">
                @if(Auth::check())
                    {{ Auth::user()->username }}
                    {{ Html::linkRoute('logout', '| LOG OUT') }}
                @endif
            </div> -->
            <div class="btn-group">
                @if(Auth::check())
                    <button type="button" class="btn btn-secondary dropdown-toggle user-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->username }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="logout">Personal files</a>
                        <a class="dropdown-item" href="logout">LOG OUT</a>
                    </div>
                 @endif
            </div>
        </div>
    </header>

    <!-- side bar -->
    <div class="side_bar" style="left:{{ count($errors)>0|isset($err) ? '0px' : '-400px' }}">
        <div class="cancel">
            <img class="cancel_img" src="../resources/assets/image/icon/cancel.png" >
        </div>
        @if(Auth::check())
            <div class="manage_page form">
                <div class="theme_manage manage_title">
                    Theme Manage
                </div>
                <div class="classification_manage manage_title">
                    Classification Manage
                </div>
                <div class="article_manage manage_title">
                    Article Manage
                </div>
            </div>
        @else
        <!-- log in -->
        <div class="form">
            <form action="{{ route('login') }}" method="post" id="LoginForm">
                {{ csrf_field() }}
                <fieldset class="form-group">
                    {{ Form::text('InputUsername','', array('class' => 'form-control', 'required' => 'required', 'name' => 'username', 'placeholder' => 'Username')) }}
                    <!-- <input type="text" class="form-control" id="InputUsername" name="username" placeholder="Username" required> -->
                </fieldset>
                <fieldset class="form-group">
                    <input type="password" class="form-control" id="InputPassword1"name="password" placeholder="Password" required>
                </fieldset>
                <!-- errors -->
                <div style="color:red;">
                    <?php               
                        foreach ($errors->all() as $message)
                        {
                            echo $message."<br>" ; 
                        }
                    ?>
                @if(isset($err)) 
                    {{ $err }}
                @endif   
                </div> 
            </form>
        </div>
        <div class="button_group">
            <div class="log_in">
                <button type="button" class="btn btn_log_in" id="login_btn" >LOG IN</button>
            </div>
            <div class="sign_up">
                <button type="button" class="btn btn_sign_up btn-lg" data-toggle="modal" data-target="#myModal">SIGN UP</button>
            </div>
        </div>
        
        @endif
    </div>

    <!-- text content -->
    <div class="content">
        <p>Knowledge Manage System</p>
        <div class="bottom_line"></div>
    </div>

    <!-- footer -->
    <footer></footer>

@endsection