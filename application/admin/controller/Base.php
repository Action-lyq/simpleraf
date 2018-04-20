<?php
namespace app\admin\controller;

use think\Controller;
use think\facade\Request;
use think\facade\Config;
use think\facade\Url;

/**
* 后台首页
*/
class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();

        if (has_login() === false) {
            $this->redirect('admin/systemctl.control/login', ['callback' => urlencode($this->request->url(true))]);
        }

        $this->assign('requestPath', Url::build(request_route()));
        $this->assign('requestIndex', Url::build(strtolower(Request::module() . '/' . Request::controller() . '/index')));
    }

    /**
     * wangEditor 图片上传
     */
    public function weuploads()
    {
        $pics = [];
        $msg = [];

        $path = Request::get('path');

        $files = Request::file('image');
        foreach ($files as $file) {
            $info = $file->rule('uniqid')->move('./uploads/'.$path.'/');
            if ($info) {
                $pics[] = '//img.'.Config::get('url_domain_root').'/'.$path.'/'.$info->getSaveName();
                $msg[] = '';
            } else {
                $pics[] = false;
                $msg[] = $file->getError();
            }
        }

        if (in_array(false, $pics)) {
            $response['errno'] = 1;
            $response['data'] = [];
            $response['msg'] = '';
        } else {
            $response['errno'] = 0;
            $response['data'] = $pics;
            $response['msg'] = $msg;
        }

        return json($response);
    }
}
