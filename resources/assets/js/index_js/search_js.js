$(document).ready(function() {
    $("body").show();
    $('.All_container').css("height", $(window).height() - $('.All_Title').height() - 10);
    $('.All_body').css("height", $(window).height() - $('.All_Title').height() * 2 - 7);
});