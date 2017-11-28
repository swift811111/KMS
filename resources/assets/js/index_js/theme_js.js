$(document).ready(function() {
    $("body").show();
    // $('#ThemeTable').DataTable();
    // $('#ThemeTable_collect').DataTable();
    $('#ThemeTable').DataTable({
        "oLanguage": {
            "oPaginate": {
                "sNext": "下一頁",
                "sPrevious": "上一頁",
            },
            "sSearch": "搜尋",
            "sLengthMenu": "顯示 _MENU_ 資料",
            // "sInfo": "Got a total of _TOTAL_ entries to show (_START_ to _END_)"
            "sInfo": "顯示 _START_ - _END_ 筆資料 ( 共_TOTAL_筆 )"
        }
    });
    $('#ThemeGroupTable').DataTable({
        "oLanguage": {
            "oPaginate": {
                "sNext": "下一頁",
                "sPrevious": "上一頁",
            },
            "sSearch": "搜尋",
            "sLengthMenu": "顯示 _MENU_ 資料",
            // "sInfo": "Got a total of _TOTAL_ entries to show (_START_ to _END_)"
            "sInfo": "顯示 _START_ - _END_ 筆資料 ( 共_TOTAL_筆 )"
        }
    });

    $(".tab-pane").css("min-height", $("body").height() - $(".menu").height() - $("#mytab").height() - 50);

    $(".ThemeContainer").css("height", $("body").height() - $(".menu").height() - $("#mytab").height() - $(".NewTheme").height() - 70);

    // var tch = $("body").height() - $(".menu").height() - $("#mytab").height() - $(".NewTheme").height() - 65;
    // $(".tabcontent").css("min-height", tch * 0.3);

    // $("#nav-home-tab").click(function() {
    //     $("#nav-home").css("display", "none");
    //     $("#nav-home").fadeIn(250);
    //     $("#nav-profile").css("display", "none");
    // });

    // $("#nav-profile-tab").click(function() {
    //     $("#nav-home").css("display", "none");
    //     $("#nav-profile").css("display", "none");
    //     $("#nav-profile").fadeIn(250);
    // });

    //開啟新增主題視窗
    $(".NewThemeIcon").click(function() {
        $(".btn_theme_create").click();
    });

    $("#theme_create_btn").click(function() {
        $("#ThemeCreateForm").submit();
    });

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

    //確認是否刪除主題
    $("#delete_theme").click(function() {

        var checked_box_length = $('input[type="checkbox"]:checked').length;

        if (checked_box_length <= 0) {
            alert("請選擇至少一項主題");
        } else {
            var sure = confirm("刪除主題將會刪除此主題底下的所有分類標籤，確認刪除 " + checked_box_length + " 筆資料?");
            if (sure == true) {
                $("#delete_theme_form").submit();
            }
        }

    });
    //確認是否刪除群組
    $("#delete_theme_group").click(function() {

        var checked_box_length = $('input[name="themes_group_data[]"]:checked').length;

        if (checked_box_length <= 0) {
            alert("請選擇至少一項主題");
        } else {
            var sure = confirm("確認刪除 " + checked_box_length + " 筆群組資料?");
            if (sure == true) {
                $("#delete_theme_group_form").submit();
            }
        }

    });
});

var theme_group = new Vue({
    el: '.content',
    data: {
        theme_data: "",
        theme_select_array: [],

        theme_show: true,
        theme_group_show: false,
        theme_value: "theme",
    },
    methods: {
        create_theme_group: function() {
            console.log("array : " + this.theme_select_array);
            console.log("json : " + JSON.stringify(this.theme_select_array));
            let self = this;
            axios.post('post/create_theme_group', {
                    theme_select_json: JSON.stringify(self.theme_select_array),
                    name: this.$refs.theme_group_name.value,
                })
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        get_theme_data: function() {
            let self = this;
            axios.get('data/theme_data')
                .then(function(response) {
                    self.theme_data = response.data;
                    console.log(self.theme_data);
                })
                .catch(function(response) {
                    console.log("error");
                });
        }
    },
    computed: {
        onchange: function() {
            if (this.theme_value == "theme") {
                this.theme_show = true;
                this.theme_group_show = false;
            } else {
                this.theme_show = false;
                this.theme_group_show = true;
            }
        }
    },
    mounted: function() {
        this.get_theme_data()
    },
});