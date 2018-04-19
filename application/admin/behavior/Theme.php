<?php
namespace app\admin\behavior;

use think\facade\View;
use think\facade\Config;
use think\Db;

/**
* Theme
*/
class Theme
{
    private $_config = [
        'theme_type'    => 'local',// local db
        'db_table'      => '',// 是否使用数据表，空为不使用
        'db_field'      => '',// 主题目录
        'local_path'    => '',// 主题目录
    ];

    public function run($params)
    {
        if (Config::has('theme.')) {
            $this->_config = array_merge($this->_config, Config::pull('theme'));
        }

        $theme_path = '';

        if ($this->_config['theme_type'] === 'db' && $this->_config['db_table'] !== '') {
            //
        } else {
            $theme_path = $this->_config['local_path'];
        }

        if ($theme_path !== '') {
            $theme_path .= DIRECTORY_SEPARATOR;
        }

        View::config('view_path', \think\Container::get('app')->getModulePath() . 'view' . DIRECTORY_SEPARATOR . $theme_path);
    }
}
