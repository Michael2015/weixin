<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;
use think\Url;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';


    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        return $this->view->fetch();
    }

    //收藏网址教程
    public function  star()
    {
        return $this->view->fetch();
    }

    //提现申请
    public function withdraw()
    {
    }

}
