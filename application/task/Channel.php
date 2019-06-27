<?php

namespace app\task;
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
        db('channel')->delete(true);
        $url = "http://wintv.ottcom.cn/admin/LiveChannel/ManageList.aspx";
        $aHeader = ['Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','Accept-Language: zh-cn'];
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);              // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);              // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $aHeader);
        curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/7.0.3(0x17000321) NetType/WIFI Language/zh_CN');
        curl_setopt($curl,CURLOPT_COOKIE,'ASP.NET_SessionId=1g1pxqyvxilagab02ytvvwg1; langid=1');
        $content = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        preg_match_all('#"ChannelName":"(.*?)",#is', $content,$match);
        preg_match_all('#"UrlList":"(.*?)",#is', $content,$match2);

        if(isset($match[1]) && $match[1])
        {
            $insertData = [];
            foreach ($match[1] as $channel_key=>$channel_name)
            {
                if(strpos($match2[1][$channel_key],'#'))
                {
                    $url_arr = explode('#',$match2[1][$channel_key]);
                    foreach ($url_arr as $url_key=>$channel_url)
                    {
                        $insertData[] = ['name'=>$channel_name.'#'.$url_key,'url'=>$channel_url];
                    }
                }
                else
                {
                    $insertData[] = ['name'=>$channel_name,'url'=>$match2[1][$channel_key]];
                }
            }
            db('channel')->insertAll($insertData);
        }
    }

}
