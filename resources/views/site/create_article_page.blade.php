@extends('MainBoard')
@section('BodyContent')

<!-- text content -->
<div class="content">
    
    <div class="themeTitle">新增文章</div>
    <nav class="article_navbar_group">

        <div class="flex navbar_side">
            <div class="article_navbar" :class="[(navbar_color_name == 'content_navbar') ? navbar_color : null]" @click="article_input_table_click">
                內容
            </div>
            <div class="article_navbar" :class="[(navbar_color_name == 'cls_navbar') ? navbar_color : null]" @click="cls_table_click">
                分類
            </div>    
        </div>

        <div class="flex">
            <input type="button" class="cursor article_cancel_btn" value="取消">
            <input type="button" class="cursor article_input_btn" value="送出文章">
        </div>
    </nav>
    <!-- 輸入表格 -->
    <form class="article_input_form" action="{{ route('create_article.create') }}" method="post">
    {{ csrf_field() }}
        <div class="article_input_table" v-show="show_content">

            <div class="article_input_text_group">
                <div>
                    <div class="article_input_group">
                        <label for="article_title" class="label_text">標題 :</label>
                        <input type="text" name="article_title" class="article_title">
                    </div>

                    <div class="article_input_group">
                        <label for="article_author" class="label_text">作者 :</label>
                        <input type="text" name="article_author" class="article_author" disabled value="{{Auth::user()->username}}">
                    </div>
                    <div class="article_input_group">
                        <label for="article_date" class="label_text" >日期 :</label>
                        <input type="date" name="article_date" class="article_date" value="<?php echo date('Y-m-d');?>">
                    </div>
                </div>

                <div>
                    <div class="article_input_group">
                        <label for="article_source" class="label_text">來源 :</label>
                        <input type="text" name="article_source" class="article_source">
                    </div>

                    <div class="article_input_group">
                        <label for="article_summary" class="label_text">摘要 :</label>
                        <input type="text" name="article_summary" class="article_summary">
                    </div>
                </div>
            </div>
            
            <!-- textarea -->
            <textarea id="article-ckeditor" name="article_textarea"></textarea>
                
        </div>

        <!-- 分類group -->
        <div class="cls_table" v-show="show_cls">

            <div class="classification_container">
                <div class="theme_menu">

                    <!-- 測試用 合併主題列表區-->
                    <div class="theme_menu_title">
                        合併主題列表
                    </div>
                    <div class="themename" v-for="item in theme_group_data" @click="click_theme_group(item.theme_name_json)">
                        <div class="themePaggingNamme" >@{{ item.name }}</div>
                        <img class="themePaggingAdd" src="../resources/assets/image/icon/add.png" alt="">
                    </div>
                    

                    <div class="theme_menu_title">
                        主題列表
                    </div>
                    <!-- 在左側列出所有主題 -->
                    <div class="themename" v-for="item in themes_my" @click="add_to_bookmark(item.themename, item.unqid)"> 
                        <div class="themePaggingNamme ellipsis" :title="item.name">@{{ item.themename }}</div>
                        <img class="themePaggingAdd" src="../resources/assets/image/icon/add.png" alt="">
                    </div>

                </div>

                <div class="classifications">
                    
                    <!-- 顯示以點選的主題頁籤 -->
                    <div class="classifications_title" id="classifications_title">
                        <div v-for="(item,index) in themes_bookmark"  :id="item.unqid" :class="[(fathername == item.unqid) ? bookmark_color : null]" class="ThemePageTitle cursor" >
                            <div class="ellipsis" @click="bookmark_click(item.unqid)" :title="item.name">
                                @{{ item.name }}
                            </div>
                            <img class="themes_bookmark_cancel" @click="remove_from_bookmark(item)" src="../resources/assets/image/icon/cancel.png" alt="">
                        </div>
                    </div>

                    <!-- 各個主題頁籤裡面的內容 -->
                    <div class="classifications_content">
                        <div class="classifications_content_page">

                            <!-- 列出父分類 -->
                            <div class="level" v-for="(item,index) in classification_names">

                                <!-- 父分類名稱 -->
                                <div class="preLevel">@{{ item.name }}</div>

                                <!-- 子分類 -->
                                <div class="childLevelContainer">
                                    <div class="childLevel center cursor" v-for=" clsitem in childclassifications[index]"  @click="click_childcls(item.name,clsitem.name)">
                                        <div class="cls_name" :title="clsitem.name">
                                            <input type="checkbox" class="cls_checkbox" v-model="cls_checkbox_array" name="cls_checkbox[]" :value="[item.unqid+clsitem.unqid]">
                                            @{{ clsitem.name }}
                                        </div>
                                    </div>
                                </div>

                            </div>  
                            <!-- 傳送checkbox的值  -->
                            <input type="text" class="none" name="checkbox_value"  v-model="cls_checkbox_array">
                            <!-- 傳送有用到的主題標籤  -->
                            <input type="text" class="none" name="bookmark_value"  v-model="themes_bookmark_unqid">
                        </div>
                        
                    </div>
                </div>  

            </div>                  
        </div>
    </form>
</div>

@endsection

@section('BodyScriptCss')

<link rel="stylesheet" href="../resources/assets/sass/index_scss/classification_manage_css.css">
<script type="text/javascript" src="../resources/assets/js/index_js/input_article_js.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/create_article_page_css.css">
<script src=" {{ asset( '/vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
@endsection

