$(function () {
    $('nav ul li a:not(:only-child)').click(function (e) {
        $(this).siblings('.nav-dropdown').toggle();
        $(this).removeAttr("href");
        e.stopPropagation();
    });

    $('html').click(function () {
        $('.nav-dropdown').fadeOut(500);
    });

    $('#nav-toggle').click(function () {
        $('nav').slideToggle();
    });

    $('#nav-toggle').click(function () {
        this.classList.toggle('active');
    });

});
