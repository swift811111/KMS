$(document).ready(function() {

    // 驗證輸入的格式是否正確 
    var sign_username_click = 0;
    var sign_password_click = 0;
    var sign_email_click = 0;
    var sign_password_click = 0;
    var equalpassword = 0;

    //驗證密碼與再次輸入的密碼是否正確
    $('.sign_input_group input').on('keyup', function() {
        // console.log('click');

        if ($.trim($('#sure_sign_password').val()) == $.trim($('#sign_password').val()) && $.trim($('#sure_sign_password').val()) != 0) {

            $('#sure_sign_password').addClass('form-control-success');
            $('#sure_sign_password').parents('.form-group').addClass('has-success');
            $('#sure_sign_password').removeClass('form-control-danger');
            $('#sure_sign_password').parents('.form-group').removeClass('has-danger');
            $('#sure_sign_password').parents('.form-group').find('.text-muted').css('display', 'none');
            equalpassword = 1;

        } else if ($.trim($('#sure_sign_password').val()) != $.trim($('#sign_password').val()) && $.trim($('#sure_sign_password').val()) != 0) {

            $('#sure_sign_password').addClass('form-control-danger');
            $('#sure_sign_password').parents('.form-group').addClass('has-danger');
            $('#sure_sign_password').removeClass('form-control-success');
            $('#sure_sign_password').parents('.form-group').removeClass('has-success');
            $('#sure_sign_password').parents('.form-group').find('.text-muted').css('display', 'block');
            equalpassword = 0;

        }

        //輸入正確才可送出表單
        if ((sign_username_click + sign_password_click + sign_email_click + sign_password_click + equalpassword) == 5) $("button").removeProp('disabled');
        else $("#sign_btn").prop('disabled', true);
    });

    //username sign 驗證
    $('#sign_username').on('keyup', function() {

        var sign_username = $.trim($('#sign_username').val());
        if (sign_username != "") { //輸入是否為空

            if (isValidUsername(sign_username)) { //驗證正規表達式

                $(this).addClass('form-control-success');
                $(this).parents('.form-group').addClass('has-success');
                $(this).removeClass('form-control-danger');
                $(this).parents('.form-group').removeClass('has-danger');
                $(this).parents('.form-group').find('.text-muted').css('display', 'none');
                // console.log('ok');
                sign_username_click = 1;

            } else {
                $(this).addClass('form-control-danger');
                $(this).parents('.form-group').addClass('has-danger');
                $(this).removeClass('form-control-success');
                $(this).parents('.form-group').removeClass('has-success');
                $(this).parents('.form-group').find('.text-muted').css('display', 'block');
                // console.log(' not ok');
                sign_username_click = 0;
            }

        } else {

            $(this).addClass('form-control-danger');
            $(this).parents('.form-group').addClass('has-danger');
            $(this).removeClass('form-control-success');
            $(this).parents('.form-group').removeClass('has-success');
            $(this).parents('.form-group').find('.text-muted').css('display', 'block');
            // console.log('null');
            sign_username_click = 0;
        }

    });

    // password sign 驗證
    $('#sign_password').on('keyup', function() {

        var sign_password = $.trim($('#sign_password').val());
        if (sign_password != "") { //輸入是否為空

            if (isValidUsername(sign_password)) { //驗證正規表達式

                $(this).addClass('form-control-success');
                $(this).parents('.form-group').addClass('has-success');
                $(this).removeClass('form-control-danger');
                $(this).parents('.form-group').removeClass('has-danger');
                $(this).parents('.form-group').find('.text-muted').css('display', 'none');
                // console.log('ok');
                sign_password_click = 1;

            } else {
                $(this).addClass('form-control-danger');
                $(this).parents('.form-group').addClass('has-danger');
                $(this).removeClass('form-control-success');
                $(this).parents('.form-group').removeClass('has-success');
                $(this).parents('.form-group').find('.text-muted').css('display', 'block');
                // console.log(' not ok');
                sign_password_click = 0;
            }

        } else {

            $(this).addClass('form-control-danger');
            $(this).parents('.form-group').addClass('has-danger');
            $(this).removeClass('form-control-success');
            $(this).parents('.form-group').removeClass('has-success');
            $(this).parents('.form-group').find('.text-muted').css('display', 'block');
            // console.log('null');
            sign_password_click = 0;
        }

    });

    // password again sign 驗證
    $('#sure_sign_password').on('keyup', function() {

        var sure_sign_password = $.trim($('#sure_sign_password').val());
        if (sure_sign_password != "") { //輸入是否為空

            sure_sign_password_click = 1;

        } else {

            $(this).addClass('form-control-danger');
            $(this).parents('.form-group').addClass('has-danger');
            $(this).removeClass('form-control-success');
            $(this).parents('.form-group').removeClass('has-success');
            $(this).parents('.form-group').find('.text-muted').css('display', 'block');
            sure_sign_password_click = 0;
        }

    });

    // email sign 驗證
    $('#sign_email').on('keyup', function() {

        var sign_email = $.trim($('#sign_email').val());
        if (sign_email != "") { //輸入是否為空

            if (isValidEmailAddress(sign_email)) { //驗證正規表達式

                $(this).addClass('form-control-success');
                $(this).parents('.form-group').addClass('has-success');
                $(this).removeClass('form-control-danger');
                $(this).parents('.form-group').removeClass('has-danger');
                $(this).parents('.form-group').find('.text-muted').css('display', 'none');
                // console.log('ok');
                sign_email_click = 1;

            } else {
                $(this).addClass('form-control-danger');
                $(this).parents('.form-group').addClass('has-danger');
                $(this).removeClass('form-control-success');
                $(this).parents('.form-group').removeClass('has-success');
                $(this).parents('.form-group').find('.text-muted').css('display', 'block');
                // console.log(' not ok');
                sign_email_click = 0;
            }

        } else {

            $(this).addClass('form-control-danger');
            $(this).parents('.form-group').addClass('has-danger');
            $(this).removeClass('form-control-success');
            $(this).parents('.form-group').removeClass('has-success');
            $(this).parents('.form-group').find('.text-muted').css('display', 'block');
            // console.log('null');
            sign_email_click = 0;
        }

    });

    //正規表達式驗證----------------------------------------------------

    //username 驗證
    function isValidUsername(emailAddress) {
        var pattern = new RegExp(/\d|[a-zA-Z]{6,20}/);
        return pattern.test(emailAddress);
    }

    //email 驗證
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/);
        return pattern.test(emailAddress);
    }

    //-------------------------------------------------------------------


});