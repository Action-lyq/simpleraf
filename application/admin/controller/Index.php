<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function initialize()
    {
    }

    public function index()
    {
        dump($this->view->engine);
        return $this->fetch();
    }
}
