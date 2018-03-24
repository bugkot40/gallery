/**
 * Created by kot on 21.06.17.
 */
$(document).ready(function () {
    $(".edit-menu p").on('click', function () {
        $(".edit-menu ul").slideUp(300);
        var menu =  $(this).siblings("ul");
        console.log(menu.css('display'));
        if(menu.css('display') == 'block'){
            menu.slideUp(300);
        }
        else menu.slideDown(300);
    })
});