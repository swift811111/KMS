@extends('MainBoard')
@section('BodyContent')
<!-- Modal theme add-->
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

    <div class="themeTitle">主題管理</div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            
            <!-- 新增 合併主題按鈕 -->
            <div class="NewTheme">
                <div class="NewAdd">
                    <img class="addTheme_img NewThemeIcon" src="../resources/assets/image/icon/plus.png" alt="">
                    <button type="button" class="btn btn_theme_create" data-toggle="modal" data-target="#ThemeCreate">新增主題</button>
                    <button type="button" class="btn btn_theme_create" data-toggle="modal" data-target="#ThemeCreate">群組</button>
                    <button type="button" class="btn btn_theme_create" id="delete_theme">刪除</button>
                </div>
            </div>

            <!-- 列出所有主題資料 -->
            <div class="ThemeContainer">
                <form id="delete_theme_form" action="{{ route('delete_theme.delete') }}" method="post">
                {{ csrf_field() }}
                    <table id="ThemeTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
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
                                    echo '<tr class="checkbox_tr">' ;
                                        echo '<td>';  
                                            echo '<input type="checkbox" class="checkbox_td" name="themes_data[]" value="'.$theme_my->unqid.'">';
                                            echo '<div style="position:absolute; height:100%;width:100%; background-color:rgba(0,0,0,0); left:0; top:0;"></div>';
                                        echo '</td>' ;
                                        echo '<td>'.$id.'</td>' ;
                                        echo '<td>'.$theme_my->themename.'</td>' ;
                                        echo '<td>'.$theme_my->foundername.'</td>' ;
                                        echo '<td>'.$theme_my->created_at.'</td>' ;
                                    echo '</tr>' ;
                                    $id++;
                                }
                                ?>

                        </tbody>
                    </table>
                </form>
            </div>
        
            <!-- <div>Icons made by <a href="https://www.flaticon.com/authors/roundicons" title="Roundicons">Roundicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div> -->
        </div>
        
    </div>

</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/theme_js.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/theme_css.css">

@endsection