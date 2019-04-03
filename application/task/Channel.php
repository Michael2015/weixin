<?php

namespace app\task;
use app\common\library\Curl2;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Channel extends Command
{
    protected function configure()
    {
        $this->setName('channel')->setDescription('Here is the remark ');
    }
    protected function execute(Input $input, Output $output)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, 'http://wx.ottcom.cn/');
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $content = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        preg_match_all('#<a[\s]*href="/news/view/id/(.*?)">#im',$content,$match);

        if($match)
        {
            $cookie = Curl2::curl_request('http://wx.ottcom.cn/login/mlogin',['uname'=>'13713018282','upass'=>'aaaaaa'],0,1);
            foreach ($match[1] as $video_id)
            {
                $video_play = Curl2::curl_request('http://wx.ottcom.cn//news/view/id/'.$video_id,[],$cookie['cookie'],0);
                preg_match('#<video[\s]+src="(.*?)"#im',$video_play,$match2);
                $video_url = $match2[1];
                preg_match('#<h2[^>]+>(.*?)<\/h2>#is',$video_play,$match3);
                $video_name = trim($match3[1]);
                if(db('channel')->where(['name'=>$video_name])->find())
                {
                    db('channel')->where(['name'=>$video_name])->update(['url'=>$video_url]);
                }
                else
                {
                    db('channel')->insert(['name'=>$video_name,'url'=>$video_url]);
                }
            }
        }
    }

}
