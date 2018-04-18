<?php
namespace app\admin\behavior;

use think\facade\View;
use think\facade\Config;

/**
* Theme
*/
class Theme
{
    public function run($params)
    {
        View::config('view_path', \think\Container::get('app')->getModulePath() . 'view' . DIRECTORY_SEPARATOR . Config::get('template.view_theme') . DIRECTORY_SEPARATOR);
    }
}
