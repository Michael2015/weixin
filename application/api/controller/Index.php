<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    private $user_id;
    private $table_name;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_id = $this->auth->getUser()->id;
        //$this->user_id = 1;
        $this->table_name = 'fa_read_article_log_'.date('Ymd');
    }

    /**
     * 点击【阅读文章】
     */
    public function log()
    {
        $tableIsExist = Db::query("show tables like '{$this->table_name}'; ");

        $sql = '';
        //如果表存在就需要插入数据，没有表则创建表
        if(!$tableIsExist)
        {
            $sql .= 'create table if not exists '.$this->table_name.'(';
            $sql .= 'id INT UNSIGNED AUTO_INCREMENT,';
            $sql .= '`article_id` INT UNSIGNED  NOT NULL,';
            $sql .= '`user_id` INT UNSIGNED  NOT NULL,';
            $sql .= '`is_valid` TINYINT UNSIGNED DEFAULT 0  NOT NULL,';
            $sql .= '`is_disable` TINYINT UNSIGNED DEFAULT 0  NOT NULL,';
            $sql .= '`createtime` datetime NOT NULL,';
            $sql .= 'PRIMARY KEY (id)';
            $sql .= ')ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            Db::query($sql);
            Db::query('ALTER TABLE '.$this->table_name.' ADD unique index(`article_id`,`user_id`);');
        }

        //生成随机文章
        $article_info  = Db::query("select id,article_url,max_read_number,read_amount from fa_article where is_valid = 1 and  id not in (select article_id from fa_user_article_relation_log where user_id = {$this->user_id}) limit 1; ");
        if($article_info)
        {
            $article_id = $article_info[0]['id'];

            //更新阅读量
            if($article_info[0]['read_amount'] <= $article_info[0]['max_read_number'])
            {
                Db::name('article')->where(['id'=>$article_id])->inc('read_amount',1)->update();
                Db::name('read_article_log_'.date('Ymd'))->insert(['user_id'=>$this->user_id,'article_id'=>$article_id,'createtime'=>date('Y-m-d H:i:s')]);
                Db::name('user_article_relation_log')->insert(['user_id'=>$this->user_id,'article_id'=>$article_id]);
            }
            else
            {
                Db::name('article')->where(['id'=>$article_id])->update(['is_valid'=>0]);
            }
            $return['article_url'] = $article_info[0]['article_url'];
            $this->success('success',$return);
        }
        else
        {
           $this->error('empty'); 
        }
    }

}
