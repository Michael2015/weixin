<?php

namespace app\task;
use app\common\library\Curl2;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Log;

class Channel extends Command
{
    protected function configure()
    {
        $this->setName('channel')->setDescription('Here is the remark ');
    }

    protected function execute(Input $input, Output $output)
    {
       /* $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, 'http://news.tvb.com/live/');
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $content = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        preg_match('#<source src="(.*?)"[^>]+>#im',$content,$match);
        if($match)
        {
            db('channel')->where('id','in',[186,193])->update(['url'=>$match[1]]);
        }*/
        //Log::write('测试时间:'.date('Y-m-d H:i:s'));
        //更新翡翠台 源-http://m.leshi123.com/gangaotai/tvb.html

        $headers = array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3','Host: 123.207.42.38','User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36','Accept-Language: zh-CN,zh;q=0.9,en;q=0.8','Connection: keep-alive');
        $ch = curl_init('http://123.207.42.38/tvb.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_COOKIE,'Hm_lvt_79fefef98779e9cad665afcfb2b19165=1555131265,1555131445,1555131465,1555131569; Hm_lpvt_79fefef98779e9cad665afcfb2b19165=1555131569');
        $response = curl_exec($ch);
        curl_close($ch);

        preg_match('#http://hls51-o.kascend.com/chushou_live/(.*?)\.m3u8#', $response, $matches);
        if($matches)
        {
            db('channel')->where(['id'=>182])->update(['url'=>$matches[0]]);
        }

        /* $cookie = Curl2::curl_request('http://wx.ottcom.cn/login/mlogin',['uname'=>'18928221189','upass'=>'111111'],0,1);

         //j2/j2备用/翡翠台（备用）/有线新闻
         $video_ids = [184=>3590,185=>3344,183=>3574,200=>3586];

         foreach ($video_ids as $key=>$video_id)
         {
             $video_play = Curl2::curl_request('http://wx.ottcom.cn/news/view/id/'.$video_id,[],$cookie['cookie'],0);
             preg_match('#<video[\s]+src="(.*?)"#im',$video_play,$match2);
             $video_url = $match2[1];
             //preg_match('#<h2[^>]+>(.*?)<\/h2>#is',$video_play,$match3);
             //$video_name = trim($match3[1]);
             db('channel')->where(['id'=>$key])->update(['url'=>$video_url]);

         }*/
    }
    /*  protected function execute(Input $input, Output $output)
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
      }*/

}
