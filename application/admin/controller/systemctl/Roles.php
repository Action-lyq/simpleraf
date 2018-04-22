<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Request;

/**
* Roles
*/
class Roles extends \app\admin\controller\Base
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
        $roles = controller('Roles', 'service')->getRoles();

        $this->assign('roles', $roles);

        return $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * 添加用户
     */
    public function do_add()
    {
        $add = controller('Roles', 'service')->addRole();

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

        $edit_role = controller('Roles', 'service')->getRoleById($id);

        $this->assign('edit_role', $edit_role);

        return $this->fetch();
    }

    /**
     * 保存修改
     */
    public function do_edit()
    {
        $edit = controller('Roles', 'service')->editRole();

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
        $delete = controller('Roles', 'service')->deleteRole();

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
        if (controller('Roles', 'service')->toggleStatus()) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }

    /**
     * 权限分配页面
     */
    public function rules()
    {
        $gid = Request::param('gid/d', 0);

        if ($gid < 1) {
            $this->redirect('admin/systemctl.roles/index');
        }

        $groupinfo = controller('Roles', 'service')->getRoleById($gid);
        $this->assign('groupinfo', $groupinfo);

        $owned = explode(',', $groupinfo['rules']);
        $this->assign('owned', $owned);

        $rules_service = \think\facade\App::controller('Rules', 'service');

        $nodes = $rules_service->treeList();
        $this->assign('nodes', $nodes);

        $rules = $rules_service->ruleColumn();
        $this->assign('rules', $rules);

        return $this->fetch();
    }

    /**
     * 保存角色权限
     * @return json 保存状态
     */
    public function save_rules()
    {
        $save = controller('Roles', 'service')->saveRules();

        if ($save['code'] === 1) {
            $this->success($save['msg'], 'admin/systemctl.roles/index');
        } else {
            $this->error($save['msg']);
        }
    }
}
