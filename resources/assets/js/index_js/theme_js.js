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

    $("#nav-home-tab").click(function() {
        $("#nav-home").css("display", "none");
        $("#nav-home").fadeIn(250);
        $("#nav-profile").css("display", "none");
    });

    $("#nav-profile-tab").click(function() {
        $("#nav-home").css("display", "none");
        $("#nav-profile").css("display", "none");
        $("#nav-profile").fadeIn(250);
    });

    $(".NewThemeIcon").click(function() {
        $(".btn_theme_create").click();
    });

    $("#theme_create_btn").click(function() {
        $("#ThemeCreateForm").submit();
    });

});