@extends('MainBoard')
@section('BodyContent')
<!-- text content -->
<div class="content">

    <div class="classification_container">
        <div class="theme_menu">
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
            <div class="themename" v-for="item in items" @click="appear(item)">@{{ item.themename }}</div>
        </div>
        <div class="classifications">
            <div class="classifications_title" id="classifications_title">
                <div class="ThemePageTitle" v-on:click="appearId">test</div>
            </div>
            <div class="classifications_content">

                
                <!-- <div class="themename" v-for="item in items" @click="appear(item)">@{{ item.themename }}</div>            -->
                <div class="classifications_content_page" v-show="show">
                    clickId : @{{ clickItemId }}
                </div>
                <child @click="appearId"></child>
                
            </div>
        </div>    
    </div>
    
</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/classification_manage_js.js"></script>
<script src="https://unpkg.com/vue"></script> 
<script>
    Vue.component('child', {
    // 声明 props
        // props: ['messageq'],
        // 就像 data 一样，prop 可以用在模板内
        // 同样也可以在 vm 实例中像“this.message”这样使用
        template: '<button v-on:click="app">@{{ message }}</button>',
        data: function(){
            return {
                message: 123
            }
        },
        methods:{
            app: function(){
                alert('asd') ;
            }
        }
    })

    var classificationPagging = new Vue({
        el: '.classification_container' ,
        data: {
            show: true ,
            exists: [] ,
            clickItemId:" " ,
            message: "hello123" ,
            items: {!! $themes_my !!}
        },
        methods: {
            toggle: function(){
                this.show = !this.show ;
            },
            appearId: function(){
                console.log('46') ;
            },
            appear: function(item){
                if (this.exists.indexOf(item.id) >= 0) {
                    alert('已經存在該項目');
                    this.clickItemId = item.id ;
                } else {
                    var namePag = document.createElement("div");
                    namePag.setAttribute("class", "ThemePageTitle");
                    namePag.innerHTML = item.themename;
                    $('.classifications_title').append(namePag);
                    this.exists.push(item.id);
                    this.clickItemId = item.id ;
                    this.Item = item ;
                    // <div class="ThemePageTitle">item.themename</div>
                    var namePag2 = document.createElement("child");
                    $('.classifications_title').append(namePag2);
                }
            }
        }
    });
</script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/classification_manage_css.css">

@endsection