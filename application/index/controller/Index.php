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
        //是否是会员
        $is_level = $this->user['gender'];
        //会员到期日期
        $birthday = $this->user['birthday'];
        $deadline = $this->user['deadline'];

        $video_id = input('video_id',182);
        $video_list = db('channel')->where(['status'=>1,'id'=>['<=',181]])->select();
        $video_list2 = db('channel')->where(['status'=>1,'id'=>['>',181]])->select();

        $video_list = array_merge($video_list2,$video_list);

        $current_video = db('channel')->where(['id'=>$video_id])->find();
        if(!$current_video) die('非法访问');
        $id = $current_video['id'];
        $current_video_url = $current_video['url'];
        $msg = '';

        $is_allow_assit = 0;
        $left_days = 0;
        //体验日期是否已经过
        if($is_level == 0)
        {
            $is_allow_assit = 1;
            if(time() > $deadline)
            {
                $msg = '尊敬的'.$this->auth->username.'用户，您的观看体验期已过，如需开通会员请添加客服微信：shan47636';
                $current_video_url = '';
            }
            else
            {
                $left_days = ceil(($deadline - time()) / 86400);
            }
        }

        //会员日期是否过
        if($is_level == 1)
        {
            if(time() > strtotime($birthday))
            {
                $msg = '尊敬的'.$this->auth->username.'会员，您购买的会员已过期，如需继续开通会员请添加客服微信：shan47636';
                $current_video_url = '';
            }
            else
            {
                $left_days = ceil((strtotime($birthday) - time()) / 86400);
            }
        }

        $current_video_name = $current_video['name'];
        $this->assign('is_allow_assit',$is_allow_assit);
        $this->assign('user_id',$this->auth->id);
        $this->assign('left_days',$left_days);
        $this->assign('msg',$msg);
        $this->assign('current_id',$video_id);
        $this->assign('current_video_url',$current_video_url);
        $this->assign('current_video_name',$current_video_name);
        $this->assign('video_list',$video_list);
        $this->assign('last_video_id',max(1,$id) == 1 ? 1 : $id - 1);
        $this->assign('next_video_id',count($video_list) - $id < 0 ? count($video_list) : $id + 1);
        return $this->view->fetch();
    }

    //邀请页面

    public function assit()
    {
        $this->assign('user_id',$this->auth->id);
        return $this->view->fetch();
    }


}
