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
    private $service;

    protected function initialize()
    {
        parent::initialize();

        $this->service = \think\facade\App::controller('Menu', 'service');
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->assign('menus', $this->service->all());

        return $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $this->assign('menus', $this->service->all());

        return $this->fetch();
    }

    /**
     * 添加菜单
     */
    public function do_add()
    {
        $add = $this->service->addMenu();

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

        $edit_menu = $this->service->getMenuById($id);

        $this->assign('edit_menu', $edit_menu);

        $this->assign('menus', $this->service->all());

        return $this->fetch();
    }

    /**
     * 保存修改
     */
    public function do_edit()
    {
        $edit = $this->service->editMenu();

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
        $delete = $this->service->deleteMenu();

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
        if ($this->service->toggleStatus()) {
            $this->success('成功');
        } else {
            $this->error('失败');
        }
    }
}
