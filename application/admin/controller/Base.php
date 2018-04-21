<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Config;
use think\facade\Url;

/**
* 后台首页
*/
class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();

        if (has_login() === false) {
            $this->redirect('admin/systemctl.control/login', ['callback' => urlencode($this->request->url(true))]);
        }

        $this->assign('requestPath', Url::build(request_route()));
        $this->assign('requestIndex', Url::build(strtolower(Request::module() . '/' . Request::controller() . '/index')));
    }
}
