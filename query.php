<?php

if (!defined('IN_CRONLITE')) die();

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退单</font>';
	else
		return '<font color=blue>待处理</font>';
}

if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)>=17 && is_numeric($data))
	{
	   $sql=" A.tradeno='{$data}'"; 
	}else{
	   $sql=" A.input='{$data}'";
	   

	}
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
}
else $sql=" A.userid='{$cookiesid}'";

$q_status=isset($_GET['status'])?trim(daddslashes($_GET['status'])):"";
if(isset($q_status) && $q_status != ""){
	$qu_status = intval($q_status);
	$sql .= " AND A.status = '{$qu_status}'";
}
$limit = 10;
$start = $limit * ($page-1);

$total = $DB->getColumn("SELECT count(*) FROM `pre_orders` A WHERE{$sql} ");
$total_page = ceil($total/$limit);
$sql = "SELECT A.*,B.`name`,B.`shopimg` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
while($res = $rs->fetch()){
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'money'=>$res['money'],'name'=>$res['name'],'shopimg'=>$res['shopimg'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}
?><!DOCTYPE html><html lang="zh" style="font-size: 20px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no"><script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script><meta name="format-detection" content="telephone=no"><title><?php echo $conf['sitename'].($conf['title']==''?'':' - '.$conf['title'])  ?></title><meta name="keywords" content="<?php echo $conf['keywords'] ?>"><meta name="description" content="<?php echo $conf['description'] ?>"><link rel="shortcut icon" href="<?php echo $conf['default_ico_url'] ?>"><link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/><link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.css"><link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/style.css"><link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.diy.css"><link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/iconfont.css"><link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/><link href="<?php echo $cdnpublic ?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/><link rel="stylesheet" type="text/css" href="./assets/simple/css/oneui.css"><script src="<?php echo $cdnpublic ?>modernizr/2.8.3/modernizr.min.js"></script><?php echo str_replace('body','html',$background_css)?></head><style>
.fix-iphonex-bottom{padding-bottom:34px;}
body{width:100%;max-width:648px;margin:auto;}
#query{background: rgb(242, 242, 242);}
.fui-tab.fui-tab-primary a.active{color:#1492fb;border-color:#1492fb;}
.qt-header{display:none;height:8vh;line-height:8vh;}
.qt-header>input{height:5vh;width:100%;border:none;text-indent:2.5em;line-height:5vh;border-radius:0.5em;font-size:0.7rem;}
.qt-header>span{position:absolute;margin-left:0.6rem;font-size:0.7rem;}
.qt-card{box-shadow:0px0px6px#eee;border-radius:0.5em;}
.qt-card img{width:6em;max-width:100%;height:6em;border-radius:0.5em;box-shadow:3px3px16px#eee;}
.qt-btn{border-radius:0.5em;border:solid1px#eee;}
.btn{margin:0;height:2em;}
#message_count{display:inline;background:red;border-radius:50%;width:20px;height:20px;position:absolute;color:white;line-height:20px;}
.fui-icon-col:after{content:'';position:absolute;left:0;bottom:0;right:auto;top:auto;height:1px;width:100%;background-color:#d0d0d0;display:block;z-index:15;-webkit-transform-origin:50% 100%;transform-origin:50% 100%;}
.fui-icon-col{ border-bottom: 2px solid transparent;}
.cm_choose{font-weight: 400;}
.cm_redcolor{color:red !important;}
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
    width: 45px !important;
    height: 45px !important;
    background-image: url(https://yzf.qq.com/xv/web/static/img/chat-btn.png);
    background-size: 100% 100%;
    vertical-align: -3px;
}

</style><body><div id="body" style="width: 100%;max-width: 648px"><div class="fui-page-group statusbar" style="max-width: 650px;left: auto;overflow: hidden;"><div class="fui-searchbar bar block"><div class="searchbar center searchbar-active" style="padding-right:50px"><input type="hidden" id="page" value="<?php echo $page?>"><input type="hidden" id="q_status" value="<?php echo $q_status?>"><input type="button" class="btn btn-sm btn-danger searchbar-cancel searchbtn " value="搜索" onclick="OrderQuery();" style="color:white;margin-top:4px;right:10px;width:auto;font-size:15px;line-height: 1px;border-radius:5px"><div class="search-input" style="border: 0px;padding-left:0px;padding-right:0px;margin-right: 5px;"><i class="icon icon-search" style="margin-top:5px"></i><input type="search" id="query" onkeydown="if(event.keyCode==13){OrderQuery()}" class="search" value="<?php echo $data; ?>" name="title" placeholder="输入下单信息.." id="title" style="height:35px;width:95%;"></div></div></div><div id="tab" class="fui-tab fui-tab-danger"><a data-tab="tab" class="external <?php if(isset($q_status) && $q_status === ""){echo "active";} ?>" onclick="window.location.href='?store=query&data=<?php echo $data; ?>'">全部</a><a data-tab="tab0" class="external <?php if($q_status === '0'){echo "active";} ?>" onclick="window.location.href='?store=query&status=0&data=<?php echo $data; ?>'">待处理</a><a data-tab="tab1" class="external <?php if($q_status === '2'){echo "active";} ?>" onclick="window.location.href='?store=query&status=2&data=<?php echo $data; ?>'">处理中</a><a data-tab="tab2" class="external <?php if($q_status === '1'){echo "active";} ?>" onclick="window.location.href='?store=query&status=1&data=<?php echo $data; ?>'">已完成</a><a data-tab="tab3" class="external <?php if($q_status === '4'){echo "active";} ?>" onclick="window.location.href='?store=query&status=4&data=<?php echo $data; ?>'">已退单</a></div><?php if($record){ ?><div class="elevator_item" id="elevator_item" style="display:block;"><a class="feedback graHover" style="background-color: #FF3399;color:#fff;" onclick="$('.tzgg').show()" rel="nofollow">订<br/>单<br/>状<br/>态<br/>说<br/>明</a></div><div style="width: 100%;height: 100vh;position: fixed;top: 0px;left: 0px;opacity: 0.5;background-color: black;display: none;z-index: 10000"
             class="tzgg"></div><div class="tzgg" type="text/html" style="display: none"><div class="account-layer" style="z-index: 100000000;"><div class="account-main" style="padding:0.8rem;height: auto"><div class="account-title">订单状态说明</div><div class="account-verify"
                         style="  display: block;    max-height: 15rem;    overflow: auto;margin-top: -10px"><?php echo $conf['gg_search'] ?></div><div class="account-btn" style="display: block" onclick="$('.tzgg').hide()">确认</div><div class="account-close" onclick="$('.tzgg').hide()"><i class="icon icon-guanbi1"></i></div></div></div></div><div class="layui-card-body" style="padding: 1em;padding-bottom: 3em;overflow:hidden;overflow-y: auto;height: 80vh;"><div class="layui-tab-item layui-show" id="order_all"><?php foreach($record as $row){?><div class="layui-card qt-card"><div class="layui-card-header"><p style="width: 70%" class="layui-elip"><?php echo $row['name']?></p><span class="layui-layout-right layui-elip" style="width:30%;text-align: right;margin-right: 0.5em"><?php echo display_zt($row['status'])?></span></div><div class="layui-card-body"><div class="layui-row layui-col-space10"><div class="layui-col-xs4"><a href="?store=buy&tid=<?php echo $row['tid']?>"><img src="<?php echo $row['shopimg']?>" onerror="this.src='assets/stores/picture/error_img.png'"></a></div><div class="layui-col-xs8" style="font-size: 0.8em;color:black;font-family: '微软雅黑'">
                            
                            下单时间：<?php echo $row['addtime']?><br>
                            商品总价：<?php echo $row['money']?>元<br>
                            订单编号：<?php echo $row['id']?><br></div><div style="width: 100%;text-align: right" class="showorders"><button class="layui-btn qt-btn layui-btn-sm layui-btn-danger order-urge-btn" id="urgeBtn">催促加急</button><button class="layui-btn qt-btn layui-btn-sm layui-btn-primary xiangqing" data-id="<?php echo $row['id']?>" data-skey="<?php echo $row['skey']?>" onclick="showOrder(<?php echo $row['id']?>,'<?php echo $row['skey']?>')">
                             查看详情
                      </button><a class="layui-btn qt-btn layui-btn-sm layui-btn-primary xiangqing_1" href="./?store=kf">联系客服</a><?php if($row['djzt'] == 3){ ?><button class="layui-btn qt-btn layui-btn-sm layui-btn-danger" onclick="window.location.href='?store=faka&id=<?php echo $row['id']?>&skey=<?php echo $row['skey']?>'">
                             提取卡密
                      </button><?php } ?></div></div></div></div><?php }?></div><div class="layui-tab-item layui-show" id="order_all" style="margin-top: 5px;"><?php if($page>1){?><button class="layui-btn layui-btn-sm layui-btn-normal" onclick="LastPage()">
		上一页
	</button><?php }
if($total_page!=$page){?><button class="layui-btn layui-btn-sm layui-btn-normal pull-right" onclick="NextPage()">
		下一页
	</button><?php }?></div><?php }else{ ?><div class="fui-content navbar order-list"><div class="fui-content-inner"><div class="content-empty" style=""><img src="./assets/stores/picture/nolist.png" style="width: 6rem;margin-bottom: .5rem;"><br><?php if($_GET['data']){ ?><p style="color: #999;font-size: .75rem">没有查询到数据（注册平台后订单才会一直保存哦！没有注册下单的订单有时候会找不到或者只保留几天）<br>↓↓↓↓出现找不到订单情况点击下方按钮↓↓↓↓</p><?php }else{ ?><p style="color: #999;font-size: .75rem">您暂时没有该类型订单哦！（注册平台后订单才会一直保存哦！没有注册下单的订单有时候会找不到或者只保留几天）<br><font color="red" style="font-weight:700">↓↓↓↓出现找不到订单情况点击下方按钮↓↓↓↓</font></p><?php } ?><a href="javascript:void(0);" onclick="showPopup()" class="btn btn-sm btn-danger" style="border-radius: 100px; height: 1.9rem; line-height: 1.4rem; width: auto; font-size: 0.75rem;">查不到订单点我查看教程</a></div></div></div><?php } ?></div></div></div><div class="fui-navbar" style="max-width: 650px;z-index: 100; height: 2.8rem;"><a href="./" class="nav-item "><span class=" " style="width:35px;font-size:1.5rem;"><img src="./assets/img/index/index.svg" style="width:32px;"></span><span class="label" style="color:#999">首页</span></a><a href="./?store=query" class="nav-item "><span class=""  style="font-size:1.5rem"><img src="./assets/img/index/orders.svg" style="width:29px;"></span><span class="label"  style="color:#ff6c44">订单</span></a><a href="./?store=kf" class="nav-item "><span class="" style="font-size:1.5rem"><img src="./assets/img/index/kf.svg"  style="width:30px;"></span><span class="label" style="color:#999">客服</span></a><?php if($conf['appurl']){?><a href="<?php echo $conf['appurl']; ?>" class="nav-item "><span class="" style="font-size:1.5rem;margin-top:15px;"><img src="./assets/img/index/app.svg"  style="width:28px;"></span><span class="label" style="color:#999">APP下载</span></a><?php }else{?><?php }?><a href="./user/jm.php" class=" nav-item  "><span class="" style="font-size:1.5rem"><img src="./assets/img/index/jia.svg"  style="width:29px;"></span><span class=" label titlename" style="color:#999">加盟</span></a><a href="./user/" class="nav-item "><span class="" style="font-size:1.5rem"><img src="./assets/img/index/user.svg"  style="width:27px;"></span><span class="label" style="color:#999">会员中心</span></a></div></div></div><script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script><script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script><script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script><script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script><script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script><script src="<?php echo $cdnserver ?>assets/stores/js/query.js"></script><script>
    function showPopup() {
      layer.open({
        title: '订单号查询教程',
        content: '<b>很简单，只需要找到我们<font color="red">支付的记录</font><br>点进去对应支付记录,复制商户单号输入查询</b><br><img src="./assets/img/pay.png" width="100%">',
        btn: ['知道了'],
        yes: function(index, layero) {
          // 用户点击了按钮的回调函数
          layer.close(index); // 关闭弹窗
        }
      });
    }
  </script>
  
  <script>
// 获取页面上所有的按钮，假设它们都有'order-urge-btn'这个类
var buttons = document.querySelectorAll('.order-urge-btn');

// 为每个按钮添加点击事件监听器
buttons.forEach(function(button) {
    // 初始化按钮的点击次数
    button.clickCount = 0;

    // 为按钮添加点击事件监听器
    button.addEventListener('click', function() {
        // 增加点击次数
        this.clickCount++;

        // 根据点击次数显示不同的提示信息
        if (this.clickCount === 1) {
            // 第一次点击，显示“催单成功”
            layer.msg('催单成功', { icon: 1, time: 2000 });
        } else {
            // 之后的点击，显示“请不要重复催单”
            layer.msg('请不要重复催单', { icon: 0, time: 2000 });
        }
    });
});
</script></body></html>