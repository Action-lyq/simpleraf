<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Session;
use think\facade\Request;

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

        return $this->fetch();
    }

    /**
     * 登录方法
     */
    public function do_login()
    {
        $logic = controller('User', 'logic');

        $login = $logic->login();

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
        $logic = controller('User', 'logic');

        $logout = $logic->logout();

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
