@extends('MainBoard')
@section('BodyContent')
<!-- text content -->
<div class="content">

    <!-- 文章管理標題 -->
    <div class="article_title">文章管理</div> 
    <div class="article_features">
        <button class="btn btn_article_create" type="button" onclick="window.location='{{ url("create_article_page") }}'">新增文章</button>
        <button class="btn btn_article_create" @click="delete_article" type="button">刪除文章</button>
    </div> 
    
    <!-- 文章列表 -->
    <div class="article_table_container">
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
                <!-- <label for="">搜尋</label> -->
                <input style="border: 1px solid rgba(0,0,0,.15);font-size: .875rem;border-radius: .2rem;padding: .25rem .5rem;" type="text" v-model="search_item.search_text">
                <input type="button" class="cursor btn btn-light" @click="search_submit" value="搜尋">
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" v-model="checkbox_all" name="" id="">
                        <div class="cursor" @click="select_all" style="position:absolute;left:0;top:0;width:100%;height:100%;background-color:rgba(255,255,255,0);"></div>
                    </th>
                    <th class="sorting_style cursor" :class="[arrange_style.article_id]" @click="arrange_data('article_id',arrange_title.article_id)">
                        ID 
                    </th>
                    <th class="sorting_style cursor" :class="[arrange_style.article_title]" @click="arrange_data('article_title',arrange_title.article_title)">
                        文章標題
                    </th>
                    <th class="sorting_style cursor" :class="[arrange_style.article_author]" @click="arrange_data('article_author',arrange_title.article_author)">
                        作者
                    </th>
                    <th>
                        主題
                    </th>
                    <th class="sorting_style cursor" :class="[arrange_style.created_at]" @click="arrange_data('created_at',arrange_title.created_at)">
                        建立日期
                    </th>
                    <th>
                        功能
                    </th>
                </tr>
            </thead>
            <tbody>
                
                <tr v-for="data in article_data">
                    <td>
                        <input type="checkbox" name="article_checkbox[]" v-model="article_delete_array" :value="data.article_unqid">
                    </td>
                    <td>
                        @{{ data.article_id }}
                    </td>
                    <td>
                        @{{ data.article_title }}
                    </td>
                    <td>
                        @{{ data.article_author }}
                    </td>
                    <td>
                        
                    </td>
                    <td>
                        @{{ data.created_at }}
                    </td>
                    <td class="center">
                        <div class="cursor" @click="to_edit_page(data.article_unqid)" style="padding-right:1em;">
                            <img class="article_edit_icon" src="../resources/assets/image/icon/edit.png">
                        </div>
                        <div class="cursor" @click="to_view_page(data.article_unqid)">
                            <img class="article_view_icon" src="../resources/assets/image/icon/preview.png">
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
   
    
</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/article.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/article_css.css">

@endsection