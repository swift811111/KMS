$(document).ready(function() {
    $("body").show();

    $(".tab-pane").css("min-height", $("body").height() - $(".menu").height() - $("#mytab").height() - 50);

    $(".ThemeContainer").css("height", $("body").height() - $(".menu").height() - $("#mytab").height() - $(".NewTheme").height() - 70);

    //點擊某一列任意處即可選擇
    $(".checkbox_tr").click(function() {
        if ($(this).find('.checkbox_td').is(':checked')) {
            $(this).find('.checkbox_td').prop("checked", false);
        } else {
            $(this).find('.checkbox_td').prop("checked", true);
        }
    });
    //背景顏色
    $(".checkbox_tr").mouseover(function() {
        $(this).children().css("background-color", "rgb(190, 187, 187)");
    });
    $(".checkbox_tr").mouseout(function() {
        $(this).children().css("background-color", "");
    });

    $("body").on("click", ".article_edit_icon", function() {
        $(this.parentElement).submit();
    });

});


var theme_group = new Vue({
    el: '.content',
    data: {

        theme_perpage_data: "",
        theme_perpage_group_data: "",
        theme_data: "", //所有主題資料
        theme_in_group_array: [], //存放要放進群組的主題
        theme_delete_array: [], //存放要刪除的主題資料
        theme_group_delete_array: [], //存放要刪除的群組資料
        theme_various: 'theme', //設定拿取哪個資料庫
        group_status: 'create', //創建或編輯群組

        //== 顯示主題館裡或群組管理的資訊
        theme_show: true,
        theme_change: "theme",

        //== 修改主題名稱框裡面的資料設定
        theme_name: "",
        theme_unqid: "",

        perpage: 5, //每頁要顯示幾筆資料
        current_page: 1, //現在在第幾頁
        pages: "", //總共有幾頁
        data_length: [], //總共有幾筆資料
        page_star: 1,
        page_end: 10,

        cursor: "cursor",
        disabled: "disabled",
        active: "active",

        //=== 設定css style ===
        arrange_style: {
            id: 'sorting_desc',
            themename: 'no_arrange',
            foundername: 'no_arrange',
            created_at: 'no_arrange',
        },
        //=== 排列方式 欄位狀態 ===
        data_arrange_method: {
            column: "id", //欄位名稱
            arrangement: "DESC", //排列方式
        },
        //== 設定現在以哪一欄做排序 ===
        arrange_title: {
            id: 'DESC', //預設為ID asc
            themename: 'no_arrange',
            foundername: 'no_arrange',
            created_at: 'no_arrange',
        },
        //=== 全選 ===
        checkbox_all: false,

    },
    methods: {
        select_all: function() {
            var select = [];

            if (this.theme_delete_array.length < this.theme_perpage_data.length) {
                this.theme_perpage_data.forEach(function(value) {
                    select.push(value.unqid);
                });
                this.theme_delete_array = select;
                this.checkbox_all = true;
            } else {
                this.theme_delete_array = [];
                this.checkbox_all = false;
            }
        },
        //=== 主題&群組 新增與刪除 ===
        create_theme: function() {
            let self = this;
            axios.post('post/create_theme', {
                    theme_name: self.$refs.theme_name.value,
                    theme_creater: self.$refs.theme_creater.value,
                    theme_in_group_array: self.theme_in_group_array,
                    theme_group_name: self.$refs.theme_group_name.value,
                    theme_various: self.theme_various,
                })
                .then(function(response) {
                    console.log(response);
                    self.get_theme_perpage_data(self.theme_various);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        delete_theme: function() {
            if (this.theme_various == 'theme') {
                if (this.theme_delete_array.length <= 0) {
                    alert("請選擇至少一項主題");
                } else {
                    var sure = confirm("刪除主題將會刪除此主題底下的所有分類標籤，確認刪除 " + this.theme_delete_array.length + " 筆資料?");
                }
            } else {
                if (this.theme_group_delete_array.length <= 0) {
                    alert("請選擇至少一項群組");
                } else {
                    var sure = confirm("確認刪除群組 ? ");
                }
            }
            if (sure == true) {
                let self = this;
                axios.post('post/delete_theme', {
                        theme_delete_array: self.theme_delete_array,
                        theme_group_delete_array: self.theme_group_delete_array,
                        theme_various: self.theme_various,
                    })
                    .then(function(response) {
                        console.log(response);
                        self.get_theme_perpage_data(self.theme_various);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        //點選更改主題&群組名稱時 讓跳出的視窗名稱為點選欄位的主題&群組名稱
        change_name: function(name, unqid) {
            this.theme_name = name;
            this.theme_unqid = unqid;
        },
        //送出更改完成的主題&群組名稱 做更改
        edit_name: function(table_name) {
            if (table_name == 'theme') {
                var sure = confirm("確認更改主題名稱 ? ");
            } else {
                var sure = confirm("確認更改群組名稱 ? ");
            }

            if (sure == true) {
                let self = this;
                axios.post('post/theme_update', {
                        theme_unqid: self.theme_unqid,
                        theme_name: self.$refs.edit_theme_name.value,
                        table_name: table_name,
                    })
                    .then(function(response) {
                        console.log(response.data);
                        self.get_theme_perpage_data(self.theme_various);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        // cli: function() {
        //     console.log(this.theme_in_group_array);
        // },
        edit_group: function(group_name, group_unqid) {
            this.change_name(group_name, group_unqid);
            this.group_status = 'edit';

            let self = this;
            axios.post('post/group_checkbox', {
                    group_unqid: group_unqid,
                })
                .then(function(response) {
                    console.log(response.data);
                    //將checkbox資料 放進checkbox陣列
                    self.theme_in_group_array = [];
                    response.data.forEach(function(value) {
                        self.theme_in_group_array.push(value.theme_unqid + value.theme_name);
                    });
                    console.log(self.theme_in_group_array);
                    self.get_theme_perpage_data(self.theme_various);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        update_group: function() {
            let self = this;
            axios.post('post/group_update', {
                    theme_in_group_array: self.theme_in_group_array,
                    theme_group_name: self.$refs.theme_group_name.value,
                    theme_group_unqid: self.$refs.theme_group_unqid.value,
                })
                .then(function(response) {
                    console.log(response);
                    self.get_theme_perpage_data(self.theme_various);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //===頁數選擇 與 排序資料 ===
        get_theme_perpage_data: function(various) {
            let self = this;
            if (various == 'theme') {
                axios.get('data/theme_data_arrange/' + self.perpage + "/" + self.current_page + "/" + self.data_arrange_method.column + "/" + self.data_arrange_method.arrangement)
                    .then(function(response) {
                        self.theme_perpage_data = response.data;
                        self.page_star_to_end();
                        self.get_all_pages(self.theme_various);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            } else {
                if (this.data_arrange_method.column == 'themename') {
                    this.data_arrange_method.column = 'theme_group_name';
                } else if (this.data_arrange_method.column == 'foundername') {
                    this.data_arrange_method.column = 'username';
                }
                axios.get('data/theme_group_data_arrange/' + self.perpage + "/" + self.current_page + "/" + self.data_arrange_method.column + "/" + self.data_arrange_method.arrangement)
                    .then(function(response) {
                        self.theme_perpage_data = response.data;
                        self.page_star_to_end();
                        self.get_all_pages(self.theme_various);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            }
        },
        get_all_pages: function(various) { //依據每頁要顯示幾筆資料得到 需要顯示幾頁
            let self = this;
            if (various == 'theme') {
                axios.get('data/theme_data')
                    .then(function(response) {
                        self.data_length = response.data.length;
                        self.theme_data = response.data;
                        self.pages = Math.ceil(response.data.length / self.perpage);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            } else {
                axios.get('data/theme_group_data')
                    .then(function(response) {
                        self.data_length = response.data.length;
                        self.pages = Math.ceil(response.data.length / self.perpage);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            }

        },
        go_to_page: function(page) { //選擇第幾頁
            this.current_page = page;
            this.get_theme_perpage_data(this.theme_various);
        },
        last_page: function() { //上一頁
            if (this.current_page - 1 >= 1) {
                this.current_page = this.current_page - 1;
                this.get_theme_perpage_data(this.theme_various);
            }
        },
        next_page: function() { //下一頁
            if (this.current_page + 1 <= parseInt(this.pages)) {
                this.current_page = this.current_page + 1;
                this.get_theme_perpage_data(this.theme_various);
            }
        },
        //顯示第幾筆 到 第幾筆 
        page_star_to_end: function() {
            if (this.current_page == 1) { //第一頁
                this.page_star = 1;
                if (this.theme_perpage_data.length < this.perpage) {
                    this.page_end = this.theme_perpage_data.length;
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
        //設定欄位箭頭顯示asc or desc of define
        arrange_style_fnc: function(style_name, style) {
            let self = this;
            $.each(self.arrange_style, function(index, value) {
                self.$set(self.arrange_style, index, 'no_arrange');
            });
            this.$set(this.arrange_style, style_name, style);
        },

        //設定排序時的數值
        set_arrange_data: function(arrange_name, arrange_method, arrange_style) {

            //先將所有的排序方法全部轉成無排序
            let self = this;
            $.each(self.arrange_title, function(index, value) {
                self.$set(self.arrange_title, index, 'no_arrange');
            });

            //設定箭頭升冪或降冪
            this.arrange_style_fnc(arrange_name, arrange_style);

            //利用傳進來的參數指定資料庫利用某一欄位來排序資料
            this.$set(this.arrange_title, arrange_name, arrange_method);

            //-- 設定資料的排序方式 --
            this.$set(this.data_arrange_method, 'column', arrange_name);
            this.$set(this.data_arrange_method, 'arrangement', arrange_method);
            this.get_theme_perpage_data(this.theme_various);
        },
        //每頁要顯示幾筆資料
        onchange: function() {
            this.current_page = 1;
            this.pages = Math.ceil(this.data_length / this.perpage); //更新總頁數
            this.get_theme_perpage_data(this.theme_various);
        },
        //對點選的欄位做排序
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

        theme_title_onchange: function() {
            if (this.theme_change == "theme") {
                this.theme_various = 'theme';
                this.get_theme_perpage_data('theme');
            } else {
                this.theme_various = 'group';
                this.get_theme_perpage_data('group');
            }
        },
    },
    computed: {

    },
    mounted: function() {
        //先載入主題管理的第一頁資料
        this.get_theme_perpage_data(this.theme_various);

    },
});