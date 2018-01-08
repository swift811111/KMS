@extends('MainBoard')
@section('BodyContent')
<!-- text content -->
<div class="content">

    <!-- 文章內容 -->
    <div class="article" v-show="show">
        <div class="article_title">
            <div class="features">
                <img class="icon cursor" onclick="window.location='{{ url("article_manage") }}'" src="../resources/assets/image/icon/back.png" alt="Back">
                <button type="button" class="features_btn" @click="to_edit_page('<?= $article_data[0]->article_unqid ?>')"> 編輯 </button>
                <button type="button" class="features_btn" @click="show_click('<?= $article_data[0]->article_unqid ?>')"> 意見 </button>
            </div>
            <h5><?= $article_data[0]->article_title ?></h5>
            <div class="hidden">
                <img class="icon cursor" src="../resources/assets/image/icon/back.png">
                <button type="button"> 編輯 </button>
                <button type="button" class="features_btn" style="margin-right:2em;"> 意見 </button>
            </div>
        </div>

        <div class="text_group">
            <div class="theme_name text_style">
                <span>主題/群組 : </span>
            </div>
            <div class="summary text_style">
                <span>摘要 : </span><?= $article_data[0]->article_summary ?>
            </div>
            <div class="editor text_style">
                <span>編者 : </span><?= $article_data[0]->article_editor ?>
            </div>
            <div style="display:flex;justify-content: space-between;">
                <div class="author text_style">
                    <span>作者 : </span><?= $article_data[0]->article_author ?>
                </div>       
                <div class="created_at text_style">
                    <span><img class="date_icon" src="../resources/assets/image/icon/date.png" alt=""></span><?= $article_data[0]->created_at ?>
                </div>
            </div>
        </div>

        <div class="article_content">
            <?= $article_data[0]->article_content ?>
        </div>
    </div>

    <!-- 意見 -->
    <div class="opinion" v-show="!show">
        <div class="article_title">
            <div class="features">
                <img class="icon cursor" onclick="window.location='{{ url("article_manage") }}'" src="../resources/assets/image/icon/back.png" alt="Back">
                <button type="button" class="features_btn" @click="show_click('<?= $article_data[0]->article_unqid ?>')"> 文章 </button>
            </div>
            <h5><?= $article_data[0]->article_title ?></h5>
            <div class="hidden">
                <img class="icon cursor" src="../resources/assets/image/icon/back.png">
                <button type="button" class="features_btn" style="margin-right:2em;"> 文章 </button>
            </div>
        </div>

        <div class="show_comments">
            <div class="comments_block" v-for="data in opinion_data">
                <div class="user_data">
                    <div class="username">
                       <img class="user_data_icon" src="../resources/assets/image/icon/user.png" alt="user"> @{{ data.username }}
                    </div>
                    <div class="comment_date">
                       <img class="user_data_icon" src="../resources/assets/image/icon/date.png" alt="date"> @{{ data.created_at }}
                    </div>
                </div>
                <div class="comment_content">
                    @{{ data.opinion_content }}
                </div>
                <div class="reply">
                    <button type="" class="reply_btn">讚</button>
                    <button type="" class="reply_btn">回復</button>
                </div>
            </div>
        </div>

        <div class="comments">
            <form action="{{ route('create_new_opinion.create') }}" method="post">
            {{ csrf_field() }}
                <textarea id="comments_textarea" ref="comments_textarea" autofocus placeholder="留點意見吧..."></textarea>
                <input type="text" class="none" ref="article_unqid" value="<?= $article_data[0]->article_unqid ?>">
            </form>
        </div>
        <div class="comments_btn">
            <button type="button" class="btn btn-primary" @click="sent_opinion"> 留言 </button>
        </div>
    </div>
    
</div>

@endsection

@section('BodyScriptCss')

<script type="text/javascript" src="../resources/assets/js/index_js/article_view_js.js"></script>
<link rel="stylesheet" href="../resources/assets/sass/index_scss/article_css.css">
<link rel="stylesheet" href="../resources/assets/sass/index_scss/article_view_css.css">

@endsection