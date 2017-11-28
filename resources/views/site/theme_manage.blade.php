@extends('MainBoard')
@section('BodyContent')

<!-- text content -->
<div class="content">

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

    <!-- Modal theme_group_modal-->
    <div class="modal fade" id="theme_group_modal" tabindex="-1" role="dialog" aria-labelledby="my_theme_group_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="my_theme_group_modal">建立群組</h4>
                </div>
                <div class="modal-body ">
                    <div class="theme_group_container">
                        <div class="theme_group_inputName">
                            <h5>群組名稱 : </h5>
                            <input type="text" name="theme_group_name" id="theme_group_name" ref="theme_group_name">
                        </div>
                        <div class="theme_group_selecter_container">
                            <div v-for="selecter in theme_data" class="theme_group_selecter">
                                <input type="checkbox" v-model="theme_select_array" :value="selecter.unqid" >
                                <div class="selecter_name">@{{ selecter.themename }}</div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" @click="create_theme_group" id="theme_group_btn" class="btn btn-primary" data-dismiss="modal">建立</button>
                </div>
            </div>
        </div>
    </div>

    <div class="themeTitle">
        <select v-model="theme_value" v-on:change="onchange">
            <option value="theme">主題管理</option>
            <option value="group">群組管理</option>
        </select>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            
            <!-- 新增 合併主題按鈕 -->
            <div class="NewTheme">
                <div class="NewAdd" v-show="theme_show">
                    <img class="addTheme_img NewThemeIcon" src="../resources/assets/image/icon/plus.png" alt="">
                    <button type="button" class="btn btn_theme_create" data-toggle="modal" data-target="#ThemeCreate">新增主題</button>
                    <button type="button" class="btn btn_theme_create" id="delete_theme">刪除</button>
                </div>
                <div class="NewAdd" v-show="theme_group_show">
                    <img class="addTheme_img NewThemeIcon" src="../resources/assets/image/icon/plus.png" alt="">
                    <button type="button" class="btn btn_theme_create" data-toggle="modal" data-target="#theme_group_modal">新增群組</button>
                    <button type="button" class="btn delete_theme_group" id="delete_theme_group">刪除</button>
                </div>
            </div>

            <!-- 列出所有主題資料 -->
            <div class="ThemeContainer" v-show="theme_show">
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
            
            <!-- 列出所有群組資料 -->
            <div class="ThemeContainer" v-show="theme_group_show">
                <form id="delete_theme_group_form" action="{{ route('delete_theme_group.delete') }}" method="post">
                {{ csrf_field() }}
                    <table id="ThemeGroupTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                            foreach ($themes_group_my as $theme_group_my)
                            {
                                echo '<tr class="checkbox_tr">' ;
                                    echo '<td>';  
                                        echo '<input type="checkbox" class="checkbox_td" name="themes_group_data[]" value="'.$theme_group_my->unqid.'">';
                                        echo '<div style="position:absolute; height:100%;width:100%; background-color:rgba(0,0,0,0); left:0; top:0;"></div>';
                                    echo '</td>' ;
                                    echo '<td>'.$id.'</td>' ;
                                    echo '<td>'.$theme_group_my->name.'</td>' ;
                                    echo '<td>'.$theme_group_my->foundername.'</td>' ;
                                    echo '<td>'.$theme_group_my->created_at.'</td>' ;
                                echo '</tr>' ;
                                $id++;
                            }
                            ?>
                            <!-- <tr class="checkbox_tr" v-for="(item,index) in theme_data">
                                <td>    
                                    <input type="checkbox" name="themes_group_data[]" class="checkbox_td" :value="item.unqid">
                                </td>
                                <td>    
                                    @{{ index+1 }}
                                </td>
                                <td>
                                    @{{ item.name }}
                                </td>
                                <td>
                                    @{{ item.foundername }}
                                </td>
                                <td>
                                    @{{ item.created_at }}
                                </td>
                            </tr> -->
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