<?php
namespace app\admin\service;

use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\Db;
use app\admin\model\User as UserModel;

/**
* 用户服务
*/
class User
{
    /**
     * 获取用户列表
     * @return array 用户列表数据
     */
    public function userList()
    {
        $userlist = UserModel::with('roles')->field('password', true)->order('id', 'desc')->paginate();

        foreach ($userlist as $key => $user) {
            $userlist[$key]['role'] = $user['roles'][0]['title'];
            unset($userlist[$key]['roles']);
        }

        return $userlist;
    }

    /**
     * 根据ID获取用户
     */
    public function getUserById($id)
    {
        return Db::name('user')
                ->alias('u')
                ->leftJoin(Config::get('auth.auth_group_access').' g', 'u.id=g.uid')
                ->field('id,username,nickname,last_time,last_ip,status,group_id')
                ->where('u.id', $id)
                ->find();
    }

    /**
     * 登录接口
     */
    public function login()
    {
        $validate = Validate::make([
            'username' => 'require',
            'password' => 'require',
            'captcha'  => 'require'
        ], [
            'username.require' => '请输入用户名',
            'password.require' => '请输入密码',
            'captcha.require'  => '请输入验证码'
        ]);

        $request = Request::post();

        if (!$validate->check($request)) {
            return build_api(0, $validate->getError());
        }

        $username = $request['username'];
        $password = Config::get('salt.admin_pwd').$request['password'];

        $captcha = $request['captcha'];
        if (!controller('Captcha', 'service')->check($captcha)) {
            return build_api(0, '验证码不正确');
        }

        $user = Db::name('user')->where('username', $username)->find();

        if (empty($user)) {
            return build_api(0, '账号不存在');
        } else {
            if (password_verify($password, $user['password']) === false) {
                return build_api(0, '密码错误');
            }

            if (intval($user['status']) !== 1) {
                return build_api(0, '账号已禁用');
            }

            unset($user['password']);

            Session::set('SRAF', $user);

            if (has_login()) {
                Db::name('user')->where('username', $username)->setField([
                    'last_time' => time(),
                    'last_ip' => Request::ip()
                    ]);
                return build_api(1, '登录成功', '', Request::post('callback'));
            } else {
                return build_api(0, '登录失败');
            }
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        Session::clear();

        if (has_login()) {
            return build_api(0, '退出失败');
        }
        return build_api(1, '退出成功');
    }

    /**
     * 添加用户
     */
    public function addUser()
    {
        $data = Request::post();

        if (trim($data['username']) === '') {
            return build_api(0, '请输入用户名');
        }

        if (Db::name('user')->where('username', trim($data['username']))->find()) {
            return build_api(0, '用户名已存在');
        }

        if (trim($data['nickname']) === '') {
            return build_api(0, '请输入昵称');
        }

        if (trim($data['password']) === '') {
            return build_api(0, '请输入密码');
        }

        $data['password'] = password_hash(Config::get('salt.admin_pwd').$data['password'], PASSWORD_DEFAULT);

        $group_id = $data['group_id'];
        unset($data['group_id']);

        Db::startTrans();

        $add = Db::name('user')->insertGetId($data);

        $group_access['uid'] = $add;
        $group_access['group_id'] = $group_id;

        $add_relation = Db::name(Config::get('auth.auth_group_access'))->insert($group_access);

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
            return build_api(0, '请输入昵称');
        }

        if (trim($data['password']) === '') {
            unset($data['password']);
        } else {
            $data['password'] = password_hash(Config::get('salt.admin_pwd').$data['password'], PASSWORD_DEFAULT);
        }

        $group_id = $data['group_id'];
        unset($data['group_id']);

        Db::startTrans();

        $save = Db::name('user')->update($data);

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

        $delete = Db::name('user')->where('id', $id)->delete();

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

        $update = Db::name('user')->where('id', $id)->setField('status', $status);

        return $update !== false;
    }
}
