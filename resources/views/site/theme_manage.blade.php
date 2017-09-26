@extends('MainBoard')
@section('BodyContent')
<!-- Modal -->
<div class="modal fade" id="ThemeCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">新增主題</h4>
            </div>
            <div class="modal-body ">
                <!-- sign up form -->
                <form action="{{ route('theme.add') }}" method="post" id="ThemeCreateForm" role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">主題名稱</label>
                        <input type="text" class="form-control" name="theme_name" id="theme_name" placeholder="" required >
                        <small class="text-muted"> 請輸入主題名稱 </small>    
                    </div>
                    <div class="form-group">
                        <label for="">建立者</label>
                        <input type="text" class="form-control" name="theme_creater" id="theme_creater" placeholder="" required value='{{ Auth::user()->username }}' >  
                        <small class="text-muted"> 請輸入名字 </small>     
                    </div>
                    <input type="hidden" name='theme_unqid' value="<?php echo uniqid() ?>">
                </form>       
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                <button type="button" id="theme_create_btn" class="btn btn-primary" >建立</button>
            </div>
        </div>
    </div>
</div>
<!-- text content -->
<div class="content">

    <nav class="nav nav-tabs" id="myTab" style="width:100%;" role="tablist">
        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-expanded="true">我的主題</a>
        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile">收藏的主題</a>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php
                // foreach ($themes as $user)
                // {
                //     echo $user->themename;
                // }
            ?>
            <div class="NewTheme">
                <div class="NewAdd">
                    <img class="menu_img NewThemeIcon" src="../resources/assets/image/icon/plus.png" alt="">
                    <!-- <span class="NewTheme_click">新增主題</span> -->
                    <button type="button" class="btn btn_theme_create" data-toggle="modal" data-target="#ThemeCreate">新增主題</button>
                </div>
                <!-- <div class="ThemeSearch">  
                    <nav class="navbar navbar-light bg-light">
                        <form class="form-inline">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </form>
                    </nav>
                </div>
                <div class="ThemeSort"></div> -->
            </div>

            <div class="ThemeContainer">
                <table id="ThemeTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>主題名稱</th>
                            <th>作者</th>
                            <th>建立日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id = 1 ;
                        foreach ($themes_my as $theme_my)
                        {
                            echo "<tr>" ;
                                echo "<td>".$id."</td>" ;
                                echo "<td>".$theme_my->themename."</td>" ;
                                echo "<td>".$theme_my->foundername."</td>" ;
                                echo "<td>".$theme_my->created_at."</td>" ;
                            echo "</tr>" ;
                            $id++;
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        
            <!-- <div>Icons made by <a href="https://www.flaticon.com/authors/roundicons" title="Roundicons">Roundicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div> -->
        </div>
        <div class="tab-pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

            <div class="NewTheme">
            <img class="menu_img NewThemeIcon" src="../resources/assets/image/icon/plus.png" style="visibility:hidden;">
            </div>
            <div class="ThemeContainer">
                <table id="ThemeTable_collect" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>主題名稱</th>
                            <th>作者</th>
                            <th>建立日期</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $id = 1 ;
                        foreach ($themes_collect as $theme_collect)
                        {
                            echo "<tr>" ;
                                echo "<td>".$id."</td>" ;
                                echo "<td>".$theme_collect->themename."</td>" ;
                                echo "<td>".$theme_collect->foundername."</td>" ;
                                echo "<td>".$theme_collect->created_at."</td>" ;
                            echo "</tr>" ;
                            $id++;
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/theme_js.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/theme_css.css">

@endsection