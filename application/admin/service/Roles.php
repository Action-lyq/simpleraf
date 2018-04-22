<?php
namespace app\admin\service;

use think\Db;
use think\facade\Config;
use think\facade\Request;

/**
* 角色组
*/
class Roles
{
    /**
     * 获取所有角色
     */
    public function getRoles()
    {
        $roles = Db::name(Config::get('auth.auth_group'))->field('id,title,status')->select();

        return $roles;
    }

    /**
     * 获取可用的角色
     */
    public function getAvailableRoles()
    {
        return Db::name(Config::get('auth.auth_group'))->where('status', 1)->select();
    }

    /**
     * 切换状态
     */
    public function toggleStatus()
    {
        $id = Request::post('id');
        $status = Request::post('status');

        $update = Db::name(Config::get('auth.auth_group'))->where('id', $id)->setField('status', $status);

        return $update !== false;
    }

    /**
     * 添加角色
     */
    public function addRole()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '请输入角色名');
        }

        if (Db::name(Config::get('auth.auth_group'))->where('title', trim($data['title']))->find()) {
            return build_api(-1, '角色名已存在');
        }

        $add = Db::name(Config::get('auth.auth_group'))->insertGetId($data);

        if ($add > 0) {
            return build_api(1, '添加成功');
        }

        return build_api(0, '添加失败');
    }

    /**
     * 编辑角色
     */
    public function editRole()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '请输入角色名');
        }

        if (Db::name(Config::get('auth.auth_group'))->where('id', '<>', $data['id'])->where('title', trim($data['title']))->find()) {
            return build_api(-1, '角色名已存在');
        }

        $save = Db::name(Config::get('auth.auth_group'))->update($data);

        if ($save > 0) {
            return build_api(1, '保存成功');
        }

        return build_api(1, '数据没有改动');
    }

    /**
     * 删除角色
     */
    public function deleteRole()
    {
        $id = Request::param('id/d');

        Db::startTrans();

        $delete_group = Db::name(Config::get('auth.auth_group'))->where('id', $id)->delete();
        $delete_group_access = Db::name(Config::get('auth.auth_group_access'))->where('group_id', $id)->delete();

        if ($delete_group !== false && $delete_group_access !== false) {
            Db::commit();
            return build_api(1, '删除成功');
        } else {
            Db::rollback();
            return build_api(0, '删除失败');
        }
    }

    /**
     * 根据ID获取角色
     */
    public function getRoleById($id)
    {
        return Db::name(Config::get('auth.auth_group'))->where('id', $id)->find();
    }

    /**
     * 保存权限分配
     */
    public function saveRules()
    {
        $data = Request::post();

        if (intval($data['gid']) < 1) {
            return build_api(-1, '参数错误');
        }

        $rules = '';
        if (isset($data['nodes'])) {
            $rules = implode(',', $data['nodes']);
        }

        $save = Db::name(Config::get('auth.auth_group'))->where('id', $data['gid'])->setField('rules', $rules);

        if ($save > 0) {
            return build_api(1, '保存成功');
        }

        return build_api(1, '数据没有改动');
    }
}
