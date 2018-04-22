<?php
namespace app\admin\service;

use think\Db;
use think\facade\Config;
use think\facade\Request;

/**
* 规则服务
*/
class Rules
{
    private function getRuleData()
    {
        return Db::name(Config::get('auth.auth_rule'))->select();
    }

    /**
     * 获取权限列表
     */
    public function getNodes()
    {
        $auth_list = $this->getRuleData();

        return $this->_buildList($auth_list, 0);
    }

    /**
     * 递归组装权限列表数组
     * @param  integer $id pid
     * @return array     组装后的数组
     */
    private $authList = [];
    private function _buildList($auths, $pid = 0)
    {
        foreach ($auths as $key => $val) {
            if ($val['pid'] === $pid) {
                $this->authList[] = $val;
                unset($auths[$key]);
                $this->_buildList($auths, $val['id']);
            }
        }

        return $this->authList;
    }

    /**
     * 层级权限列表
     */
    public function treeList()
    {
        $rules = $this->getRuleData();

        return $this->_lists($rules, 0);
    }
    private function _lists($rules, $pid)
    {
        $treeRules = [];

        foreach ($rules as $k => $v) {
            if ($v['pid'] === $pid) {
                $v['children'] = $this->_lists($rules, $v['id']);
                $treeRules[] = $v;
            }
        }

        return $treeRules;
    }

    /**
     * 带索引查询列
     */
    public function ruleColumn()
    {
        return Db::name(Config::get('auth.auth_rule'))->column('name', 'id');
    }

    public function getNodeById($id)
    {
        return Db::name(Config::get('auth.auth_rule'))->where('id', $id)->find();
    }

    /**
     * 切换状态
     */
    public function toggleStatus()
    {
        $id = Request::post('id');
        $status = Request::post('status');

        $update = Db::name(Config::get('auth.auth_rule'))->where('id', $id)->setField('status', $status);

        return $update !== false;
    }

    /**
     * 删除节点
     */
    public function deleteNode()
    {
        $id = Request::param('id/d');

        $delete = Db::name(Config::get('auth.auth_rule'))->where('id', $id)->delete();

        if ($delete > 0) {
            return build_api(1, '删除成功');
        }

        return build_api(0, '删除失败');
    }

    /**
     * 添加节点
     */
    public function addNode()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '名称不能为空');
        }

        $level = Db::name(Config::get('auth.auth_rule'))->where('id', $data['pid'])->value('level');
        if (+$data['pid'] === 0) {
            $data['level'] = 0;
        } else {
            $data['level'] = +$level + 1;
        }

        $add = Db::name(Config::get('auth.auth_rule'))->insertGetId($data);

        if ($add > 0) {
            return build_api(1, '添加成功');
        }

        return build_api(0, '添加失败');
    }

    /**
     * 编辑节点
     */
    public function editNode()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '名称不能为空');
        }

        $level = Db::name(Config::get('auth.auth_rule'))->where('id', $data['pid'])->value('level');
        if (+$data['pid'] === 0) {
            $data['level'] = 0;
        } else {
            $data['level'] = +$level + 1;
        }

        $save = Db::name(Config::get('auth.auth_rule'))->update($data);

        if ($save > 0) {
            return build_api(1, '保存成功');
        }

        return build_api(1, '数据没有改动');
    }
}
