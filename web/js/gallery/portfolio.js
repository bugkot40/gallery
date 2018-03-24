$(document).ready(function () {
    /**
     * @type {*|jQuery}
     * скрипты для самого портфолео
     */
    var width = $(window).width();
    if (width <= 750) {
        $("#portfolio-data").height(width * 0.75);
    }
    $(window).resize(function () {
        var width = $(window).width();
        if (width <= 750) {
            $("#portfolio-data").height(width * 0.75);
        }
        if (width > 750) {
            $("#portfolio-data").height(480);
        }
    });

    /**
     * скрипты дл визитки
     */

    $("#visiting-title").click(function () {
        var avatar = $("#visiting-avatar");
        avatar.css("left", "5px");
        avatar.animate({
            "width": "300px",
            "height": "500px"
        }, 500);
        setTimeout(function () {
            $("#visiting-load").css('display', 'block');
            $("#visiting-close").css('display', 'block');
        }, 550);
    });
    $("#visiting-close").click(function () {
        $("#visiting-avatar").animate({
            "left": "120px",
            "width": "0",
            "height": "0"
        }, 500);
        $("#visiting-load").css('display', 'none');
        $("#visiting-close").css('display', 'none');
    });

});