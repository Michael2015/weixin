<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"/usr/share/nginx/html/weixin/public/../application/index/view/index/index.html";i:1538893163;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="天天微赚">
    <meta name="author" content="">
    <title>快乐阅读，轻松赚钱</title>
    <METAHTTP-EQUIV="Pragma" CONTENT="no-cache">
    <METAHTTP-EQUIV="Cache-Control" CONTENT="no-cache">
    <METAHTTP-EQUIV="Expires" CONTENT="0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<style type="text/css">
.container-fluid .row
{
    border-bottom: 1px solid #ddd;
    background-color:#24292e;
}
.list-group-item
{
    border-radius: 0;
    border: 0;
    border-bottom: 0;
    padding: 4px 15px;
    float: right;
    background-color: transparent;
    color: #eee;
}
.list-group
{
    margin-bottom: 0;
    list-style-type: none;
}
.list-group-item:last-child 
{
    border-radius: 0px;
}
.page-header
{
    border:0;
    margin: 0;
}
.page-header h1
{
    margin-top: 0px;
    margin-bottom: -2px;
    text-align: center;
}
.page-header h1>small
{
   color: #eee;
}
.list-group-item>.badge
{
    margin-left: 5px;
    background: yellow;
    color: blue;
}
.jumbotron
{
    margin-bottom: 0;
}
.jumbotron .container
{
    text-align: center;
    margin: 0;
    padding: 50px 0;
}
.jumbotron2
{
    margin: 0 auto;
    background: transparent;
    padding: 0;
    margin-bottom: 30px;
}
.jumbotron2 .container
{
    padding: 0;
}
.jumbotron2 img
{
    width: 50%;
    margin-left: 25%;
}
.alert-dismissable, .alert-dismissible
{
    text-align: left;
    padding-right:15px;
}
footer
{
    position: fixed;
    bottom: 0;
    width: 100%;
}
footer .btn
{
    border-radius: 0;
}
#alert-status-1>span 
{
    font-size: 16px;
    font-weight: 700;
    color: red;
}
</style>
<body>
    <!-- Stack the columns on mobile by making one full-width and the other half-width -->
    <div class="container-fluid">
        <div class="row">
          <div class="col-xs-4 col-sm-4">
            <div class="page-header">
              <h1><small>会员号 <?php echo $user_id; ?></small></h1>
          </div>
      </div>
      <div class="col-xs-8 col-sm-4">
        <ul class="list-group">
          <li class="list-group-item">
            <span class="badge"><?php echo $all_article_count; ?>/次</span>
            今日阅读量
        </li>
        <li class="list-group-item">
            <span class="badge"><?php echo $valid_article_count; ?>/次</span>
            有效阅读量
        </li>
        <li class="list-group-item">
            <span class="badge"><?php echo $score; ?>/元</span>
            账户总余额
        </li>
    </ul>
</div>
</div>
</div>

<div class="jumbotron">
    <div class="container">
        <?php if(( $alert_status === 0)): ?> 
        <button type="button" class="btn btn-primary" onclick="return getUrl();">马 上 阅 读</button>
        <?php elseif($alert_status == 1): ?>
        <div id="alert-status-1"><span>5</span>秒后自动进入下一篇文章</div>
        <?php else: ?> 
        <button type="button" class="btn btn-primary" onclick="return getUrl();">马 上 阅 读</button>
        <div class="alert alert-warning text-center" id="alert-status-2" role="alert" style="position: fixed;top: 0;width: 100%;">
            阅读越多，赚得越多
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="jumbotron jumbotron2">
    <div class="container">
       <div class="list-group">
          <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
              <h4><strong>赚钱技巧</strong><span class="glyphicon glyphicon-star-empty" style="float: right;"></span><span style="float: right;" onclick="window.location.href='https://mp.weixin.qq.com/s/oUYlhpMMznaJs4gAzLwHUA';">收藏网址</span><span class="glyphicon glyphicon-star-empty" style="float: right;"></h4>
                  <h6>1.新用户首次注册奖励0.5元</h6>
                  <h6>2.每一次阅读单价0.03元，满1元即可提现；</h6>
                  <h6>3.阅读时间间隔在5秒及以上，否则视为无效阅读；</h6>
                  <h6>4.进入文章后，可以按微信底部返回按钮返回；</h6>
                  <h6>5.任何问题，欢迎咨询微信客服shan47636；</h6>
                  <img src="http://wx3.sinaimg.cn/mw690/0060lm7Tly1fvzmiw6a50j3076076gm2.jpg">
              </div>
          </div>
      </div>
  </div>
  <footer>
    <button type="button" class="btn btn-primary btn-lg btn-block"> 我 要 提 现 </button>
</footer>
<div class="alert alert-warning text-center" id="alert-status-4" role="alert" style="display:none;position: fixed;top: 0;width: 100%;">
    后台暂无文章，请稍后刷新
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<script type="text/javascript">
    $().ready(function(){

        if( $('#alert-status-2').length > 0)
        {
            $('#alert-status-2').delay(2000).hide('slow');
        }

        if( $('#alert-status-1').length > 0)
        {
            sec = 5;
            var timer = setInterval(function(){

               $('#alert-status-1>span').html(sec);
               sec = sec-1;
               if( sec === -1)
               {
                window.clearInterval(timer); 
                getUrl();
            }

        },1000);
        }

    });

    function getUrl()
    {
        $.ajax({ url: "<?php echo url('api/index/log',['token'=>$token]); ?>", context: document.body, success: function(msg){
            if(msg.code === 1)
            {
                window.location.href = msg.data.article_url;
            }
            else
            {
                $('#alert-status-4').delay(1000).show('fast');
                $('#alert-status-4').delay(2000).hide('slow');
            }
        }});
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
    var pagNum=performance.navigation.type;
     if(pagNum==2){
         document.location.reload();
    }
 });
</script>
</body>
</html>