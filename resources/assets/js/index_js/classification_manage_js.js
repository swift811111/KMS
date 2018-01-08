$(document).ready(function() {
    $("body").show();
    $('.classification_container').css("height", $('.content').height() - 1);
    $('.classifications_content').css("height", $('.classifications').height() - $('.classifications_title').height());
    $('.theme_menu').css("height", $('.classifications').height());

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


var classificationPagging = new Vue({
    el: '.content',
    data: {
        red: "red",
        bookmark_color: "bookmark_color",

        themes_my: [], //主題列表
        themes_bookmark: [], //檢視是否存在重複的主題頁籤
        show: false, //新增按鈕是否顯示
        theme_group_data: "", //群組資料

        //父分類
        classification_names: [], //儲存所有父分類資料
        fathername: "",
        classification_name: "",
        foundername: "",
        unqid: "",
        father_checkedunqid: [],

        //子分類
        childclassifications: [],
        cls_foundername: "",
        Child_Classification_add_unqid: "",
        cls_name: "",
        father_unqid: "",
        edit_text: "",
        cls_c_name: "",

    },
    methods: {
        addpag: function() {
            this.range += 1;
        },
        give_cls_c: function(name, unqid) {
            this.add_to_bookmark(name, unqid);
            console.log(name + ' ' + unqid);
        },
        //拿分類的資料
        get_classification_data: function() {
            if (this.fathername != '') {
                let self = this;
                axios.get('data/classification_data/' + self.fathername)
                    .then(function(response) {
                        self.classification_names = response.data[0];
                        self.childclassifications = response.data[1];
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            }
        },

        //將分類加入頁籤並更新頁籤裡面的分類資料
        add_to_bookmark: function(name, unqid) {

            if ((name && unqid) != "") {
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
                    this.fathername = unqid;
                    //顯示分類

                    this.get_classification_data()

                    if (this.themes_bookmark.length > 0) this.show = true;
                    else this.show = false;
                }
            }
        },
        //更新頁籤裡面的分類資料
        bookmark_click: function(unqid) {
            this.fathername = unqid;
            this.get_classification_data()
        },

        //將頁籤移除 並檢查頁籤的位子 決定要顯示哪個頁籤的分類資料
        remove_from_bookmark: function(removeItem) {

            let index = this.themes_bookmark.indexOf(removeItem)
            this.themes_bookmark.splice(index, 1);
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
                this.show = false;
            }
        },

        //新增父分類
        new_classification: function() {
            let self = this;

            this.classification_name = this.$refs.classification_name.value;
            this.foundername = this.$refs.classification_foundername.value;

            axios.post('post/classificationAdd', {
                    fathername: self.fathername,
                    name: self.classification_name,
                    foundername: self.foundername,
                })
                .then(function(response) {
                    // axios.get('data/classification_data/' + self.fathername)
                    //     .then(function(response) {
                    //         self.classification_names = response.data[0];
                    //         self.childclassifications = response.data[1];
                    //     })
                    //     .catch(function(response) {
                    //         console.log("error");
                    //     });
                    self.get_classification_data()
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        enter_to_new_cls: function(type) {
            if (type == 'cls_f') {
                $('#classification_create_btn').click();
                this.new_classification();
            } else {
                $('#Child_Classification_create_btn').click();
                this.new_Child_Classification();
            }

        },
        //刪除父分類
        delete_father_cls: function() {
            console.log(this.father_checkedunqid);
            let self = this;

            var checked_box_length = $('input[type="checkbox"]:checked').length;

            if (checked_box_length <= 0) {
                alert("請選擇至少一項分類");
            } else {
                var sure = confirm("刪這些分類將會連擁有此分類的標籤刪除，確認刪除 " + checked_box_length + " 筆資料?");
                if (sure == true) {
                    axios.post('post/delete_father_cls', {
                            unqid: self.father_checkedunqid,
                        })
                        .then(function(response) {
                            self.get_classification_data()
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            }

        },

        //新增子分類
        Input_Father_Classification_Unqid: function(unqid) {
            this.father_unqid = unqid;
            console.log(this.father_unqid);
        },
        new_Child_Classification: function() {
            let self = this;

            this.cls_name = this.$refs.cls_name.value;
            this.cls_foundername = this.$refs.cls_foundername.value;

            axios.post('post/Child_Classification_add', {
                    fathername: self.father_unqid,
                    name: self.cls_name,
                    foundername: self.cls_foundername,
                })
                .then(function(response) {
                    self.get_classification_data()
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        //刪除子分類資料
        delete_child_cls: function(unqid) {
            let self = this;

            var sure = confirm("刪除此分類將會連擁有此分類的標籤刪除，確認刪除?");

            if (sure == true) {
                axios.post('post/delete_child_cls', {
                        unqid: unqid,
                    })
                    .then(function(response) {
                        self.get_classification_data();
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        //編輯子分類
        clk: function() {
            console.log(this.vm.$el.queryselector('.yuiyj').value);
        },
        edit_cls_c: function(unqid) {
            console.log(this.cls_c_name);
            this.edit_text = "";
            let self = this;
            axios.post('post/update_child_cls', {
                    unqid: unqid,
                    name: self.cls_c_name,
                })
                .then(function(response) {
                    self.get_classification_data();
                })
                .catch(function(error) {
                    console.log(error);
                });
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
        sleep: function(milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds) {
                    break;
                }
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
    watch: {
        show_button: function() {
            if (this.themes_bookmark.length > 0) this.show = true;
            else this.show = false;
        }
    },
    mounted: function() {
        this.init();

    },
    destroyed: function() {
        this.init();
    }


});