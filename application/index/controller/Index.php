<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;

class Index extends Frontend
{

    protected $noNeedLogin = '';
    protected $noNeedRight = '*';
    protected $layout = '';
    private $table_name;


    public function _initialize()
    {
        $this->table_name = 'fa_read_article_log_'.date('Ymd');
        parent::_initialize();
    }

    public function index()
    {
        $user_id = $this->auth->getUser()->id;
        //$user_id = 1;
        
        $tableIsExist = Db::query("show tables like '{$this->table_name}'; ");
        $alert_status = 0; 
        $all_article_count = 0;
        $valid_article_count = 0;
        if($tableIsExist)
        {
             //获取用户今天阅读文章总阅读量
            $all_article_count =  Db::name('read_article_log_'.date('Ymd'))->where(['user_id'=>$user_id])->count();
            if( $all_article_count > 0 )
            {
                $log_info =  Db::name('read_article_log_'.date('Ymd'))->field('id,createtime,is_disable')->where(['user_id'=>$user_id])->order('createtime','desc')->find();
                if($log_info &&  $log_info['is_disable'] === 0 && strtotime($log_info['createtime']) <= strtotime('-5 seconds'),time())
                {
                 $alert_status =  1;
                  //$last_one  = Db::name('read_article_log_'.date('Ymd'))->where(['user_id'=>$user_id])->order('createtime','desc')->field('id')->limit(1)->find();
                 //将文章更新为有效
                 $result = Db::name('read_article_log_'.date('Ymd'))->where(['id'=>$log_info['id'],'is_valid'=>0])->update(['is_valid'=>1]);
                 if($result)
                 {
                      //将对应的用户积分更新
                    Db::name('user')->where(['id'=>$user_id])->inc('score',3)->update();
                }
            }
            else
            {
                $alert_status =  2;
            }
             Db::name('read_article_log_'.date('Ymd'))->where(['id'=>$log_info['id'],'is_disable'=>0])->update(['is_disable'=>1]);
        }
            //获取用户今天有效阅读数
        $valid_article_count =  Db::name('read_article_log_'.date('Ymd'))->where(['user_id'=>$user_id,'is_valid'=>1])->count();
    }
    //\think\Cookie::get('token');
    $token = \think\Cookie::get('token');
    $this->assign('token',$token); 
    //会员ID
    //$this->auth->getUser()->score
    $this->assign('user_id',$user_id); 
        //获取该用户余额
    $this->assign('score',number_format(50/100,2)); 
    $this->assign('all_article_count',$all_article_count); 
    $this->assign('valid_article_count',$valid_article_count); 
    $this->assign('alert_status',$alert_status);
    return $this->view->fetch();
}

    //收藏网址教程
public function  star()
{
    return $this->view->fetch();
}

}
