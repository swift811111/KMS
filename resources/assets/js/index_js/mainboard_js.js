$(document).ready(function() {
    // side bar animation
    $(".menu_click").click(function() {
        $(".side_bar").animate({
            left: '0px',
        });
        $(".side_bar").height(
            $(window).height()
        );

        // $(".mask").css("display", "block");
        $(".mask").fadeIn(300);
    });

    $(".mask").click(function() {
        $(".mask").fadeOut(300);
        if ($('.side_bar').css('left') > '-400px') {
            $(".side_bar").animate({
                left: '-400px',
            });
        }
    });

    // click content to receive side bar
    $(".content").click(function() {
        if ($('.side_bar').css('left') > '-400px') {
            $(".side_bar").animate({
                left: '-400px',
            });
        }
    });

    // cancel button
    $(".cancel").click(function() {
        $(".side_bar").animate({
            left: '-400px',
        });
        $(".mask").fadeOut(300);
    });

    //content size
    $(".content").css("min-height", $(window).height() - $(".menu").height() - 2);

    //login submit
    $("#login_btn").click(function() {
        $("#LoginForm").submit();
    });

    //sign submit SignForm
    $("#sign_btn").click(function() {
        $("#SignForm").submit();
    });

    $('.modal').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });

});

//管理文章的ICON轉動
var sidebar = new Vue({
    el: '.side_bar',
    data: {
        show: true,
        theme_data: [],
    },
    methods: {
        imgTransform: function() {
            if (this.show == true) {
                $("img.chevron-sign-to-right").css("transform", "rotate(-1deg)");
                this.show = false;
            } else {
                $("img.chevron-sign-to-right").css("transform", "rotate(90deg)");
                this.show = true;
            }
        },
    }
});