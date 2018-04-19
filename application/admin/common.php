<?php

use think\facade\Session;
use think\facade\Request;

/**
 * 检测登录
 * @return boolean 是否登录
 */
function has_login()
{
    return Session::has('OX');
}

/**
 * 内部API返回
 * @param  integer $code 错误码
 * @param  string  $data 错误信息
 * @param  string  $url  跳转参数
 * @return array        固定格式数组
 */
function api_return($code = 1, $data = '', $url = '')
{
    return ['code' => $code, 'data' => $data, $url => $url];
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

/**
 * 当前请求路由
 * @return string 当前请求路由地址
 */
function request_route()
{
    return strtolower(implode('/', [Request::module(), Request::controller(), Request::action()]));
}
