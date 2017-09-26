$(document).ready(function() {
    $("body").show();
    $('#ThemeTable').DataTable();
    $('#ThemeTable_collect').DataTable();

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