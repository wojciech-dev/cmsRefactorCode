//grid generate
if ($(".text").hasClass("schema6")) {
    $("#content").addClass("grid");
}

//arrow in menu
$(".nav li").has("ul").addClass("triangle");

//add class to scroll element
$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 100) {
        $("header").addClass("darkHeader");
    } else {
        $("header").removeClass("darkHeader");
    }
});