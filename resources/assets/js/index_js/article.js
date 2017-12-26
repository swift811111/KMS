$(document).ready(function() {
    $("body").show();

    $(".content").css("height", $(window).height() - $(".menu").height() - 5);
});

var article = new Vue({
    el: '.content',
    data: {
        article_data: [],
        perpage: 3, //每頁要顯示幾筆資料
        current_page: 1, //現在在第幾頁
        disabled: "disabled",
        active: "active",
        pages: "", //總共有幾頁
        cursor: "cursor",
        data_length: [], //總共有幾筆資料
        page_star: 1,
        page_end: 10,
        article_delete_array: [],

        //排列的css style
        arrange_style: {
            article_id: 'sorting_desc',
            article_title: 'no_arrange',
            article_author: 'no_arrange',
            created_at: 'no_arrange',
        },
        ////排列方式 欄位狀態
        data_arrange_method: {
            column: "article_id", //欄位名稱
            arrangement: "DESC", //排列方式
        },
        //全選 反選
        arrange_title: {
            article_id: 'DESC', //預設為ID asc
            article_title: 'no_arrange',
            article_author: 'no_arrange',
            article_theme: 'no_arrange',
            created_at: 'no_arrange',
        },
        checkbox_all: false,

    },
    methods: {
        get_article_data: function() { //依據每頁要顯示幾筆 還有第幾頁來拿資料

            let self = this;
            axios.get('data/article_data/' + self.perpage + "/" + self.current_page + "/" + self.data_arrange_method.column + "/" + self.data_arrange_method.arrangement)
                .then(function(response) {
                    self.article_data = response.data;
                    self.page_star_to_end();
                    self.get_all_pages();
                })
                .catch(function(response) {
                    console.log("error");
                });


        },
        get_all_pages: function() { //依據每頁要顯示幾筆資料得到 需要顯示幾頁
            let self = this;
            axios.get('data/article_all_data')
                .then(function(response) {
                    self.data_length = response.data.length;
                    self.pages = Math.ceil(response.data.length / self.perpage);
                })
                .catch(function(response) {
                    console.log("error");
                });
        },
        select_all: function() {
            var select = [];

            if (this.article_delete_array.length < this.article_data.length) {
                this.article_data.forEach(function(value) {
                    select.push(value.article_unqid);
                });
                this.article_delete_array = select;
                this.checkbox_all = true;
            } else {
                this.article_delete_array = [];
                this.checkbox_all = false;
            }

        },
        to_edit_page: function(data) {
            // this.edit_data = data;
            // console.log(this.edit_data);
            window.location = '/KMS/public/article_edit_data' + data;
        },
        to_view_page: function(data) {
            // this.edit_data = data;
            // console.log(this.edit_data);
            window.location = '/KMS/public/article_view_data' + data;
        },
        delete_article: function() {
            let self = this;
            axios.post('post/delete_article', {
                    article_delete_array: self.article_delete_array,
                })
                .then(function(response) {
                    console.log(response);
                    self.get_article_data();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        go_to_page: function(page) { //選擇第幾頁
            this.current_page = page;
            this.get_article_data();


        },
        last_page: function() { //上一頁
            if (this.current_page - 1 >= 1) {
                this.current_page = this.current_page - 1;
                this.get_article_data();

            }
        },
        next_page: function() { //下一頁
            if (this.current_page + 1 <= parseInt(this.pages)) {
                this.current_page = this.current_page + 1;
                this.get_article_data();

            }
        },
        //顯示第幾筆 到 第幾筆 
        page_star_to_end: function() {
            if (this.current_page == 1) { //第一頁
                this.page_star = 1;
                if (this.article_data.length < this.perpage) {
                    this.page_end = this.article_data.length;
                } else {
                    this.page_end = this.perpage;
                }
            } else if (this.current_page != this.pages) { //中間頁
                this.page_star = (this.current_page - 1) * this.perpage + 1
                this.page_end = this.current_page * this.perpage;
            } else { //最後一頁
                this.page_star = ((this.current_page - 1) * this.perpage) + 1;
                this.page_end = this.data_length;
            }
        },
        //每頁要顯示幾筆資料
        onchange: function() {
            this.current_page = 1;
            this.pages = Math.ceil(this.data_length / this.perpage);
            this.get_article_data();
        },

        //設定欄位箭頭顯示asc or desc of define
        arrange_style_fnc: function(style_name, style) {
            this.$set(this.arrange_style, 'article_id', 'no_arrange');
            this.$set(this.arrange_style, 'article_title', 'no_arrange');
            this.$set(this.arrange_style, 'article_author', 'no_arrange');
            this.$set(this.arrange_style, 'created_at', 'no_arrange');
            this.$set(this.arrange_style, style_name, style);
        },

        //設定排序時的數值
        set_arrange_data: function(arrange_name, arrange_method, arrange_style) {
            this.arrange_title.article_id = 'no_arrange';
            this.arrange_title.article_title = 'no_arrange';
            this.arrange_title.article_author = 'no_arrange';
            this.arrange_title.article_theme = 'no_arrange';
            this.arrange_title.created_at = 'no_arrange';
            this.$set(this.arrange_title, arrange_name, arrange_method);
            this.$set(this.data_arrange_method, 'column', arrange_name);
            this.$set(this.data_arrange_method, 'arrangement', arrange_method);
            this.arrange_style_fnc(arrange_name, arrange_style);
            this.get_article_data();
        },

        //對每個欄位做排序
        arrange_data: function(arrange_name, arrange_value) {
            if (arrange_value == 'no_arrange') {
                this.set_arrange_data(arrange_name, 'ASC', 'sorting_asc');
                console.log('column : ' + this.data_arrange_method.column);
            } else if (arrange_value == 'ASC') {
                this.set_arrange_data(arrange_name, 'DESC', 'sorting_desc');
            } else if (arrange_value == 'DESC') {
                this.set_arrange_data(arrange_name, 'ASC', 'sorting_asc');
            }

        },
    },
    computed: {

    },
    mounted: function() {
        this.get_article_data();
        this.get_all_pages();
    },
});