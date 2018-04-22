<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Session;
use think\facade\Request;
use think\facade\Config;

/**
* 会话控制
*/
class Control extends Controller
{
    /**
     * 登录页面
     */
    public function login()
    {
        if (has_login()) {
            $this->redirect('admin/index/index');
        }

        if (Config::get('theme.open')) {
            $assets = '/static/admin/' . Config::get('theme.name') . Config::get('theme.version');
        } else {
            $assets = '/static/admin';
        }
        $this->assign('assets', $assets);

        return $this->fetch();
    }

    /**
     * 登录方法
     */
    public function do_login()
    {
        $service = controller('User', 'service');

        $login = $service->login();

        if ($login['code'] === 1) {
            $this->success($login['msg']);
        } else {
            $this->error($login['msg']);
        }
    }

    /**
     * 退出登录方法
     */
    public function do_logout()
    {
        $service = controller('User', 'service');

        $logout = $service->logout();

        if ($logout['code'] === 1) {
            $this->success($logout['msg']);
        } else {
            $this->error($logout['msg']);
        }
    }

    /**
     * 验证码
     */
    public function captcha()
    {
        $service = controller('Captcha', 'service');
        return $service->captcha();
    }
}
