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
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">主題名稱</label>
                        <input type="text" class="form-control" name="theme_name" id="theme_name" ref="theme_name" placeholder="" required >
                        <small class="text-muted"> 請輸入主題名稱 </small>    
                    </div>
                    <div class="form-group">
                        <label for="">建立者</label>
                        <input type="text" class="form-control" name="theme_creater" id="theme_creater" ref="theme_creater" placeholder="" required value='{{ Auth::user()->username }}' >  
                        <small class="text-muted"> 請輸入名字 </small>     
                    </div>     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" id="theme_create_btn" class="btn btn-primary" data-dismiss="modal" @click="create_theme" >建立</button>
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
                    <h4 v-if="group_status=='create'" class="modal-title" id="my_theme_group_modal">建立群組</h4>
                    <h4 v-else class="modal-title" id="my_theme_group_modal">編輯群組</h4>
                </div>
                <div class="modal-body ">
                    <div class="theme_group_container">
                        <div class="theme_group_inputName">
                            <h5>群組名稱 : </h5>
                            <input type="text" name="theme_group_name" ref="theme_group_name" :value="theme_name">
                            <input type="text" class="none" name="theme_group_unqid" ref="theme_group_unqid" :value="theme_unqid">
                        </div>
                        <div class="theme_group_selecter_container">
                            <div v-for="selecter in theme_data" class="theme_group_selecter">
                                <input type="checkbox" v-model="theme_in_group_array" :value="selecter.unqid+selecter.themename" >
                                <div class="selecter_name">@{{ selecter.themename }}</div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="group_status='create'">關閉</button>
                    <button v-if="group_status=='create'" type="button" @click="create_theme" id="theme_group_btn" class="btn btn-primary" data-dismiss="modal">建立</button>
                    <button v-else type="button" @click="update_group" id="theme_group_btn" class="btn btn-primary" data-dismiss="modal">編輯</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit_theme_modal-->
    <div class="modal fade" id="edit_theme_modal" tabindex="-1" role="dialog" aria-labelledby="my_edit_theme_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                   <h4 v-if="theme_various == 'theme'" class="modal-title" id="my_edit_theme_modal">編輯主題名稱</h4>
                   <h4 v-else class="modal-title" id="my_edit_theme_modal">編輯群組名稱</h4>
                </div>
                <div class="modal-body ">
                    <div class="edit_theme_container">
                        <div class="edit_theme_inputName">
                            <h5 v-if="theme_various == 'theme'">主題名稱 : </h5>
                            <h5 v-else>群組名稱 : </h5>
                            <input type="text" name="edit_theme_name" id="edit_theme_name" ref="edit_theme_name" :value="theme_name">
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button v-if="theme_various == 'theme'" type="button" @click="edit_name('theme')" id="edit_theme_btn" class="btn btn-primary" data-dismiss="modal">編輯</button>
                </div>
            </div>
        </div>
    </div>

    <div class="themeTitle">
        <select v-model="theme_change" v-on:change="theme_title_onchange">
            <option value="theme">主題管理</option>
            <option value="group">群組管理</option>
        </select>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            
            <!-- 新增 合併主題按鈕 -->
            <div class="NewTheme">
                <div class="NewAdd" v-if="theme_various == 'theme'">
                    <img class="addTheme_img NewThemeIcon" data-toggle="modal" data-target="#ThemeCreate" src="../resources/assets/image/icon/plus.png" alt="">
                    <button type="button" class="btn btn_theme_create" @click="theme_name ='' " data-toggle="modal" data-target="#ThemeCreate">新增主題</button>
                    <button type="button" class="btn btn_theme_create" @click="delete_theme">刪除</button>
                </div>
                <div class="NewAdd" v-else>
                    <img class="addTheme_img NewThemeIcon"data-toggle="modal" data-target="#theme_group_modal" src="../resources/assets/image/icon/plus.png" alt="">
                    <button type="button" class="btn btn_theme_create" @click="[theme_name ='',group_status='create',theme_in_group_array=[]]" data-toggle="modal" data-target="#theme_group_modal">新增群組</button>
                    <button type="button" class="btn delete_theme_group" @click="delete_theme">刪除</button>
                </div>
            </div>

            <!-- 列出所有主題資料 -->
            <div class="ThemeContainer" v-if="theme_various == 'theme'">
                {{ csrf_field() }}
                <div class="appear_option_and_search_section">
                    <div class="appear_option">
                        <label for="">顯示</label> 
                        <select v-model="perpage" v-on:change="onchange">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                        </select>
                        <label for="">資料</label>
                    </div>
                    <div class="search">
                        <label for="">搜尋</label>
                        <input style="border: 1px solid rgba(0,0,0,.15);font-size: .875rem;border-radius: .2rem;padding: .25rem .5rem;" type="text" name="" id="">
                    </div>
                </div>

                <table id="ThemeTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" v-model="checkbox_all" name="" id="">
                                <div class="cursor" @click="select_all" style="position:absolute;left:0;top:0;width:100%;height:100%;background-color:rgba(255,255,255,0);"></div>
                            </th>
                            <th class="sorting_style cursor" :class="[arrange_style.id]" @click="arrange_data('id',arrange_title.id)">ID</th>
                            <th class="sorting_style cursor" :class="[arrange_style.themename]" @click="arrange_data('themename',arrange_title.themename)">主題名稱</th>
                            <th class="sorting_style cursor" :class="[arrange_style.foundername]" @click="arrange_data('foundername',arrange_title.foundername)">作者</th>
                            <th class="sorting_style cursor" :class="[arrange_style.created_at]" @click="arrange_data('created_at',arrange_title.created_at)">建立日期</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-for="(data,index) in theme_perpage_data">
                            <td>
                                <input type="checkbox" name="article_checkbox[]" v-model="theme_delete_array" :value="data.unqid">
                            </td>
                            <td>
                                @{{ index+1 }}
                            </td>
                            <td>
                                @{{ data.themename }}
                            </td>
                            <td>
                                @{{ data.foundername }}
                            </td>
                            <td>
                                @{{ data.created_at }}
                            </td>
                            <td class="center">
                                <div class="cursor" @click="change_name(data.themename,data.unqid)" style="padding-right:1em;">
                                    <img class="article_edit_icon" data-toggle="modal" data-target="#edit_theme_modal" src="../resources/assets/image/icon/edit.png" title="編輯主題名稱">
                                </div>
                                <div class="cursor">
                                    <form action="{{ route('edit_theme_cls.edit') }}" method="post">
                                    {{ csrf_field() }}
                                        <input type="text" class="none" name="themesname" :value="data.themename">
                                        <input type="text" class="none" name="themesunqid" :value="data.unqid">
                                        <!-- <input type="submit" value="submit" onclick="this.form.submit();"> -->
                                    <img class="article_edit_icon" src="../resources/assets/image/icon/chevron-sign-to-right.png" title="編輯分類">
                                    </form>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>

                <div class="pagination_container">
                    <div class="data_num">
                        顯示第 @{{ page_star }}~@{{ page_end }} ( 共@{{data_length}}筆 )
                    </div>
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item" :class="[(current_page == 1) ? disabled : cursor]" @click="last_page">
                                <a class="page-link" tabindex="-1">上一頁</a>
                            </li>
                            <li class="page-item cursor" v-for="page in pages" :class="[(current_page == page) ? active : null]" @click="go_to_page(page)"><a class="page-link" href="#">@{{ page }}</a></li>
                            <li class="page-item" style="color:#014C8C;" :class="[(current_page == pages) ? disabled : cursor]" @click="next_page">
                                <a class="page-link">下一頁</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- 列出所有群組資料 -->
            <div class="ThemeContainer" v-else>
                <div class="appear_option_and_search_section">
                    <div class="appear_option">
                        <label for="">顯示</label> 
                        <select v-model="perpage" v-on:change="onchange">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                        </select>
                        <label for="">資料</label>
                    </div>
                    <div class="search">
                        <label for="">搜尋</label>
                        <input style="border: 1px solid rgba(0,0,0,.15);font-size: .875rem;border-radius: .2rem;padding: .25rem .5rem;" type="text" name="" id="">
                    </div>
                </div>

                <table id="ThemeTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" v-model="checkbox_all" name="" id="">
                                <div class="cursor" @click="select_all" style="position:absolute;left:0;top:0;width:100%;height:100%;background-color:rgba(255,255,255,0);"></div>
                            </th>
                            <th class="sorting_style cursor" :class="[arrange_style.id]" @click="arrange_data('id',arrange_title.id)">ID</th>
                            <th class="sorting_style cursor" :class="[arrange_style.themename]" @click="arrange_data('themename',arrange_title.themename)">群組名稱</th>
                            <th class="sorting_style cursor" :class="[arrange_style.foundername]" @click="arrange_data('foundername',arrange_title.foundername)">作者</th>
                            <th class="sorting_style cursor" :class="[arrange_style.created_at]" @click="arrange_data('created_at',arrange_title.created_at)">建立日期</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr v-for="(data,index) in theme_perpage_data">
                            <td>
                                <input type="checkbox" name="" v-model="theme_group_delete_array" :value="data.theme_group_unqid">
                            </td>
                            <td>
                                @{{ index+1 }}
                            </td>
                            <td>
                                @{{ data.theme_group_name }}
                            </td>
                            <td>
                                @{{ data.username }}
                            </td>
                            <td>
                                @{{ data.created_at }}
                            </td>
                            <td class="center">
                                <div class="cursor" data-toggle="modal" data-target="#theme_group_modal" @click="edit_group(data.theme_group_name,data.theme_group_unqid)" style="padding-right:1em;">
                                    <img class="article_edit_icon" src="../resources/assets/image/icon/edit.png" title="編輯群組名稱">
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>

                <div class="pagination_container">
                    <div class="data_num">
                        顯示第 @{{ page_star }}~@{{ page_end }} ( 共@{{data_length}}筆 )
                    </div>
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item" :class="[(current_page == 1) ? disabled : cursor]" @click="last_page">
                                <a class="page-link" tabindex="-1">上一頁</a>
                            </li>
                            <li class="page-item cursor" v-for="page in pages" :class="[(current_page == page) ? active : null]" @click="go_to_page(page)"><a class="page-link" href="#">@{{ page }}</a></li>
                            <li class="page-item" style="color:#014C8C;" :class="[(current_page == pages) ? disabled : cursor]" @click="next_page">
                                <a class="page-link">下一頁</a>
                            </li>
                        </ul>
                    </nav>
                </div>
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