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
                    
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">分類名稱</label>
                            <input type="text" autofocus class="form-control" name="classification_name" ref="classification_name" id="classification_name" @keyup.enter="enter_to_new_cls('cls_f')" required >
                            <small class="text-muted"> 請輸入分類名稱 </small>    
                        </div>
                        <input type="hidden" name='classification_foundername' ref="classification_foundername" value="{{ Auth::user()->username }}">
                  
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
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">子分類名稱</label>
                        <input type="text" class="form-control" autofocus name="cls_name" ref="cls_name" id="cls_name" @keyup.enter="enter_to_new_cls('cls_c')" required >
                        <small class="text-muted"> 請輸入子分類名稱 </small>    
                    </div>
                    <input type="hidden" name='cls_foundername' ref="cls_foundername" value="{{ Auth::user()->username }}">
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

            <!--合併主題列表區-->
            <div class="theme_menu_title">
                合併主題列表
            </div>
            <div class="themename" v-for="item in theme_group_data" @click="click_theme_group(item.theme_group_unqid)">
                <div class="themePaggingNamme" >@{{ item.theme_group_name }}</div>
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
            <!-- 編輯所屬主題的分類 -->
            <cls-value v-on:get="give_cls_c('<?php if(isset($themesname))echo $themesname ; ?>','<?php if(isset($themesnuqid))echo $themesnuqid ;?>')"></cls-value>       
        </div>

        <div class="classifications">
            
            <!-- 顯示以點選的主題頁籤 -->
            <div class="classifications_title" id="classifications_title">
                <div v-for="(item,index) in themes_bookmark"  :id="item.unqid"  class="ThemePageTitle cursor" :class="[(fathername == item.unqid) ? bookmark_color : bookmark_color_orgin]" >
                    <div class="ellipsis" @click="bookmark_click(item.unqid)" :title="item.name">
                        @{{ item.name }}
                    </div>
                    <img class="themes_bookmark_cancel" @click="remove_from_bookmark(item)" src="../resources/assets/image/icon/cancel.png" alt="">
                </div>
            </div>
            <!-- 新增 & 刪除 父分類按鈕 -->
            <div class="classification_btn_group" v-show="show">
                <img class="icon_add cursor"  data-toggle="modal" data-target="#ClassificationCreate" >
                <img class="icon_delete cursor"  @click="delete_father_cls" >
                <!-- <button class="classification_btn" data-toggle="modal" data-target="#ClassificationCreate">新增分類</button> -->
                <!-- <button class="classification_btn" @click="delete_father_cls">刪除分類</button> -->
            </div>
            <!-- 各個主題頁籤裡面的內容 -->
            <div class="classifications_content">        
                <div class="classifications_content_page"> 
                    <!-- 列出父分類 -->
                    <div class="level" v-for="(item,index) in classification_names">
                    <!-- background-color: rgba(189, 189, 189, 0.3); -->
                        <div class="center" style="padding:0 0.3em;border-bottom: 1px solid rgba(189, 189, 189, 0.5);width:100%;">
                            <!--  checkbox  -->
                            <div style="width:5%;" class="father_cls_checkedbox center">
                                <input type="checkbox" :id="item.unqid" v-model="father_checkedunqid" :value="item.unqid">
                            </div>
                            <!--  父標題  -->
                            <div class="preLevel"><label class="cursor center ellipsis" :for="item.unqid" style="margin-bottom:0;">@{{ item.name }}</label></div>
                            <!-- 子分類及新增子分類功能 -->
                            <!--  新增子分類按鈕 -->
                            <div class="childLevel-img">
                                <img class="classification_btn" src="../resources/assets/image/icon/plus.png" data-toggle="modal" data-target="#Child_Classification_Add" @click="Input_Father_Classification_Unqid(item.unqid)">
                            </div>
                        </div>

                        <!--  列出子分類  -->
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" clsitem in childclassifications[index]">
                                <div v-show="edit_text==clsitem.unqid" class="cls_name" :title="clsitem.name">
                                    <input type="text" @keyup.enter="edit_cls_c(clsitem.unqid)" v-model="cls_c_name" :value="clsitem.name">
                                </div>
                                <div v-show="edit_text!=clsitem.unqid" @dblclick="[edit_text=clsitem.unqid,cls_c_name=clsitem.name]" class="cls_name" :title="clsitem.name">
                                    @{{ clsitem.name }}
                                </div>
                                <!-- <button type="button"@click="clk">456</button> -->
                                <div style="width:23%;" class="center">
                                    <img v-show="edit_text==clsitem.unqid" class="child_classification_delete" @click="edit_text=''"  src="../resources/assets/image/icon/forbidden-sign.png">
                                    <img v-show="edit_text!=clsitem.unqid" class="child_classification_delete" @click="delete_child_cls(clsitem.unqid)"  src="../resources/assets/image/icon/forbidden-sign.png">
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