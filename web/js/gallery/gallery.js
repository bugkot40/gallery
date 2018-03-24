/**
 * Created by kot on 20.01.17.
 */
$(document).ready(function () {
    //test begin

    //test end

    $("button.menu").on('click', function () {
        if ($("ul.menu").css('display') == 'block') {
            $("ul.menu").slideUp(0);
            $(".works-content").css('width', '100%');
        }
        else {
            $("ul.menu").slideDown(300);
            if ($(window).width() < 550) {
                $(".works-content").css('width', '100%');
            }
            else $(".works-content").css('width', '70%');
        }
    });
    $(window).resize(function () {
        $(".works-content").css('width', '100%');
        if ($(window).width() <= 768 && $("button.menu").css('display') == 'inline-block') {
            $("ul.menu").css('display', 'none');
        }
        else {
            $("ul.menu").css('display', 'block');
        }
    });

    /**
     * ajax
     * В этой работе "Галлерея" решил ajax не применять
     * даже если получиться выполнить загрузку страниц асинхронно,
     * что конечно повысит скорость загрузки галлерей, но при этом:
     * -теряется ЧПУ;
     * -слетают js обработчики;
     * короче все это не просто, надо изучать и пробовать виджет PJAX !
     */

    /* $(".js_gallery").on('click', function () {
     var status = $(this).attr('data-status');
     console.log(status);
     $.ajax({
     url: '/gallery/gallery',
     type: 'GET',
     data: {
     'status': status
     },
     success: function (res) {
     console.log(res);
     $('#ulia').html(res);
     },
     error: function () {
     alert('Введите цифру');
     }
     });
     return false;
     });*/
});
