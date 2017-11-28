@extends('MainBoard')
@section('BodyContent')

<!-- text content -->
<div class="content">

    <!-- Modal ClassificationCreate-->
    <div class="modal fade" id="ClassificationCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabelclscreate" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabelclscreate">新增分類</h4>
                </div>
                <div class="modal-body ">
                    <!-- sign up form -->
                    <!-- <form action="{{ route('theme.add') }}" method="post" id="ClassificationCreateForm" role="form"> -->
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">分類名稱</label>
                            <input type="text" class="form-control" name="classification_name" ref="classification_name" id="classification_name" placeholder="" required >
                            <small class="text-muted"> 請輸入分類名稱 </small>    
                        </div>
                        <input type="hidden" name='classification_foundername' ref="classification_foundername" value="{{ Auth::user()->username }}">
                    <!-- </form>        -->
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" @click="new_classification" data-dismiss="modal">關閉</button> -->
                    <button type="button" id="classification_create_btn" @click="new_classification" data-dismiss="modal" class="btn btn-primary" >新增</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Child_Classification_Add-->
    <div class="modal fade" id="Child_Classification_Add" tabindex="-1" role="dialog" aria-labelledby="myModalLabelclsadd" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabelclsadd">新增子分類</h4>
                </div>
                <div class="modal-body ">
                    <!-- sign up form -->
                    <!-- <form action="{{ route('theme.add') }}" method="post" id="Child_Classification_AddForm" role="form"> -->
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">子分類名稱</label>
                            <input type="text" class="form-control" name="Child_Classification_add_name" ref="Child_Classification_add_name" id="Child_Classification_add_name" placeholder="" required >
                            <small class="text-muted"> 請輸入子分類名稱 </small>    
                        </div>
                        <input type="hidden" name='Child_Classification_add_foundername' ref="Child_Classification_add_foundername" value="{{ Auth::user()->username }}">
                    <!-- </form>        -->
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" @click="new_Child_Classification" data-dismiss="modal">關閉</button> -->
                    <button type="button" id="Child_Classification_create_btn" @click="new_Child_Classification" data-dismiss="modal" class="btn btn-primary" >新增</button>
                </div>
            </div>
        </div>
    </div>

    <div class="themeTitle">分類管理</div>
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
            <?php
                // foreach ($themes_my as $theme_my)
                // {
                //     echo '<div class="themename" id="'.$theme_my->unqid.'" name="'.$theme_my->themename.' ">' ;
                //         echo '<div class="themePaggingNamme">'.$theme_my->themename.'</div>' ;
                //         echo '<img class="themePaggingAdd" src="../resources/assets/image/icon/add.png" alt="">' ;
                //     echo '</div>' ;   
                // }
            ?>
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
                    
                    <!-- 新增父分類按鈕 -->
                    <div class="classification_btn_group" v-show="show">
                        <button class="classification_btn" data-toggle="modal" data-target="#ClassificationCreate">新增分類</button>
                        <button class="classification_btn" @click="delete_father_cls">刪除分類</button>
                    </div>

                    <!-- 列出父分類 -->
                    <div class="level" v-for="(item,index) in classification_names">
                        <div style="width:5%;" class="father_cls_checkedbox center">
                                <input type="checkbox" v-model="father_checkedunqid" :value="item.unqid">
                        </div>
                        <div class="preLevel">@{{ item.name }}</div>

                        <!-- 子分類及新增子分類功能 -->
                        <div class="childLevel-img">
                                <!-- <button class="classification_btn" data-toggle="modal" data-target="#Child_Classification_Add" @click="Input_Father_Classification_Uunqid(item.unqid)">+</button> -->
                                <img class="classification_btn" src="../resources/assets/image/icon/plus.png" data-toggle="modal" data-target="#Child_Classification_Add" @click="Input_Father_Classification_Uunqid(item.unqid)">
                        </div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" clsitem in childclassifications[index]">
                                <div class="cls_name" :title="clsitem.name">
                                    @{{ clsitem.name }}
                                </div>
                                <div style="width:23%;" class="center">
                                    <img class="child_classification_delete" @click="delete_child_cls(clsitem.unqid)"  src="../resources/assets/image/icon/forbidden-sign.png">
                                </div>
                            </div>
                            
                        </div>
                    </div>                    
                </div>
                
            </div>
        </div>  

    </div>                                                       
</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/classification_manage_js.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/classification_manage_css.css">

@endsection