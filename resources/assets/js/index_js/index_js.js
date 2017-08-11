$(document).ready(function() {

    // side bar animation
    $(".menu_click").click(function() {
        $(".side_bar").animate({
            left: '0px',
        });
        $(".side_bar").height(
            $(window).height()
        );
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
    });

    //content size
    $(".content").css("height", $(window).height() - $(".menu").height() - 2);

    // body fade
    $("body").fadeIn(1300);

    // text top to center fade
    $("p").animate({
        'margin-top': '0px',
    }, 1300);

    $(".bottom_line").animate({
        'width': '35%',
    }, 1300);

    //login submit
    $("#login_btn").click(function() {
        $("#LoginForm").submit();
    });

    //sign submit SignForm
    $("#sign_btn").click(function() {
        $("#SignForm").submit();
        // $.ajax({
        //     type: "POST",
        //     url: "/login",
        //     dataType: "json",
        //     data: {
        //         username: $(".username").val(),
        //         password: $(".password").val()
        //     },
        //     success: function(data) {
        //         alert('success');
        //     },
        //     error: function(jqXHR) {
        //         alert("發生錯誤: " + jqXHR.status);
        //     }
        // })
    });

});