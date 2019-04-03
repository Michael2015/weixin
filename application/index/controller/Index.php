<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '';
    protected $noNeedRight = '*';


    public function _initialize()
    {
        $user = $this->auth->id;
        print_r($user);

        parent::_initialize();
    }

    public function index()
    {
        $video_id = input('video_id','9');
        $video_list = db('channel')->select();
        $current_video = db('channel')->where(['id'=>$video_id])->find();
        if(!$current_video) die('非法访问');
        $id = $current_video['id'];
        $current_video_url = $current_video['url'];
        $this->assign('current_id',$video_id);
        $this->assign('current_video_url',$current_video_url);
        $this->assign('video_list',$video_list);
        $this->assign('last_video_id',max(1,$id) == 1 ? 1 : $id - 1);
        $this->assign('next_video_id',count($video_list) - $id < 0 ? count($video_list) : $id + 1);
        return $this->view->fetch();
    }
}
