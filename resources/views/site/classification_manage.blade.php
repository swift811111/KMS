@extends('MainBoard')
@section('BodyContent')
<!-- text content -->
<div class="content">
    <div class="themeTitle">分類管理</div>
    <div class="classification_container">
        <div class="theme_menu">

            <!-- 測試用 -->
            <div class="theme_menu_title">
                合併主題列表
            </div>
            <div class="themename">
                <div class="themePaggingNamme">測試</div>
                <img class="themePaggingAdd" src="../resources/assets/image/icon/add.png" alt="">
            </div>
            

            <div class="theme_menu_title">
                主題列表
            </div>
            <?php
                // foreach ($themes_my as $theme_my)
                // {
                //     echo '<div class="themename" id="'.$theme_my->unqid.'" name="'.$theme_my->themename.'">' ;
                //         echo $theme_my->themename ;
                //     echo '</div>' ;   
                // }
            ?>
            <div class="themename" v-for="item in items" @click="appear(item)"> 
                <div class="themePaggingNamme">@{{ item.themename }}</div>
                <img class="themePaggingAdd" src="../resources/assets/image/icon/add.png" alt="">
            </div>
        </div>
        <div class="classifications">
            <div class="classifications_title" id="classifications_title">
                <div class="ThemePageTitle" v-on:click="appearId">測試</div>
            </div>
            <div class="classifications_content">

                <div class="classifications_content_page" v-show="show">
                    <div class="level">
                        <div class="preLevel">預設層級</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 9">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類一</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 3">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類二</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 6">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類三</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 6">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類四</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 6">測試</div>
                            <div class="childLevel-img center"  >
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類四</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 6">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="level">
                        <div class="preLevel">分類四</div>
                        <div class="childLevelContainer">
                            <div class="childLevel center" v-for=" n in 6">測試</div>
                            <div class="childLevel-img center">
                                <img class="levelAdd" src="../resources/assets/image/icon/plus.png" alt="">
                            </div>
                        </div>  
                    </div>

                    <!-- clickId : @{{ clickItemId }} -->
                </div>
                <child v-for="n in range"></child>
            </div>
        </div>    
    </div>                                                       
</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/classification_manage_js.js"></script>
<script src="https://unpkg.com/vue"></script> 
<script>
// var Vue = require('vue');
// Vue.use(require('vue-resource'));

Vue.component('child', {
    // 声明 props
    // props: ['messageq'],
    // 就像 data 一样，prop 可以用在模板内
    // 同样也可以在 vm 实例中像“this.message”这样使用
    template: '<div class="ThemePageTitle" v-on:click="addpag">456</div>',
    data: function() {
        return {
            message: 123
        }
    },
    methods: {
        app: function() {
            alert('asd');
        }
    }
})

var classificationPagging = new Vue({
    el: '.classification_container',
    data: {
        show: true,
        exists: [],
        clickItemId: " ",
        message: "hello123",
        pos: [],
        items: {!! $themes_my !!},
        range: 0,
    },
    methods: {
        toggle: function() {
            this.show = !this.show;
        },
        appearId: function() {
            console.log('46');
        },
        appear: function(item) {
            if (this.exists.indexOf(item.id) >= 0) {
                alert('已經存在該項目');
                this.clickItemId = item.id;
            } else {
                var namePag = document.createElement("div");
                namePag.setAttribute("class", "ThemePageTitle");
                namePag.innerHTML = item.themename;
                $('.classifications_title').append(namePag);
                this.exists.push(item.id);
                this.clickItemId = item.id;
                // this.Item = item;
                // <div class="ThemePageTitle">item.themename</div>
                // var namePag2 = document.createElement("child");
                // $('.classifications_title').append(namePag2);
            }
        },
        addpag: function() {
            this.range += 1;
        },
        // loadPersonalObjectives: function() {
        //     Vue.http.get('/data').then((response) => { this.pos = response.data; });
        // }
    }
});
</script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/classification_manage_css.css">

@endsection