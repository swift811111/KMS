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

    //確認是否刪除
    $("#delete_theme").click(function() {

        var checked_box_length = $('input[type="checkbox"]:checked').length;
        var sure = confirm("刪除主題將會刪除此主題底下的所有分類標籤，確認刪除 " + checked_box_length + " 筆資料?");

        if (sure == true) {
            $("#delete_theme_form").submit();
        }

    });
});