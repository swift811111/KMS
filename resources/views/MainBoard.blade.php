<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>KMS</title>
    <!-- Loading -->
    <link rel="Stylesheet" type="text/css" href="../resources/assets/sass/index_scss/loading.css" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap4.min.css">
    <script src="https://unpkg.com/vue"></script>
    
  </head>
  
  <body>
    
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">註冊</h4>
                </div>
                <div class="modal-body sign_input_group">
                    <!-- sign up form -->
                    <form action="{{ route('user.signup') }}" method="post" id="SignForm" role="form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">使用者名稱</label>
                            <input type="text" class="form-control" name="sign_username" id="sign_username" placeholder="create username" required >
                            <small class="text-muted"> 請輸入符合格式的字串 </small>    
                        </div>
                        <div class="form-group">
                            <label for="">密碼</label>
                            <input type="password" class="form-control" name="sign_password" id="sign_password" placeholder="create password" required >  
                            <small class="text-muted"> 請輸入符合格式的字串 </small>     
                        </div>
                        <div class="form-group">
                            <label for="">密碼確認</label>
                            <input type="password" class="form-control" name="sure_sign_password" id="sure_sign_password" placeholder="enter password again" required >
                            <small class="text-muted"> 與密碼不符 </small>    
                        </div>
                        <div class="form-group">
                            <label for="">電子信箱</label>
                            <input type="email" class="form-control" name="sign_email" id="sign_email" placeholder="your e-mail" required >
                            <small class="text-muted"> 請輸入正確的email </small>    
                        </div>
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
                <!-- <span>選單</span> -->
                <img class="menu_img" src="../resources/assets/image/icon/menu.png" alt="">
                <div class="blank" style="width:1em;"></div>
                <div class="menuName">選單</div>
            </div>
            <!-- <div class="Logout_btn ">
                @if(Auth::check())
                    {{ Auth::user()->username }}
                    {{ Html::linkRoute('logout', '| LOG OUT') }}
                @endif
            </div> -->
            <div class="kmsTitle" onclick="window.location='{{ url("/") }}'">
                    KMS
            </div>
            <div class="btn-group row">
                <div class="center">
                    @if(Auth::check())
                        <button type="button" class="btn btn-secondary dropdown-toggle user-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->username }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/">個人資料管理</a>
                            <a class="dropdown-item" href="logout">登出</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- side bar -->
    <div class="side_bar" style="left:{{ count($errors)>0|isset($err) ? '0px' : '-400px' }}">
        <div class="cancel">
           <div class="close-btn"><b>關閉</b></div>
           <div class="close-img">
            <img class="cancel_img" src="../resources/assets/image/icon/cancel.png" >
           </div>
        </div>
        @if(Auth::check())
            <!-- 已登入的管理頁面 -->
            <div class="manage_page" id="exampleAccordion" data-children=".item">

                <div class="item">
                    <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion2" aria-expanded="false" aria-controls="exampleAccordion2">              
                        <div class="collapse_title" onclick="window.location='{{ url("create_article_page") }}'">
                            新增文章
                        </div>
                    </a>
                </div>

                <div class="item">
                    <a data-toggle="collapse" data-parent="#exampleAccordion" href="#manageOption" aria-expanded="false" aria-controls="manageOption">                        
                        <div class="collapse_title"  @click="imgTransform" >
                            <div class="manage_father center">管理選單</div>
                            <div class="center"><img src="../resources/assets/image/icon/chevron-sign-to-right.png" class="chevron-sign-to-right"></div>
                        </div>
                    </a>
                    <div id="manageOption" class="collapse show" role="tabpanel">
                        <div class="theme_manage manage_title" onclick="window.location='{{ url("theme_manage") }}'">
                            <div class="center"><img src="../resources/assets/image/icon/chevron.png" class="chevron"></div>
                            <div class="manage_child center">主題管理</div>
                        </div>
                        <div class="classification_manage manage_title" onclick="window.location='{{ url("classification_manage") }}'">
                            <div class="center"><img src="../resources/assets/image/icon/chevron.png" class="chevron"></div>
                            <div class="manage_child center">分類管理</div>
                        </div>
                        <div class="article_manage manage_title" onclick="window.location='{{ url("article_manage") }}'">
                            <div class="center"><img src="../resources/assets/image/icon/chevron.png" class="chevron"></div>
                            <div class="manage_child center">文章管理</div>
                        </div>
                    </div>
                </div>

                <div class="item">
                    <a data-toggle="collapse" data-parent="#exampleAccordion" href="#exampleAccordion2" aria-expanded="false" aria-controls="exampleAccordion2">                        
                        <div class="collapse_title">
                            搜尋
                        </div>
                    </a>
                </div>
                
            </div>
        @else
            <!-- log in -->
            <div class="form">
                <form action="{{ route('login') }}" method="post" id="LoginForm">
                    {{ csrf_field() }}
                    <fieldset class="form-group">
                        {{ Form::text('InputUsername','', array('class' => 'form-control', 'required' => 'required', 'name' => 'username', 'placeholder' => '使用者名稱')) }}
                        <!-- <input type="text" class="form-control" id="InputUsername" name="username" placeholder="Username" required> -->
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="password" class="form-control" id="InputPassword1"name="password" placeholder="密碼" required>
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
                <button type="button" class="btn btn_log_in" id="login_btn" >登入</button>
            </div>
            <div class="sign_up">
                <button type="button" class="btn btn_sign_up btn-lg" data-toggle="modal" data-target="#myModal">註冊</button>
            </div>
        </div>
        
        @endif
    </div>
    
    <!-- text content -->
    <div class="mask"></div>
    @yield('BodyContent')
                        
    <!-- footer -->
    <footer></footer>
    
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" ></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue"></script>
    <!-- index CSS -->
    <link rel="stylesheet" href="../resources/assets/sass/index_scss/mainboard_css.css">
    <!-- index JS -->
    <script type="text/javascript" src="../resources/assets/js/index_js/mainboard_js.js"></script>
    <script type="text/javascript" src="../resources/assets/js/index_js/indexisValid_js.js"></script>
    @yield('BodyScriptCss')
    
  </body>

</html>