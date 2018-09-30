<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    private $user_id;
    private $table_name;

    public function _initialize()
    {
        parent::_initialize();
        //$this->user_id = $this->auth->getUser()->id;
        $this->user_id = 1;
        $this->table_name = 'fa_read_article_log_'.date('Ymd');
    }

    /**
     * 点击【阅读文章】
     */
    public function log()
    {
        $article_id = $this->request->param('article_id',0);
        if(!$article_id)
        {
            die('非法访问');
        }
        $tableIsExist = Db::query("show tables like '{$this->table_name}'; ");

        $sql = '';
        //如果表存在就需要插入数据，没有表则创建表
        if(!$tableIsExist)
        {
            $sql .= 'create table if not exists '.$this->table_name.'(';
            $sql .= 'id INT UNSIGNED AUTO_INCREMENT,';
            $sql .= '`article_id` INT UNSIGNED  NOT NULL,';
            $sql .= '`user_id` INT UNSIGNED  NOT NULL,';
            $sql .= '`createtime` datetime NOT NULL,';
            $sql .= 'PRIMARY KEY (id)';
            $sql .= ')ENGINE=InnoDB DEFAULT CHARSET=utf8;';
            Db::query($sql);
            Db::query('ALTER TABLE '.$this->table_name.' ADD unique index(`article_id`,`user_id`);');
        }
        Db::name('read_article_log_'.date('Ymd'))->insert(['user_id'=>$this->user_id,'article_id'=>$article_id,'createtime'=>date('Y-m-d H:i:s')]);
        $this->success('请求成功');
    }

}
