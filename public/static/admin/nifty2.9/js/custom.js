// layer默认参数
layer.config({offset: 't', anim: 5});

$(function() {
    var mm = $("#mainnav-menu .active-link").parentsUntil('#mainnav-menu');
    mm.filter('li').addClass('active');
    mm.filter('ul').addClass('in').attr('aria-expanded', 'true');
    // 初始化ajax data-target="#login"
    $.ajaxSetup({
        beforeSend: function() {
            $('#overlay-btn').niftyOverlay({}).niftyOverlay('show');
        },
        complete: function() {
            $('#overlay-btn').niftyOverlay('hide');
        },
        type: 'POST',
        dataType: 'json'
    });

    // 退出登录
    $(".do-logout").click(function(event) {
        event.preventDefault();
        $.post($(this).attr('href'), function(response) {
            if (response.code === 1) {
                layer.msg(response.msg, {icon: 1}, function() {
                    window.location.href = "/";
                });
            } else {
                layer.msg(response.msg, {icon: 2});
            }
        }, 'json');
    });
});
