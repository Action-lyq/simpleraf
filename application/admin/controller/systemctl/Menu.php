<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Request;

/**
* 菜单
*/
class Menu extends \app\admin\controller\Base
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
        $this->assign('menus', controller('Menu', 'service')->all());

        return $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $this->assign('menus', controller('Menu', 'service')->all());

        return $this->fetch();
    }

    /**
     * 添加菜单
     */
    public function do_add()
    {
        $add = controller('Menu', 'service')->addMenu();

        if ($add['code'] === 1) {
            $this->success($add['msg']);
        } else {
            $this->error($add['msg']);
        }
    }

    /**
     * 编辑菜单
     */
    public function edit()
    {
        $id = Request::param('id');

        if (intval($id) <= 0) {
            $this->redirect('admin/systemctl.menu/index');
        }

        $edit_menu = controller('Menu', 'service')->getMenuById($id);

        $this->assign('edit_menu', $edit_menu);

        $this->assign('menus', controller('Menu', 'service')->all());

        return $this->fetch();
    }

    /**
     * 保存修改
     */
    public function do_edit()
    {
        $edit = controller('Menu', 'service')->editMenu();

        if ($edit['code'] === 1) {
            $this->success($edit['msg']);
        } else {
            $this->error($edit['msg']);
        }
    }

    /**
     * 删除菜单
     */
    public function do_delete()
    {
        $delete = controller('Menu', 'service')->deleteMenu();

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
        if (controller('Menu', 'service')->toggleStatus()) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }
}
