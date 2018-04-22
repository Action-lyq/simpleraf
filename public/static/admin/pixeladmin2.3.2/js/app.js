// layer默认参数
layer.config({offset: 't', anim: 5});

$(function() {
    // 全局AJAX配置
    $.ajaxSetup({
        beforeSend: function() {
            $('[data-ajax]').addClass('form-loading');
        },
        complete: function() {
            $('[data-ajax]').removeClass('form-loading');
        },
        type: 'POST',
        dataType: 'json'
    });

    // 页面初始化
    $('body > .px-nav').pxNav();
    $('body > .px-footer').pxFooter();

    // 退出登录
    $(".do-logout").click(function(event) {
        event.preventDefault();
        $.post($(this).attr('href'), function(response) {
            if (response.code === 1) {
                layer.msg(response.msg, {icon: 1, time: 800}, function() {
                    window.location.href = "/";
                });
            } else {
                layer.msg(response.msg, {icon: 2, time: 800});
            }
        }, 'json');
    });

    // 表格全选
    $("#checkall:enabled").on('click', function(event) {
        var _itemStatus = $(this).prop('checked');
        var _controlItems = $('.checkitem:enabled');
        if (_itemStatus) {
            _controlItems.prop('checked', _itemStatus).parent('label').addClass('active');
        } else {
            _controlItems.prop('checked', _itemStatus).parent('label').removeClass('active');
        }
    });
    $(".checkitem:enabled").on('click', function(event) {
        var _controlItems = $('#checkall:enabled');
        var _parents = $(this).parent('label').parent('td').parent('tr').parent('tbody').find('.checkitem:enabled');
        var _status = true;
        $.each(_parents, function(i, v) {
            if ($(v).prop('checked') === false) {_status = false;}
        });
        if (_status) {
            _controlItems.prop('checked', _status).parent('label').addClass('active');
        } else {
            _controlItems.prop('checked', _status).parent('label').removeClass('active');
        }
    });
});
