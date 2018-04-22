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
        'open'    => false,
        'name'    => '默认主题',
        'version' => '1',
        'path'    => ''
    ];

    public function run($params)
    {
        if (Config::has('theme.')) {
            $this->_config = array_merge($this->_config, Config::pull('theme'));
        }

        if ($this->_config['open']) {
            View::config('view_path', \think\Container::get('app')->getModulePath() . 'theme' . DIRECTORY_SEPARATOR . $this->_config['path'] . DIRECTORY_SEPARATOR);
        }
    }
}
