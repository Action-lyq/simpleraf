<?php

use think\facade\Session;
use think\facade\Request;

/**
 * 检测登录
 * @return boolean 是否登录
 */
function has_login()
{
    return Session::has('SRAF');
}

/**
 * 内部API返回
 * @param  integer $code 错误码
 * @param  string  $msg  错误信息
 * @param  string  $data 返回数据
 * @param  string  $url  跳转参数
 * @return array         固定格式数组
 */
function build_api($code = 1, $msg = '', $data = '', $url = '')
{
    return [
        'code'=> $code,
        'msg' => $msg,
        'data'=> $data,
        'url' => $url
    ];
}

/**
 * 当前请求路由
 * @return string 当前请求路由地址
 */
function request_route()
{
    return strtolower(implode('/', [Request::module(), Request::controller(), Request::action()]));
}

/**
 * 字节转易读单位
 * @param  int $bytes 字节大小
 * @return string     数据大小易读单位
 */
function convert_bytes_units($bytes)
{
    if (!$bytes) return 'Function "'.__FUNCTION__.'()" requires a parameter !';

    $size_ext = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB', 'NB', 'DB'];
    for ($i = 0; $bytes >= 1000 && $i < 12; $i++) $bytes /= 1024;
    return round($bytes, 2) . $size_ext[$i];
}
