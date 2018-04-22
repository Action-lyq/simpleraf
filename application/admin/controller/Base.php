<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Config;
use think\facade\Url;
use think\facade\Cache;

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

        if (!Request::isAjax()) {
            $mainnav = controller('Menu', 'service')->build();
            $this->assign('mainnav', $mainnav);

            $breadcrumb = controller('Menu', 'service')->breadcrumb();
            $this->assign('breadcrumb', $breadcrumb['html']);
            $this->assign('title', $breadcrumb['title']);

            if (Config::get('theme.open')) {
                $assets = '/static/admin/' . Config::get('theme.name') . Config::get('theme.version');
            } else {
                $assets = '/static/admin';
            }
            $this->assign('assets', $assets);

            $this->assign('requestPath', Url::build(request_route()));
            $this->assign('requestIndex', Url::build(strtolower(Request::module() . '/' . Request::controller() . '/index')));
        }
    }
}
