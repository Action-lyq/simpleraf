<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Request;

/**
* Nodes
*/
class Nodes extends \app\admin\controller\Base
{
    private $service;

    protected function initialize()
    {
        parent::initialize();

        $this->service = \think\facade\App::controller('Rules', 'service');
    }

    /**
     * 列表
     */
    public function index()
    {
        $auths = $this->service->getNodes();
        $this->assign('auths', $auths);

        return $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $auths = $this->service->getNodes();
        $this->assign('auths', $auths);

        return $this->fetch();
    }

    /**
     * 添加用户
     */
    public function do_add()
    {
        $add = $this->service->addNode();

        if ($add['code'] === 1) {
            $this->success($add['data']);
        } else {
            $this->error($add['data']);
        }
    }

    /**
     * 编辑页面
     */
    public function edit()
    {
        $id = Request::param('id/d');

        $auths = $this->service->getNodes();
        $this->assign('auths', $auths);

        $node = $this->service->getNodeById($id);
        $this->assign('node', $node);

        return $this->fetch();
    }

    /**
     * 保存修改
     */
    public function do_edit()
    {
        $edit = $this->service->editNode();

        if ($edit['code'] === 1) {
            $this->success($edit['data']);
        } else {
            $this->error($edit['data']);
        }
    }

    /**
     * 删除用户
     */
    public function do_delete()
    {
        $delete = $this->service->deleteNode();

        if ($delete['code'] === 1) {
            $this->success($delete['data']);
        } else {
            $this->error($delete['data']);
        }
    }

    /**
     * 改变状态
     */
    public function update_status()
    {
        $status = $this->service->toggleStatus();

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