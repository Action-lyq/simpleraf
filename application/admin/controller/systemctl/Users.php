<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Session;
use think\facade\Request;

/**
* 会话控制
*/
class Users extends \app\admin\controller\Base
{
    /**
     * 登录页面
     */
    public function index()
    {
        return $this->fetch('index/index');
    }
}
