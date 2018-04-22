<?php
namespace app\admin\controller\systemctl;

use think\Controller;
use think\Db;
use think\facade\Request;

/**
* 系统配置
*/
class Configs extends \app\admin\controller\Base
{
    private $service;

    protected function initialize()
    {
        parent::initialize();
        
        $this->service = \think\facade\App::controller('Configs', 'service');
    }

    /**
     * 列表
     */
    public function index()
    {
        $configs = $this->service->getConfigs();
        $this->assign('configs', $configs);

        return $this->fetch();
    }

    /**
     * 保存配置
     */
    public function save_configs()
    {
        $save = $this->service->saveConfigs();

        if ($save['code'] === 1) {
            $this->success($save['data']);
        } else {
            $this->error($save['data']);
        }
    }

    /**
     * 测试邮件配置
     */
    public function test_smtp()
    {
        $data = Request::post();

        $send = smtp_send([
            'receiver' => $data['receiver'],
            'title' => $data['title'],
            'body' => $data['body'],
            'altbody' => $data['body']
            ]);

        if ($send === true) {
            $this->success('发送成功');
        } else {
            $this->error('发送失败: '.$send);
        }
    }
}