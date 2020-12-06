$(document).ready(function () {

    if (window.location.href.indexOf('all') > 0) {
        $('.btnTodo').css('opacity', '0.5');
        $('.btnProgress').css('opacity', '0.5');
        $('.btnDone').css('opacity', '0.5');
    }
    if (window.location.href.indexOf('todo') > 0) {
        $('.btnDone').css('opacity', '0.5');
        $('.btnAll').css('opacity', '0.5');
        $('.btnProgress').css('opacity', '0.5');
    }
    if (window.location.href.indexOf('inprogress') > 0) {
        $('.btnTodo').css('opacity', '0.5');
        $('.btnAll').css('opacity', '0.5');
        $('.btnDone').css('opacity', '0.5');
    }
    if (window.location.href.indexOf('done') > 0) {
        $('.btnTodo').css('opacity', '0.5');
        $('.btnAll').css('opacity', '0.5');
        $('.btnProgress').css('opacity', '0.5');
    }
})

