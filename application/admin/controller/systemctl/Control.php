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
        $service = \think\facade\App::controller('AdminUsers', 'service');
        $login = $service->login();
        if ($login['code'] === 1) {
            $this->success($login['data']);
        } else {
            $this->error($login['data']);
        }
    }

    /**
     * 退出登录方法
     */
    public function do_logout()
    {
        Session::clear();
        if (has_login()) {
            $this->error('退出失败');
        } else {
            $this->success('退出成功');
        }
    }

    /**
     * 验证码图片
     * @param  string $id 验证码ID
     * @return string     验证码图片
     */
    public function captcha($id = "")
    {
        $captcha = new \think\captcha\Captcha(config('captcha.'));
        return $captcha->entry($id);
    }
}
