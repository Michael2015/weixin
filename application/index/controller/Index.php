<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;
use think\Url;

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
                if($log_info &&  $log_info['is_disable'] == 0 && strtotime($log_info['createtime']) <= strtotime('-5 seconds',time()))
                {
                    $alert_status =  1;
                    //$last_one  = Db::name('read_article_log_'.date('Ymd'))->where(['user_id'=>$user_id])->order('createtime','desc')->field('id')->limit(1)->find();
                    //将文章更新为有效
                    $result = Db::name('read_article_log_'.date('Ymd'))->where(['id'=>$log_info['id'],'is_valid'=>0])->update(['is_valid'=>1]);
                    if($result)
                    {
                        //将对应的用户积分更新
                        Db::name('user')->where(['id'=>$user_id])->inc('score',2)->update();
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
        $nickname = $this->auth->getUser()->nickname;
        $token = \think\Cookie::get('token');
        $score = $this->auth->getUser()->score;

        $this->assign('nickname',$nickname);
        $this->assign('token',$token);
        //会员ID
        $this->assign('user_id',$user_id);
        //获取该用户余额
        $this->assign('score',number_format($score/100,2));
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

    //提现申请
    public function withdraw()
    {
        $score = $this->auth->getUser()->score;
        $nickname = $this->auth->getUser()->nickname;
        $user_id = $this->auth->getUser()->id;

        $amount = $this->request->param('amount');
        $wechat = $this->request->param('wechat');
        if(empty($amount) || empty($wechat))
        {
            $this->error('数字为空，提现失败',Url::build('/'));
        }
        if(!is_numeric($amount))
        {
            $this->error('类型不对，提现失败',Url::build('/'));
        }
        $amount = (int)$amount*100;
        if($amount > $score)
        {
            $this->error('余额不足，提现失败',Url::build('/'));
        }
        //微信只绑定一次
        if($nickname && $wechat !== $nickname)
        {
            $this->error('微信只能绑定一次，提现失败',Url::build('/'));
        }
        //账户减积分
        Db::name('user')->dec('score',$amount)->where(['id'=>$user_id])->update(['nickname'=>$wechat]);
        //插入提现记录
        Db::name('user_withdraw_log')->insert(['user_id'=>$user_id,'amount'=>$amount,'wechat'=>$wechat,'createtime'=>date('Y-m-d H:i:s')]);
        $this->success('提现成功，稍后工作人员联系您');
    }

}
