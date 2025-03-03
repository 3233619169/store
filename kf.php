<?php

if (!defined('IN_CRONLITE')) die();
$qqlink = 'https://wpa.qq.com/msgrd?v=3&uin=' . $conf['kfqq'] . '&site=qq&menu=yes';
if ($is_fenzhan && !empty($conf['kfwx']) && file_exists(ROOT . '/assets/stores/img/qrcode/wxqrcode_' . $siterow['zid'] . '.png')) {
    $qrcodeimg = './assets/stores/img/qrcode/wxqrcode_' . $siterow['zid'] . '.png';
    $qrcodename = '微信';
} elseif (!empty($conf['kfwx']) && file_exists(ROOT . '/assets/stores/img/wxqrcode.png')) {
    $qrcodeimg = './assets/stores/img/wxqrcode.png';
    $qrcodename = '微信';
} else {
    $qrcodeimg = '//api.qrserver.com/v1/create-qr-code/?size=250x250&margin=10&data=' . urlencode($qqlink);
    $qrcodename = 'QQ';
}
if ($userrow['power'] == 1) {
    $jibie = "加盟商";
} elseif ($userrow['power'] == 2) {
    $jibie = "合伙人";
} else {
    $jibie = "用户";
}
?>

<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
 
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-param" content="_csrf">
    <title><?php echo $conf['sitename'] . ($conf['title'] == '' ? '' : ' - ' . $conf['title']) ?></title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <!-- Vendor styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/iconfont.css">
    <?php echo str_replace('body', 'html', $background_css) ?>
</head>

<style>
    ::-webkit-scrollbar {
        display: none;
        /* background-color:transparent; */
    }

    .custormer-page {
        padding-bottom: 2.7rem;
        background: #f3f3f3;
    }

    .custormer-page .fixed {

    }

    .custormer-page .box {
        width: 95%;
        /*height: 17rem;*/
        background: #fff;
        border-radius: 0.4rem;
        text-align: center;

    }

    .custormer-page .box p {
        line-height: 1rem;
       
        font-weight: bold;
        font-size: 0.6rem;
    }

    .custormer-page .box img {
        width: 13rem;
        height: 13rem;
    }

    .custormer-text {
        color: #969696;
        line-height: 2rem;
        font-size: 0.65rem;
        font-weight: bold;
    }

    .complaint {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        color: #3c3d3e;
        width: 95%;
        height: 2rem;
        line-height: 2rem;
        justify-content: center;
        background: #e8e8e8;
        border-radius: 6px;
        margin: .5rem auto;

    }

    .complaint img {
        width: 1.5rem;
        margin-right: 0.2rem;
    }

    .custormer-page .box .box-btn {
        width: 100%;


    }

    .custormer-page .box .box-btn .box-btn-btn {
        width: 100%;
        height: 2.0rem;
        line-height: 2.0rem;
        text-align: center;
        background: -webkit-linear-gradient(left, rgba(91, 193, 241, 1.0), rgba(52, 151, 234, 1.0));
        color: #fff;
        border-radius: 8px;


    }

    .custormer-page .box .box-top {
        width: 100%;

        display: flex;
        justify-content: space-between;
        margin: 10px 0 20px 0;
        font-size: 0.7rem;

    }

    .custormer-page .box .box-top .info {
        height: 5rem;
        width: 60%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-size: 0.7rem;
        border-right: 1px solid #f3f3f3;

    }

    .custormer-page .box .box-top img {
        width: 5rem;
        height: 5rem;
    }
    .main-contact .chat-btn {
    padding: 10px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-size: 12px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    text-decoration: none;
    padding: 5px 30px !important;
    border-radius: 20px !important
}
.main-contact .icon {
    display: block;
    width: 30px !important;
    height: 25px !important;
    background-image: url(https://yzf.qq.com/xv/web/static/img/chat-btn.png);
    background-size: 100% 100%;
    vertical-align: -3px;
}
.tab-pane {
font-size: 16px;
color: #333;
background-color: #fff;
border-radius: 5px;
padding: 10px;
}

.tab-header {
display: flex;
align-items: center;
margin-bottom: 10px;
}

.tab-number {
width: 40px;
height: 40px;
line-height: 40px;
font-size: 18px;
color: #fff;
background-color: #019858;
border-radius: 50%;
margin-right: 10px;
}

.tab-title {
font-size: 18px;
font-weight: bold;
}

.tab-content {
text-align: left;
color:#444;
}
</style>

<style>
  .download-section {
    text-align: center;
    margin-top: 50px;
  }
</style>
<body ontouchstart="" style="overflow: auto;height: 9rem; !important;max-width: 650px; margin:auto;">
<div id="body">
  
       
   <div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md8 layui-col-md-offset2">
      <div class="layui-card">
        <div class="layui-card-body">
          <h3 class="layui-text">亲爱的用户，为了更好的服务体验，建议您下载我们的官方APP联系客服。</h3><hr>
          <p class="layui-text">下载APP后，您将有机会参与我们的免单活动，享受更多优惠。</p><hr>
          <div class="download-section">
               <img src="./qy.png" width='100'/><br>
            <button class="layui-btn layui-btn-normal layui-icon layui-icon-download-circle" onclick="location.href='<?php echo htmlspecialchars($conf['appurl'], ENT_QUOTES, 'UTF-8'); ?>'">立即下载APP</button><br>
           <br><font color="red" style="font-weight:700">为了更好给您服务，联系客服需要下载app！</font>
          </div><hr>
          <p class="layui-text">如果您在下载或使用过程中遇到任何问题，请随时联系我们的客服人员。</p>
        </div>
      </div>
    </div>
  </div>
</div>

     <div class="fui-navbar" style="max-width: 650px;z-index: 100; height: 2.8rem;">
            <a href="./" class="nav-item "> <span class=" " style="width:35px;font-size:1.5rem;"><img src="./assets/img/index/index.svg" style="width:32px;"></span> <span class="label" >首页</span>
            </a>
            <a href="./?store=query" class="nav-item "> <span class=""  style="font-size:1.5rem"><img src="./assets/img/index/order.svg" style="width:29px;"></span> <span class="label" >订单</span> </a>
		
            <a href="./?store=kf" class="nav-item "> <span class="" style="font-size:1.5rem"><img src="./assets/img/index/kfs.svg"  style="width:30px;"></span> <span class="label" style="color:#ff6c44">客服</span>
            </a>
                	<?php if($conf['appurl']){?>
             <a href="<?php echo $conf['appurl']; ?>" class="nav-item "> <span class="" style="font-size:1.5rem;margin-top:15px;"><img src="./assets/img/index/app.svg"  style="width:28px;"></span> <span class="label">APP下载</span>
            </a>	<?php }else{?>
			<?php }?>
            <a href="./user/jm.php" class=" nav-item  "> <span class="" style="font-size:1.5rem"><img src="./assets/img/index/jia.svg"  style="width:29px;"></span> <span class=" label titlename">加盟</span> </a>
            <a href="./user/" class="nav-item "> <span class="" style="font-size:1.5rem"><img src="./assets/img/index/user.svg"  style="width:27px;"></span> <span class="label">会员中心</span> </a>
        </div>


</div>

<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
layui.use(function(){
  var rate = layui.rate;
  // 批量渲染
  rate.render({
    elem: '.class-rate-demo-theme',
    // theme: '#FF8000' // 自定义主题色
  });
});
</script>
<script>
    var clipboard = new Clipboard('.wx_hao');
    clipboard.on('success', function (e) {
        layer.msg('复制成功');
    });
    clipboard.on('error', function (e) {
        layer.msg('复制失败');
    });

    function openwx() {

        locatUrl = "weixin://";

        if (/ipad|iphone|mac/i.test(navigator.userAgent)) {

            var ifr = document.createElement("iframe");

            ifr.src = locatUrl;

            ifr.style.display = "none";

            document.body.appendChild(ifr);

        } else {

            window.location.href = locatUrl;
        }

    }
   
</script>
<script>
  // 当页面加载完成后执行
  layui.use('layer', function(){
    var layer = layui.layer;

    // 监听下载按钮点击事件
    document.getElementById('downloadAppBtn').addEventListener('click', function() {
      layer.msg('正在跳转至下载页面...', {icon: 16, time: 2000});
      // 这里可以添加跳转到下载页面的逻辑
  window.location.href = '<?php echo $conf['appurl']; ?>';
    });
  });
</script>

</body>
</html>