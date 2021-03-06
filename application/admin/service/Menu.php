<?php
namespace app\admin\service;

use think\Db;
use think\facade\Request;
use think\facade\Url;

/**
* 菜单生成接口
*/
class Menu
{
    private $route = '';
    private $url = '';
    private $parent_url = '';

    /**
     * 构建递归菜单代码
     * @return string 菜单HTML
     */
    public function build()
    {
        $this->route = request_route();
        $this->url = Url::build($this->route);

        $pid = Db::name('menu')
            ->where('route', $this->route)
            ->where('is_page', 1)
            ->value('pid');
        if ($pid) {
            $parent_route = Db::name('menu')->where('id', $pid)->value('route');
            $this->parent_url = Url::build($parent_route);
        }

        $menus = $this->getMenus();
        $menus = $this->createTree($menus, 0);

        $menu_html = $this->createHTML($menus);

        return $menu_html;
    }

    /**
     * 构建顺序菜单
     * @return array 顺序菜单
     */
    public function all()
    {
        $menus = $this->getMenus(true);
        $menus = $this->createSort($menus);
        return $menus;
    }

    /**
     * 查询数据库所有菜单
     * @param  boolean $is_all 是否查询所有
     * @return array          原始菜单
     */
    private function getMenus($is_all = false)
    {
        if ($is_all) {
            $menus = Db::name('menu')->order(['sort' => 'desc', 'id'])->select();
        } else {
            $menus = Db::name('menu')
                ->field('id,pid,title,level,icon,route')
                ->where('is_page', 0)
                ->where('status', 1)
                ->order(['sort' => 'desc', 'id'])
                ->select();
        }

        return $menus;
    }

    /**
     * 创建递归数组
     * @param  array  $menus 原始数据
     * @param  integer $pid   pid
     * @return array         层级菜单
     */
    private function createTree($menus, $pid = 0)
    {
        $tree = [];

        foreach ($menus as $menu) {
            if ($menu['pid'] === $pid) {
                $menu['child'] = $this->createTree($menus, $menu['id']);
                $menu['has_child'] = !empty($menu['child']);
                $tree[] = $menu;
            }
        }

        return $tree;
    }

    /**
     * 递归排序方法
     */
    private function createSort($menus, $pid = 0)
    {
        $tree = [];

        foreach ($menus as $menu) {
            if ($menu['pid'] === $pid) {
                $tree[] = $menu;
                $tree = array_merge($tree, $this->createSort($menus, $menu['id']));
            }
        }

        return $tree;
    }

    /**
     * 创建HTML
     * @param  array $menus 菜单数组
     * @return string        菜单HTML
     */
    private function createHTML($menus)
    {
        $html = '';

        /*foreach ($menus as $menu) {
            $html .= '<li class="' . ($this->url === Url::build($menu['route']) ? 'active' : ($this->parent_url === Url::build($menu['route']) || $this->url === Url::build($menu['route']) ? 'active' : '')) . '">';

            $html .= '<a href="' . ($menu['route'] === '' ? 'javascript:;' : Url::build($menu['route'])) . '">' . ($menu['icon'] === '' ? '' : '<i class="' . $menu['icon'] . '"></i> ') . ($menu['pid'] === 0 ? '<span class="menu-title">' . $menu['title'] . '</span>' : $menu['title']) . ($menu['has_child'] ? '<i class="arrow"></i>' : '') . '</a>';

            if ($menu['has_child']) {
                $html .= '<ul class="collapse">' . $this->createHTML($menu['child']) . '</ul>';
            }

            $html .= '</li>';
        }*/

        foreach ($menus as $menu) {
            $html .= $menu['has_child'] ? '<li class="px-nav-item px-nav-dropdown">' : '<li class="px-nav-item' . ($this->parent_url === Url::build($menu['route']) || $this->url === Url::build($menu['route']) ? ' active' : '') . '">';

            $html .= '<a href="' . ($menu['route'] === '' ? 'javascript:;' : Url::build($menu['route'])) . '">' . ($menu['icon'] === '' ? '' : '<i class="' . $menu['icon'] . '"></i> ') . '<span class="px-nav-label">' . $menu['title'] . '</span></a>';

            if ($menu['has_child']) {
                $html .= '<ul class="px-nav-dropdown-menu">' . $this->createHTML($menu['child']) . '</ul>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    /**
     * 切换状态
     */
    public function toggleStatus()
    {
        $id = Request::post('id');
        $status = Request::post('status');

        $update = Db::name('menu')->where('id', $id)->setField('status', $status);

        return $update !== false;
    }

    /**
     * 添加菜单
     */
    public function addMenu()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '请输入名称');
        }

        $data['level'] = (intval($data['pid']) === 0 ? 0 : Db::name('menu')->where('id', $data['pid'])->value('level') + 1);

        $add = Db::name('menu')->insertGetId($data);

        if ($add > 0) {
            return build_api(1, '添加成功');
        }

        return build_api(0, '添加失败');
    }

    /**
     * 编辑菜单
     */
    public function editMenu()
    {
        $data = Request::post();

        if (trim($data['title']) === '') {
            return build_api(-1, '请输入名称');
        }

        $data['level'] = (intval($data['pid']) === 0 ? 0 : Db::name('menu')->where('id', $data['pid'])->value('level') + 1);

        $save = Db::name('menu')->update($data);

        if ($save > 0) {
            return build_api(1, '保存成功');
        }

        return build_api(0, '保存失败');
    }

    /**
     * 删除菜单
     */
    public function deleteMenu()
    {
        $id = Request::param('id/d');

        $delete = Db::name('menu')->where('id', $id)->delete();

        if ($delete > 0) {
            return build_api(1, '删除成功');
        }

        return build_api(0, '删除成功');
    }

    /**
     * 根据ID获取菜单
     */
    public function getMenuById($id)
    {
        return Db::name('menu')->where('id', $id)->find();
    }

    /**
     * 面包屑
     */
    public function breadcrumb()
    {
        $this->route = request_route();

        $menus = $this->getMenus(true);
        $c_menu = [];

        foreach ($menus as $menu) {
            if ($menu['route'] === $this->route) {
                $c_menu = $menu;
                break;
            }
        }

        $tree = [$c_menu];
        $this->createBreadcrumb($menus, $c_menu, $tree);

        $html = '';
        foreach ($tree as $menu) {
            $html .= '<li' . ($c_menu === $menu ? ' class="active"' : '') . '>' . ($c_menu === $menu ? $menu['title'] : ($menu['route'] === '' ? $menu['title'] : '<a href="' . Url::build($menu['route'] . '">' . $menu['title'] . '</a>'))) . '</li>';
        }
        return ['html' => $html, 'title' => $c_menu['title']];
    }

    /**
     * 创建面包屑数组
     */
    private function createBreadcrumb($menus, $c_menu, &$tree)
    {
        foreach ($menus as $menu) {
            if ($c_menu['pid'] > 0) {
                if ($menu['id'] === $c_menu['pid']) {
                    array_unshift($tree, $menu);
                    $this->createBreadcrumb($menus, $menu, $tree);
                }
            }
        }
    }
}
