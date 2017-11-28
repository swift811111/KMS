$(document).ready(function() {
    $("body").show();
    $(".article_input_table").css("height", $(window).height() - $(".menu").height() - $(".article_navbar_group").height() - $(".themeTitle").height() - $(".themeTitle").height() / 3 - 2);
    //送出表單
    $(".article_input_btn").click(function() {
        $(".article_input_form").submit();
    });


    //內容大小
    $('.classification_container').css("height", $('.content').height() - $('.article_navbar_group').height() * 3 + 10);
    $('.classifications_content').css("height", $('.classification_container').height() - $('.classifications_title').height() - 50);
    $('.theme_menu').css("height", $('.classifications_content').height() + 50);

    //使選擇的checkbox變色
    // $(".childLevel").click(function() {
    //     if ($(this).find('.cls_checkbox').is(':checked')) {
    //         $(this).find('.cls_checkbox').prop("checked", false);
    //     } else {
    //         $(this).find('.cls_checkbox').prop("checked", true);
    //     }
    //     console.log('checkbox');
    // });

    //設定兩個textarea的大小
    var input_group_width = $('.content').width() - 5;
    var input_group_height = $(window).height() - $(".menu").height() - $(".article_navbar_group").height() - $(".themeTitle").height() * 3 - $(".article_input_text_group").height() - 30;
    var input_opinion_width = $('.content').width() - 5;
    var input_opinion_height = $(window).height() - $(".menu").height() - $(".article_navbar_group").height() - $(".themeTitle").height() * 3 - 15;
    CKEDITOR.replace('article-ckeditor', {
        width: input_group_width,
        height: input_group_height,
    });

    CKEDITOR.replace('opinion-ckeditor', {
        width: input_opinion_width,
        height: input_opinion_height,
    });

});

var input_article = new Vue({

    el: '.content',
    data: {

        show_content: true,
        show_opinion: false,
        show_cls: false,
        navbar_color_name: "content_navbar",
        navbar_color: "navbar_color",

        //----------------------------------------分類
        bookmark_color: "bookmark_color",

        themes_my: [], //主題列表
        themes_bookmark: [], //檢視是否存在重複的主題頁籤
        themes_bookmark_unqid: [], //檢視主題unqid
        theme_group_data: "", //群組資料

        //父分類
        classification_names: [], //儲存所有父分類資料
        fathername: "",
        classification_name: "",
        unqid: "",

        //子分類
        childclassifications: [],
        cls_checkbox_array: [],

    },
    methods: {

        show_false: function() {
            this.show_content = false;
            this.show_opinion = false;
            this.show_cls = false;
        },
        article_input_table_click: function() {
            this.show_false();
            this.navbar_color_name = 'content_navbar';
            this.show_content = true;
            console.log(this.navbar_color_name);
        },
        opinion_table_click: function() {
            this.show_false();
            this.navbar_color_name = 'opinion_navbar';
            this.show_opinion = true;
        },
        cls_table_click: function() {
            this.show_false();
            this.navbar_color_name = 'cls_navbar';
            this.show_cls = true;
        },

        //-----------------------------------分類
        //拿分類的資料
        get_classification_data: function() {
            let self = this;
            axios.get('data/classification_data/' + self.fathername)
                .then(function(response) {
                    self.classification_names = response.data[0];
                    self.childclassifications = response.data[1];
                })
                .catch(function(response) {
                    console.log("error");
                });
        },

        //將分類加入頁籤並更新頁籤裡面的分類資料
        add_to_bookmark: function(name, unqid) {

            let self = this;

            //  find name index
            var name_index = $.map(this.themes_bookmark, function(item, index) {
                return item.name
            }).indexOf(name);

            if (name_index >= 0) {
                // alert('已經存在該項目');
                this.fathername = unqid;
                this.get_classification_data()
            } else {
                this.themes_bookmark.push({ name: name, unqid: unqid });
                this.themes_bookmark_unqid.push(unqid);
                this.fathername = unqid;
                //顯示分類

                this.get_classification_data()

            }
            console.log(this.themes_bookmark);
        },

        //更新頁籤裡面的分類資料
        bookmark_click: function(unqid) {
            this.fathername = unqid;
            if (event.target.checked) {
                console.log("red");
            }
            this.get_classification_data()
        },

        //將頁籤移除 並檢查頁籤的位子 決定要顯示哪個頁籤的分類資料
        remove_from_bookmark: function(removeItem) {

            let index = this.themes_bookmark.indexOf(removeItem)
            let unqid_index = this.themes_bookmark_unqid.indexOf(removeItem.unqid)
            this.themes_bookmark.splice(index, 1);
            this.themes_bookmark_unqid.splice(unqid_index, 1);
            if (this.themes_bookmark.length >= 1) {

                if (index == this.themes_bookmark.length) {
                    this.fathername = this.themes_bookmark[index - 1].unqid;
                    this.get_classification_data()
                } else {
                    this.fathername = this.themes_bookmark[index].unqid;
                    this.get_classification_data()
                }
            } else {
                this.fathername = null;
                this.get_classification_data()
            }
        },

        //點擊群組
        click_theme_group: function(theme_name_json) {
            //取出資料 去空白和不必要的符號 放進array
            var theme_name_array = theme_name_json.replace(/"|\[|\]/g, '').replace(/[ ]/g, "").trim().split(",");
            console.log(theme_name_array);

        },
        //得到群組資料
        get_theme_group_data: function() {
            let self = this;
            axios.get('data/theme_group_data')
                .then(function(response) {
                    self.theme_group_data = response.data;
                    console.log(self.theme_group_data);
                })
                .catch(function(response) {
                    console.log("error");
                });
        },

        click_childcls: function(data, cls, $event) {
            console.log("父分類 : " + data + " 子分類 : " + cls);
            if (event.target.checked) {
                console.log('checked');
            } else {
                console.log('no checked');
            }
        },
        cls: function() {
            console.log(this.cls_checkbox_item);
        },

        //初始化左邊頁面的主題資料
        init: function() {
            let self = this;
            axios.get('data/theme_data')
                .then(function(response) {
                    self.themes_my = response.data;
                })
                .catch(function(response) {
                    console.log(error);
                });
        },
    },
    mounted: function() {
        this.init()
        this.get_theme_group_data()

    },
    updated: function() {

        // // 點選外圍div就可以讓子分類勾選
        // $(".childLevel").click(function() {
        //     if ($(this).find('.cls_checkbox').is(':checked')) {
        //         // $(this).find('.cls_checkbox').prop("checked", false);
        //         $(this).css("background-color", "rgb(218, 78, 78)");


        //     } else {
        //         // $(this).find('.cls_checkbox').prop("checked", true);
        //         $(this).css("background-color", "#8bc4bc");
        //     }
        // });
    }

});