$(document).ready(function() {
    $("body").show();
    var exists = [];

    $('.classification_container').css("height", $('.content').height() - 1);


    // $('.themename').click(function() {

    //     var ThemeName = this.attributes["name"].value;
    //     var id = this.id;

    //     if (exists.indexOf(id) >= 0) {
    //         alert('已經存在該項目');
    //     } else {
    //         var namePag = document.createElement("div");
    //         namePag.setAttribute("class", "ThemePageTitle");
    //         namePag.innerHTML = ThemeName;
    //         $('.classifications_title').append(namePag);
    //         exists.push(id);
    //     }

    // })

});