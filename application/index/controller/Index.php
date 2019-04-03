<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '';
    protected $noNeedRight = '*';


    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $video_id = input('video_id','9');
       /* $video_list = [
            'c4ca4238a0b923820dcc509a6f75849b' => ['id'=>1,'name'=>'翡翠台','url'=>'http://live.xiaochai.club/wintv/922bbe495a87497da1c0a461dd1bbcde.m3u8'],
            'c81e728d9d4c2f636f067f89cc14862c' => ['id'=>2,'name'=>'翡翠台2','url'=>'http://play.cnrmall.com/live/0e86b397a216405fbb0161f7beee7dea.m3u8'],
            'eccbc87e4b5ce2fe28308fd9f2a7baf3' => ['id'=>3,'name'=>'603台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/825e9e0bdc0744e0ac199264ac77439d.m3u8'],
            'a87ff679a2f3e71d9181a67b7542122c' => ['id'=>4,'name'=>'18台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/9a62cc27257b426bbafc3d985bb35f38.m3u8'],
            'e4da3b7fbbce2345d7772b0674a318d5' => ['id'=>5,'name'=>'J2台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/d129be9045624f4b9bc57be527868353.m3u8'],
            '1679091c5a880faf6fb5e6087eb1b2dc' => ['id'=>6,'name'=>'有线新闻台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/2d74c23b92db4d74ad191c0995da8d01.m3u8'],
            '8f14e45fceea167a5a36dedd4bea2543' => ['id'=>7,'name'=>'无线新闻台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/a77188f76791495499fad722ad5fc58c.m3u8'],
            'c9f0f895fb98ab9159f51fd0297e236d' => ['id'=>8,'name'=>'香港电影台','url'=>'http://play.cnrmall.com/live/cb04d8fe9d89467eb9dee5fbfc7a17b4.m3u8'],
            '45c48cce2e2d7fbdea1afc51c7c6ad26' => ['id'=>9,'name'=>'美亚电影台','url'=>'http://59.120.242.104:9000/live/live2.m3u8'],
            'd3d9446802a44259755d38e6d163e820' => ['id'=>10,'name'=>'珠江台','url'=>'http://play.cnrmall.com/live/fcdcbb4dc4424d498c7e35a8ecd635e3.m3u8'],
            '6512bd43d9caa6e02c990b0a82652dca' => ['id'=>11,'name'=>'TVB星河','url'=>'http://play.cnrmall.com/live/8f4a53f0aa92447b8e360de05b21802b.m3u8'],
            'c20ad4d76fe97759aa27a0c99bff6710' => ['id'=>12,'name'=>'澳门卫视','url'=>'rtmp://pushws.stream.moguv.com/live/MAC2eeeddsooss'],
            'c51ce410c124a10e0db5e4b97fc2af39' => ['id'=>13,'name'=>'无线财经','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/92c1f9a8835049d7bd56dc46a8b99f9a.m3u8'],
            'aab3238922bcc25a6f606eb525ffdc56' => ['id'=>14,'name'=>'CCTV5','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/9f3ae55e0ebb4ed9bed9c315ee632ba4.m3u8'],
            '9bf31c7ff062936a96d3c8bd1f8f2ff3' => ['id'=>15,'name'=>'凤凰资讯','url'=>'http://play.cnrmall.com/live/ce45fd3ded614efca5f68b98ed0b45bd.m3u8'],
            'c74d97b01eae257e44aa9d5bade97baf' => ['id'=>16,'name'=>'动物星球','url'=>'http://play.cnrmall.com/live/cbb8e1bd63aa4916ae6e01d0978550e1.m3u8'],
            '70efdf2ec9b086079795c442636b55fb' => ['id'=>17,'name'=>'三立台湾','url'=>'http://play.cnrmall.com/live/70a032691ccf4473abd4916c7f557e60.m3u8'],
            '6f4922f45568161a8cdf4ad2299f6d23' => ['id'=>18,'name'=>'中天新闻','url'=>'http://play.cnrmall.com/live/a3734807527e459b90875cf21ee91143.m3u8'],
            '1f0e3dad99908345f7439f8ffabdffc4' => ['id'=>19,'name'=>'迪士尼卡通','url'=>'http://live-dft-hls-yf.jstv.com/live/b3a6b3755bdd49879b65e21cb71ba400/online.m3u8'],
            '98f13708210194c475687be6106a3b84' => ['id'=>20,'name'=>'寰宇新闻','url'=>'http://bsyll.qingk.cn/live/8ac83d0396cc486ba5cd514c01de27ea/index.m3u8'],
            '3c59dc048e8850243be8079a5c74d079' => ['id'=>21,'name'=>'球彩台','url'=>'http://c01.live.aliyuncdn.sharkselection.com/live/b5ac542c2a914732bd666ef01cd3a40f.m3u8'],
        ];*/
        $video_list = db('channel')->select();

        $current_video = db('channel')->where(['id'=>$video_id])->find();

        if(!$current_video)
        {
            die('非法访问');
        }

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
