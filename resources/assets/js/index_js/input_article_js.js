$(document).ready(function() {
    $("body").show();
    $(".article_input_table").css("height", $(window).height() - $(".menu").height() - $(".article_navbar_group").height() - $(".themeTitle").height() - $(".themeTitle").height() / 3 - 2);
    //送出表單
    $(".article_input_btn").click(function() {
        $(".article_input_form").submit();
    });


    //內容大小
    $('.classification_container').css("height", $('.content').height() - $('.article_navbar_group').height() * 3 + 10);
    $('.classifications_content').css("height", $('.classification_container').height() - $('.classifications_title').height() - 52);
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
    CKEDITOR.replace('article-ckeditor', {
        width: input_group_width,
        height: input_group_height,
    });

});

Vue.component('cls-value', {
    template: '<span class="none"></span>',
    props: ['message'],
    data: function() {
        return {}
    },
    methods: {},
    mounted: function() {
        this.$emit('get');
    },
})

var input_article = new Vue({

    el: '.content',
    data: {

        show_side: {
            show_content: true,
            show_cls: false,
        },

        navbar_color_name: "show_content",
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
        //讓陣列不重複
        onlyUnique: function(value, index, self) {
            return self.indexOf(value) === index;
        },
        //將後端傳來的checkbox資料 讓前端顯示已勾選
        give_cls_c: function(cls_data, theme_data) {
            let self = this;
            var match_theme_array = theme_data.match(/.{1,32}/g); //將字串以32個為一組分成陣列
            var match_cls_array = cls_data.match(/.{1,64}/g); //將字串以64個為一組分成陣列
            console.log('theme: ' + match_theme_array);
            console.log(match_cls_array);
            var unique_theme_array = match_theme_array.filter(this.onlyUnique);
            console.log(' unique : ' + unique_theme_array);
            //themematch_theme_array
            self.fathername = match_theme_array[0];
            unique_theme_array.forEach(function(value) {
                axios.get('data/theme_data/' + value)
                    .then(function(response) {
                        console.log(response.data[0].themename);
                        self.add_to_bookmark(response.data[0].themename, response.data[0].unqid);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            });

            //將checkbox資料 放進checkbox陣列
            var array = [];
            match_cls_array.forEach(function(value) {
                array.push(value);
                self.cls_checkbox_array.push(array);
                array = [];
            });
            console.log('match : ' + match_theme_array);
            console.log(unique_theme_array);
            console.log('子分類資料 : ');
            console.log('cls_checkbox_array : ' + this.cls_checkbox_array);
        },
        show_table: function(table_name) {
            let self = this;
            $.each(self.show_side, function(index, value) {
                self.$set(self.show_side, index, false);
                console.log(index);
            });
            this.$set(this.show_side, table_name, true);
            this.navbar_color_name = table_name;
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
        click_theme_group: function(unqid) {
            this.themes_bookmark = [];
            this.classification_names = [];
            this.childclassifications = [];
            this.fathername = '';
            let self = this;
            axios.post('post/group_checkbox', {
                    group_unqid: unqid,
                })
                .then(function(response) {
                    console.log('group_checkbox : ' + response.data);
                    response.data.forEach(function(value) {
                        // self.add_to_bookmark(value.theme_name, value.theme_unqid);
                        console.log('group_checkbox : ' + value.theme_name + " " + value.theme_unqid);
                        self.fathername = value.theme_unqid;
                        self.themes_bookmark.push({ name: value.theme_name, unqid: value.theme_unqid });
                    });
                    self.get_classification_data();
                    if (self.themes_bookmark.length > 0) self.show = true;
                    else self.show = false;
                })
                .catch(function(error) {
                    console.log(error);
                });

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

            this.get_theme_group_data();
        },
    },
    mounted: function() {
        this.init();
        console.log('test : ');

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