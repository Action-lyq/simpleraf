<?php
namespace app\admin\controller\systemctl;

use think\Db;
use think\facade\Request;

/**
* User
*/
class User extends \app\admin\controller\Base
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 列表
     */
    public function index()
    {
        $users = controller('User', 'service')->userList();
        $this->assign('users', $users);

        return $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $roles = controller('Roles', 'service')->getAvailableRoles();
        $this->assign('roles', $roles);

        return $this->fetch();
    }

    /**
     * 添加用户
     */
    public function do_add()
    {
        $add = controller('User', 'service')->addUser();

        if ($add['code'] === 1) {
            $this->success($add['msg']);
        } else {
            $this->error($add['msg']);
        }
    }

    /**
     * 编辑页面
     */
    public function edit()
    {
        $id = Request::param('id/d');

        if ($id <= 1) {
            $this->redirect('admin/systemctl.users/index');
        }

        $edit_user = controller('User', 'service')->getUserById($id);

        $this->assign('edit_user', $edit_user);

        $roles = controller('Roles', 'service')->getAvailableRoles();
        $this->assign('roles', $roles);

        return $this->fetch();
    }

    /**
     * 保存修改
     */
    public function do_edit()
    {
        $edit = controller('User', 'service')->editUser();

        if ($edit['code'] === 1) {
            $this->success($edit['msg']);
        } else {
            $this->error($edit['msg']);
        }
    }

    /**
     * 删除用户
     */
    public function do_delete()
    {
        $delete = controller('User', 'service')->deleteUser();

        if ($delete['code'] === 1) {
            $this->success($delete['msg']);
        } else {
            $this->error($delete['msg']);
        }
    }

    /**
     * 改变状态
     */
    public function update_status()
    {
        $status = controller('User', 'service')->toggleStatus();

        if (is_bool($status)) {
            if ($status) {
                $this->success('成功');
            } else {
                $this->error('失败');
            }
        } else {
            $this->error('失败', null, $status['data']);
        }
    }
}
