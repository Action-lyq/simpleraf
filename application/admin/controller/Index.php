<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;

class Index extends Base
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $version = Db::query('SELECT VERSION() AS ver');

        $config  = [
            'url'             => ($_SERVER['SERVER_PORT'] === '80' ? 'http://' : 'https://').$_SERVER['HTTP_HOST'],
            'document_root'   => $_SERVER['DOCUMENT_ROOT'],
            'server_os'       => PHP_OS,
            'server_port'     => $_SERVER['SERVER_PORT'],
            'server_soft'     => $_SERVER['SERVER_SOFTWARE'],
            'php_version'     => PHP_VERSION,
            'mysql_version'   => $version[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize'),
            'client_ip'       => Request::ip()
        ];

        $this->assign('config', $config);

        return $this->fetch();
    }
}
