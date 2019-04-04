<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '';
    protected $noNeedRight = '*';
    private $user;


    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->auth->getUser();
    }

    public function index()
    {
        $is_level = $this->user['level'];
        $createtime = $this->user['createtime'];

        $video_id = input('video_id',1);
        $video_list = db('channel')->where('name','<>','测试')->select();
        $current_video = db('channel')->where(['id'=>$video_id])->find();
        if(!$current_video) die('非法访问');
        $id = $current_video['id'];

        $current_video_url = $current_video['url'];
        if($is_level == 1 && time() > strtotime('+3 days',$createtime))
        {
            $current_video_url = '';
        }
        $current_video_name = $current_video['name'];

        $this->assign('user_id',$this->auth->id);
        $this->assign('current_id',$video_id);
        $this->assign('current_video_url',$current_video_url);
        $this->assign('current_video_name',$current_video_name);
        $this->assign('video_list',$video_list);
        $this->assign('last_video_id',max(1,$id) == 1 ? 1 : $id - 1);
        $this->assign('next_video_id',count($video_list) - $id < 0 ? count($video_list) : $id + 1);
        return $this->view->fetch();
    }
}
