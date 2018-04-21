<?php
namespace app\admin\logic;

use think\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;

/**
* 用户逻辑
*/
class User
{
    /**
     * 登录接口
     */
    public function login()
    {
        $username = Request::post('username', '', 'trim');
        if ($username === '') {
            return build_api(-1, '请输入账号');
        }

        $password = Request::post('password', '', 'trim');
        if ($password === '') {
            return build_api(-1, '请输入密码');
        }
        $password = Config::get('salt.admin_pwd').$password;

        $captcha = Request::post('captcha', '', 'trim');
        if ($captcha === '') {
            return build_api(-1, '请输入验证码');
        }

        if (!$this->check($captcha)) {
            return build_api(-1, '验证码不正确');
        }

        $user = Db::name('users')->where('username', $username)->find();

        if (empty($user)) {
            return build_api(-1, '账号不存在');
        } else {
            if (password_verify($password, $user['password']) === false) {
                return build_api(0, '密码错误');
            }

            if (intval($user['status']) !== 1) {
                return build_api(0, '账号已禁用');
            }

            unset($user['password']);

            Session::set('baseCMF', $user);

            if (has_login()) {
                Db::name('users')->where('username', $username)->setField([
                    'last_time' => time(),
                    'last_ip' => Request::ip()
                    ]);
                return build_api(1, '登录成功', Request::post('callback'));
            } else {
                return build_api(0, '登录失败');
            }
        }
    }

    /**
     * 获取用户列表
     * @return array 用户列表数据
     */
    public function userList()
    {
        return Db::name('users')->field('password', true)->order('id', 'desc')->paginate();
    }

    /**
     * 添加用户
     */
    public function addUser()
    {
        $data = Request::post();

        if (trim($data['username']) === '') {
            return build_api(-1, '请输入用户名');
        }

        if (Db::name('users')->where('username', trim($data['username']))->find()) {
            return build_api(-1, '用户名已存在');
        }

        if (trim($data['nickname']) === '') {
            return build_api(-1, '请输入昵称');
        }

        if (trim($data['password']) === '') {
            return build_api(-1, '请输入密码');
        }

        $data['password'] = password_hash(Config::get('salt.admin_pwd').$data['password'], PASSWORD_DEFAULT);

        $group_id = $data['group_id'];
        unset($data['group_id']);

        Db::startTrans();

        $add = Db::name('users')->insertGetId($data);

        $group_access['uid'] = $add;
        $group_access['group_id'] = $group_id;

        $add_relation = Db::name(Config::get('auth.auth_group_access'))->insert($group_relation);

        if ($add > 0 && $add_relation > 0) {
            Db::commit();
            return build_api(1, '添加成功');
        }

        Db::rollback();
        return build_api(0, '添加失败');
    }

    /**
     * 编辑用户
     */
    public function editUser()
    {
        $data = Request::post();

        if (trim($data['nickname']) === '') {
            return build_api(-1, '请输入昵称');
        }

        if (trim($data['password']) === '') {
            unset($data['password']);
        } else {
            $data['password'] = password_hash(Config::get('salt.admin_pwd').$data['password'], PASSWORD_DEFAULT);
        }

        $group_id = $data['group_id'];
        unset($data['group_id']);

        Db::startTrans();

        $save = Db::name('users')->update($data);

        $save_relation = Db::name(Config::get('auth.auth_group_access'))->where('uid', $data['id'])->setField('group_id', $group_id);

        if ($save > 0 || $save_relation >= 0) {
            Db::commit();
            return build_api(1, '保存成功');
        }

        Db::rollback();
        return build_api(1, '数据没有改动');
    }

    /**
     * 删除用户
     */
    public function deleteUser()
    {
        $id = Request::param('id/d');

        if ($id === 1) {
            return build_api(0, '禁止删除超级管理员');
        }

        $delete = Db::name('users')->where('id', $id)->delete();

        if ($delete > 0) {
            return build_api(1, '删除成功');
        }

        return build_api(0, '删除成功');
    }

    /**
     * 切换状态
     */
    public function toggleStatus()
    {
        $id = Request::post('id/d');
        $status = Request::post('status');

        if ($id === 1) {
            return build_api(0, '禁止操作超级管理员');
        }

        $update = Db::name('users')->where('id', $id)->setField('status', $status);

        return $update !== false;
    }

    /**
     * 根据ID获取用户
     */
    public function getUserById($id)
    {
        return Db::name('users')
                ->alias('u')
                ->leftJoin(Config::get('auth.auth_group_access').' g', 'u.id=g.uid')
                ->field('id,username,nickname,last_time,last_ip,status,group_id')
                ->where('u.id', $id)
                ->find();
    }
}
