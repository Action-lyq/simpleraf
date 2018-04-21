// layer默认参数
layer.config({offset: '50px', anim: 5});

$(function() {
    // var mm = $("#mainnav-menu .active-link").parentsUntil('#mainnav-menu');
    // mm.filter('li').addClass('active');
    // mm.filter('ul').addClass('in').attr('aria-expanded', 'true');
    // 初始化ajax动画
    var overlay = $("[data-overlay]");
    var overlayHTML =   '<div id="overlay-' + overlay.data('overlay') + '" class="overlay">' +
                            '<div class="overlay-content">' +
                                '<div class="overlay-icon"><i class="css-loader"></i></div>' +
                                '<div class="overlay-text"></div>' +
                            '</div>' +
                        '</div>';
    $.ajaxSetup({
        beforeSend: function() {
            overlay.addClass('overlay-wrap').append(overlayHTML);
        },
        complete: function() {
            overlay.removeClass('overlay-wrap').children('#overlay-' + overlay.data('overlay')).remove();
        },
        type: 'POST',
        dataType: 'json'
    });

    // 退出登录
    $(".do-logout").click(function(event) {
        event.preventDefault();
        $.post($(this).attr('href'), function(response) {
            if (response.code === 1) {
                layer.msg(response.msg, {icon: 1, time: 800}, function() {
                    window.location.href = '';
                });
            } else {
                layer.msg(response.msg, {icon: 2, time: 800});
            }
        }, 'json');
    });
});
