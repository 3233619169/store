
<?php

if (!defined('IN_CRONLITE')) die();
$tid=intval($_GET['tid']);
$tool=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
if(!$tool)sysmsg('没有找到商品熬！');
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = ''; 
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding ); 
            if (strlen ( $str ) > 1) { // 多字节字符 
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) ); 
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) ); 
            } 
        } 
    } 
    return $return; 
}

$level = '<font class="layui-badge layui-bg-green"">普通用户售价</font>';
if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	if($userrow['power']==2){
		$level = '<font class="layui-badge">合伙人专属密价</font>';
	}elseif($userrow['power']==1){
		$level = '<font class="layui-badge layui-bg-orange" color="red">加盟商售价</font>';
	}
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}

if(isset($price_obj)){
	$price_obj->setToolInfo($tool['tid'],$tool);
	if($price_obj->getToolDel($tool['tid'])==1)sysmsg('商品已下架');
	$price=$price_obj->getToolPrice($tool['tid']);
	$islogin3=$islogin2;
	unset($islogin2);
	$price_pt=$price_obj->getToolPrice($tool['tid']);
	$price_1=$price_obj->getToolCost($tool['tid']);
	$price_2=$price_obj->getToolCost2($tool['tid']);
	$islogin2=$islogin3;
}else{
   $price=$tool['price'];
   $price_pt=$tool['price'];
   $price_1=$tool['cost1'];
   $price_2=$tool['cost2'];
}


if($tool['is_curl']==4){
	$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$tool['tid']}' and orderid=0");
	$fakainput = getFakaInput();
	$tool['input']=$fakainput;
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}elseif($tool['stock']!==null){
	$count = $tool['stock'];
	$isfaka = 1;
	$stock = '<span class="stock" style="">剩余:<span class="quota">'.$count.'</span>份</span>';
}else{
	$isfaka = 0;
}

if($tool['prices']){
	$arr = explode(',',$tool['prices']);
	if($arr[0]){
		$arr = explode('|',$tool['prices']);
		$view_mall = '<font color="#bdbdbd" size="2">购买'.$arr[0].'个以上按批发价￥'.($price-$arr[1]).'计算</font>';
	}
}
?>

<!DOCTYPE html>
<html lang="zh" style="font-size: 20px;">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
    <script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
    <meta name="format-detection" content="telephone=no">
    <title>商品详情</title>
    <meta name="keywords" content="<?php echo $conf['keywords'] ?>">
    <meta name="description" content="<?php echo $conf['description'] ?>">
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic ?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/foxui.diy.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/style(1).css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/detail.css">
    <link href="<?php echo $cdnpublic ?>animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css"/>
    
        <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/mall.css">
                <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/iconfont3.css">
                <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/hsycmsAlert.css">
                <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/shop.css">
                <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/shop2.css">
                <link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/store/css/base.css?a=222">

	<?php echo str_replace('body','html',$background_css)?>

</head>

<style>
    select {
        /*Chrome和Firefox里面的边框是不一样的，所以复写了一下*/
        border: solid 1px #000;
        /*很关键：将默认的select选择框样式清除*/
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;
        /*将背景改为红色*/
        background: none;
        /*加padding防止文字覆盖*/
        padding-right: 14px;
    }

    /*清除ie的默认选择框样式清除，隐藏下拉箭头*/
    select::-ms-expand {
        display: none;
    }

	.onclick{cursor: pointer;touch-action: manipulation;}

    .fui-page,
    .fui-page-group {
        -webkit-overflow-scrolling: auto;
    }

    .fui-cell-group .fui-cell .fui-input {
        display: inline-block;
        width: 70%;
        height: 32px;
        line-height: 1.5;
        margin: 0 auto;
        padding: 2px 7px;
        font-size: 12px;
        border: 1px solid #dcdee2;
        border-radius: 4px;
        color: #515a6e;
        background-color: #fff;
        background-image: none;
        cursor: text;
        transition: border .2s ease-in-out, background .2s ease-in-out, box-shadow .2s ease-in-out;
    }

    .btnee {
        width: 20%;
        float: right;
        margin-top: -2.8em;
    }

	.btnee_left {
        width: 20%;
        float: lef;
        margin-top: -2.8em;
    }

    .fui-cell-group .fui-cell .fui-cell-label1 {
        padding: 0 0.4rem;
        line-height: 0.7rem;
    }

    .fui-cell-group .fui-cell.must .fui-cell-label:after {
        top: 40%;
    }

    /*支付方式*/
    .payment-method {
        position: fixed;
        bottom: 0;
        background: white;
        width: 100%;
        padding: 0.75rem 0.7rem;
        z-index: 1000 !important;
            border-radius: 10px 10px 0px 0px;
    }

    .payment-method .title {
        font-size: 0.75rem;
        text-align: center;
        color: #333333;
        line-height: 0.75rem;
        margin-bottom: 1rem;
    }

    .payment-method .title span {
        height: 0.75rem;
        position: absolute;
        right: 0.3rem;
        width: 2rem;
    }

    .payment-method .title .close:before {
        font-family: 'iconfont';
        content: '\e654';
        display: inline-block;
        transform: scale(1.5);
        color: #000;

    }

    .payment-method .payment {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        padding: 0.7rem 0;
    }

    .payment-method .payment .icon-weixin1 {
        color: #5ee467;
        font-size: 1.3rem;
        margin-right: 0.4rem;
    }

    .payment-method .payment .icon-zhifubao1 {
        color: #0b9ff5;
        font-size: 1.5rem;
        margin-right: 0.4rem;
    }

    .icon-zhifubao1::before {
        margin-left: 1px;
    }

    .payment-method .payment .paychoose {
        font-size: 1.2rem;
    }

    .payment-method .payment .icon-xuanzhong4 {
        color: #2e8cf0;
    }

    .payment-method .payment .icon-option_off {
        color: #ddd;
    }

    .payment-method .payment .paytext {
        flex: 1;
        font-size: 0.8rem;
        color: #333;
    }

    .payment-method button {
        margin-top: 0.8rem;
        background: #2e8cf0;
        color: white;
        letter-spacing: 1px;
        font-size: 0.7rem;
        border: none;
        outline: none;
        width: 17.25rem;
        height: 1.75rem;
        border-radius: 1.75rem;
    }

    .input_select {
        flex: 1;
        height: 1.5rem;
        border-radius: 2px;
        border: none;
        border-bottom: 1px solid #eee;
        outline: none;
        margin-left: 0.4rem;
    }
</style>
<style>
    html {
        font-size: 14px;
        color: #000;
        font-family: '微软雅黑'
    }

    a, a:hover {
        text-decoration: none;
    }

    pre {
        font-family: '微软雅黑'
    }

    .box {
        padding: 20px;
        background-color: #fff;
        margin: 50px 100px;
        border-radius: 5px;
    }

    .box a {
        padding-right: 15px;
    }

    #about_hide {
        display: none
    }

    .layer_text {
        background-color: #fff;
        padding: 20px;
    }

    .layer_text p {
        margin-bottom: 10px;
        text-indent: 2em;
        line-height: 23px;
    }

    .button {
        display: inline-block;
        *display: inline;
        *zoom: 1;
        line-height: 30px;
        padding: 0 20px;
        background-color: #56B4DC;
        color: #fff;
        font-size: 14px;
        border-radius: 3px;
        cursor: pointer;
        font-weight: normal;
    }

    .photos-demo img {
        width: 200px;
    }

    .layui-layer-content {
        margin: auto;
    }

    * {
        -webkit-overflow-scrolling: touch;
    }

    .pro_content {
        background-image: linear-gradient(130deg, #00F5B2, #1FC3FF, #00dbde);
        height: 120px;
        position: relative;
        margin-bottom: 4rem;
        background-size: 300%;
        animation: bganimation 10s infinite;
    }

    @keyframes bganimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    #picture {
        padding-top: 1em;
    }

    #picture div {
        text-align: center;
    }

    #picture img {
        width: auto;
        max-height: 38vh;
        margin: auto;
    }
	.hd_intro img{ max-width: 100%; }
	.aui-product-title{
	    padding:.8rem .1rem .8rem .8rem !important;
	}
	
</style>
<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 650px;margin: auto;">
<section class="aui-flexView">
    <header class="aui-navBar aui-navBar-fixed"> <a href="/?r=xt0vx9gr" class="aui-navBar-item"> <i class="icon icon-return"></i> </a> <div class="aui-center"> <span class="aui-center-title"></span> </div>  </header>
    <div id="scrollBg" style="display: none;"></div>
    <section class="aui-scrollView">
        <div class="img_gallery" id="commodity"> 
            <div class="main_img descbox">
                 <ul> <li> <img alt="<?php echo $tool['name'];?>" class="ticon" src="<?php echo $tool['shopimg'];?>"> </li> </ul>            </div> 
        </div>
      
        
         <div style="position:relative;display:none;" class="dia"> 
            <img style="width:100%;margin:0 auto;" src="" class="dia_bg">
            <div style="position: absolute;top: 39%;left: 8%;font-size: 15px;color: #a19d9d;width:14%;text-align:center;" class="dia_l"></div>
            <div style="position: absolute;top: 23%;left: 35%;font-size: 21px;color: #fcf8fc;width: 30%;text-align: center;" class="dia_n"></div>
            <div style="position: absolute;top: 40%;left: 75%;font-size: 15px;color: #a19d9d;width: 20%;text-align: center;" class="dia_f"></div>
            <div style="position: absolute;top: 75%;left: 38%;font-size: 3.6vh;color: #040404;width: 7%;text-align: center;" class="dia_d"></div>
            <div style="position: absolute;top: 0;left: 0;width: 100%;height:100%;"></div>
        </div>
        <div class="aui-product-title">
        <h2><?php echo $tool['name']?></h2>
        </div>
        <div class="aui-product-head aui-footer-flex" style="padding-bottom:0.6rem;margin-top:-1.2rem;"> <h2> <i>￥</i></h2><h2 class="pay_price price-format"><div class="pay_price_int"><?php echo $price?></h2><h2 class="payunit"></h2></div>
        <div class="aui-product-head aui-footer-flex" style="border-bottom: 1px solid #e9e9e9;padding-top:0;">
             <div class="t-table"><em>口碑商品</em></div>
             <div class="t-table"><em>品质服务</em></div>
             <div class="t-table"><em>优质售后</em></div>
             <div class="t-table"><em>金牌卖家</em></div>
        </div>
        <div class="list_tag" style="margin-left:0;position: relative;margin-top: 10px;width: 100%;">
            <div class="price" style="width:100%;margin-left:0;display:flex;  flex-direction:row;justify-content: space-between;align-items: center;padding:0 10px;font-size:13px">
                <p class="stock" id="bb" style="text-align:center;flex:1;line-height:19px;width:auto;">
                    <i class="iconfont-m- icon-m-xuanzekapian1" style="font-size:13px;"></i>
                    库存:<span class="quota pay_kucun" id="pay_kucun">充足</span>
                </p>
                <p class="stock" id="cc" style="text-align:center;flex:1;line-height:19px;width:auto;min-width: 92px;">
                    <i class="iconfont-m- icon-m-yejijiangli" style="font-size:13px;"></i>
                    销量:<span class="quota pay_xl" id="pay_xl">9999+</span></p>
                <p class="stock" id="dd" style="text-align:center;flex:1;line-height:19px;width:auto;min-width: 100px;">
                    <i class="iconfont-m- icon-m-alipay-zhifuchenggong" style="font-size:13px;"></i>
                   评价:<span class="quota pay_tx" id="pay_tx" style="color: #ff8000;">★★★★★</span></p>            </div>
        </div>
        <div class="aui-product-title aui-product-title-text"> <h2></h2> </div>
        


<div class="divHeight coupon-style"></div>
<div class="aui-evaluate-box coupon-style">
<style>
.geetest_canvas_img {
    pointer-events: none;
}
.geetest_copyright{
    pointer-events: none;
}
.geetest_feedback{
    pointer-events: none;
}

    .coupon-cont-box{
        background:#fff6e7;display: flex;height: 2.5rem;border-radius: 8px;
    }
    .coupon-cont-box .icon1{
        font-size: 25px !important;color:#fc4f02;align-self: center;margin-left: 10px;height: 21px;width: auto ;
    }
    .coupon-cont-box .title-text{
        color: #6f6b62;align-self: center;margin-left: 10px;font-size: .65rem;font-weight: 400;
    }
    .coupon-cont-box .title-aim{
        color: #665543;align-self: center;margin-left: 10px;font-size: .65rem;font-weight: 100;flex:1;
    }
    .coupon-cont-box .icon2{
        color: #665543;align-self: center;font-size: 1rem;width: 1.5rem;
    }
    
    .coupon-list {
        display:none;
    }
    .coupon-list .{}
    .coupon-list .closebg-coupon-all{position: fixed;background: rgba(0, 0, 0, 0.6);width: 100%;height: 100%;top: 0;left: 0;z-index: 9999999;}
    .coupon-list .main-body{background: #fff;top: 20%;bottom: 0;width: 100%;left: 50%;position: fixed;z-index: 9999999;max-width: 650px;margin: 0 auto;transform: translate3d(-50% , 0 , 1px);}
    .coupon-list .main-body .header{display: flex;height:10%;width:100%;}
    .coupon-list .main-body .header-left{width: 40px;}
    .coupon-list .main-body .header-center{flex: 1;text-align: center;font-size: .965rem;color: red;line-height: 2rem;align-self: center;}
    .coupon-list .main-body .header-right{font-size: 29px !important;width: 40px;align-self: center;}
    
    .coupon-list-body {height:90%;width:100%;overflow-y:auto;}
    .coupon-list-body .centerbox{text-align: center;}
    .coupon-list-body .reducebox{display: inline-block;border: 1px solid #d46b6b;text-align: center;padding: 0 10px;border-radius: 7px;    background: #f5e5e6;}
    .coupon-list-body .reducebox2{vertical-align:bottom;color: red;padding-top: 5px;}
    .coupon-list-body .coupon-minprice-sign{font-size: 13px;}
    .coupon-list-body .coupon-minprice{font-size: 23px;}
    .coupon-list-body .coupon-minprice-unit{font-size: 13px;}
    
.layui-form-label{
    font-weight: 550;
    color: #000;
}
    .layui-input {
font-weight: 550;
        
    }
</style>

</div>    </div>
        
                <div class="aui-evaluate-box">
            <div class="aui-footer-flex aui-evaluate-item">
                <div class="aui-footer-flex1 aui-evaluate-hd">商品评价<font style="font-size:15px;color:#999">（评价请联系客服，好坏都会采纳）</font></div>
                <!--div class="aui-evaluate-fr review">更多评价</div-->
            </div>
            <div class="aui-category-box-list ">
                
                <div style="height:1.65rem;overflow:hidden;">
                 <div style="white-space: nowrap;overflow-y: auto;height: 100px;" id="review-tag">
	<a  class="aui-category-box-item review">全部<em>99+</em>
	</a>
		<a  class="aui-category-box-item review">真实/靠谱<em>99+</em>
	</a>
	<a  class="aui-category-box-item review">质量好/速度快<em>99+</em>
	</a>
	<a  class="aui-category-box-item review">服务态度好<em>99+</em>
	</a>

	<a  class="aui-category-box-item review">其它<em>99+</em>
	</a>
</div>
                </div>
             
             
             	<!-- begin:User Testimonies -->
             	<style>
             	    @media screen and (max-width: 768px) {
  #marquee2 {
     overflow: scroll;
     white-space: nowrap;
  }
}
@media screen and (min-width: 769px) {
  #marquee2 {
     overflow: hidden;
     white-space: nowrap;
  }
}
@keyframes slide-up {
    from { transform: translateY(100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes slide-down {
    from { transform: translateY(0); opacity: 1; }
    to { transform: translateY(100%); opacity: 0; }
}

#paymentmethod {
    position: fixed;
    bottom: 0;
    width: 100%;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
    transition: all 0.5s ease;
    display: none;
}
             	</style>
		<div class="user_Testimonies">
			<div class="w1 user_box">
				<div  class="w1 user_box"  id="marquee2">
					<ul class="user_ul">
					       <?php 
					       function hideMiddleCharacters($string) {
    $length = strlen($string);
    if ($length <= 5) {  return $string; }  $visiblePart = substr($string, 0, 3); $hiddenPart = str_repeat('*', $length - 5);$endPart = substr($string, -2);
    return $visiblePart . $hiddenPart . $endPart;}
                 $rs = $DB->query("SELECT * FROM pre_comment ORDER BY RAND()");	while($res = $rs->fetch()){?>
						<li style="margin:10px 10px;">
							<div class="user_header">
								<img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $res['qq']?>&spec=100" />
								<div class="userhead_txt">
									<p class="p_text"><?php echo hideMiddleCharacters($res['qq'])?></p>
									<!--<p class="p_text2"><?php echo $res['addtime']?></p>-->
								</div>
							</div>
							<span>“<?php echo $res['contenr']?>”</span>
							<div class="user_star">
								<img src="/assets/store/images/xx.png" >
								<p><?php echo $res['types']?></p>
								<p id="likebox<?php echo $res['id']?>" onclick="alike(<?php echo $res['id']?>)" style="    background: #ff8000;;  color: #fff;align-items: center; border-radius: 15px; display: flex;"><img id="listk<?php echo $res['id']?>" src="/assets/store/images/like1.png" style="width:16px;"><sapn id="likenum<?php echo $res['id']?>"><?php echo $res['alike']?></sapn></p>
							</div>
						</li>
					    <?php }?>
					</ul>
				</div>
				
			</div>

		</div>
		<!-- end:User Testimonies -->
              
           
    
                       </div>
                    
                    
                    
                </div>
            </div>
 
        </div>
        <div class="divHeight"></div>
        
                  
        <div class="aui-introduce"> 
            <div class="aui-tab" data-ydui-tab=""> 
                <ul class="tab-nav"> 
                    <li id="spxq" class="tab-nav-item tab-active"> 
                    <a href="javascript:">商品详情</a> </li> <li id="wtdy" class="tab-nav-item"> 
                    <a href="javascript:">问题答疑</a> </li> 
                </ul> 
                <div class="tab-panel"> 
                    <div id="spxq_1" class="tab-panel-item tab-active"> 
                        <div class="tab-item"> 
                            <div class="aui-page-img">  <?php echo $tool['desc']?></div> 
                        </div> 
                    </div> 
                    <div id="wtdy_1" class="tab-panel-item">
                        <div class="tab-item"> 
                            <div class="tab-item-faq"> <h2> <span>商品FAQ</span> </h2> </div> 
                                <div class="tab-item-faq-text"> <ul> <li> <h4>一、关于发货</h4> <p>&nbsp;&nbsp;&nbsp;若您填写的下单信息均准确无误，订单通常将在10分钟左右开始处理，最迟24小时内处理完毕，若超过24小时未到请及时联系客服处理。</p> </li> 
			<li> <h4>二、是否安全</h4> <p>&nbsp;&nbsp;&nbsp;平台运营多年资质深厚，资金100%安全，欢迎加盟推广，商品全年稳定，欢迎代理跳槽加盟品鉴~！</p> </li> 
			<li> <h4>三、售后保障</h4> <p>&nbsp;&nbsp;&nbsp;本站多名售前、售后客服24小时轮班为您服务，无论当前是几点都有人工客服接待您，为您提供最优质、贴心的售后服务，欢迎体验！</p> </li>
			</ul></div> 
                            </div> 
                        </div>                         
                    </div>
                </div> 
            </div>
        



    <footer id="oneshop" class="aui-footer-button  aui-footer-fixed" style="display: flex;"> 
        <div class="aui-footer-wrap" style="display: flex; align-items: center; justify-content: center; flex-direction: column; margin: 0 0px;">
    <a href="./?r=0t0vx9gr" style="text-align: center;">
        <i class="icon icon-return" style="font-size:18px;"></i> 
        <span style="margin-top:3px;">返回</span>
    </a>
</div>
<div class="aui-footer-wrap" style="display: flex; align-items: center; justify-content: center; flex-direction: column; margin: 0 0px;">
    <a href="./?store=kf" style="text-align: center;">
        <i class="icon-service" style="font-size:18px;"></i> 
        <span>客服</span>
    </a>
</div>
       <div class="aui-footer-group aui-footer-flex1">
    <div class="aui-footer-flex">
        <div class="aui-btn show-with-animation" onclick="showPaymentMethod();" style="
            width: 95%;height: 40px;padding: 10px 20px;color: #000000;border-radius: 25px;text-align: center;aligh：right;font-size: 14px;line-height: 20px;background-color: #fb9c0c;cursor: pointer;margin-top: .35rem;">
            <span>立即购买</span>
        </div>
    </div>
</div> </footer>
</section>


<div id="form1">

<input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $price;?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
<input type="hidden" id="leftcount" value="<?php echo $isfaka?$count:100?>">

    <div id="paymentmethod" class="common-mask" style="display:block;max-width: 650px">
        <div id="payment" class="payment-method" style="position: absolute;">
            <div class="title" id="gid" data-tid="<?php echo $_GET['gid'] ?>" style="color: black;font-size: .9em;float:left;">
                下单信息确认
                <span class="close" onclick="hidePaymentMethod();"></span>
            </div>
            <hr>
            <div style="overflow:hidden;overflow-y: auto">
                
                <div class="fui-goods-group" style="margin-bottom:.5rem">
                    <a class="fui-goods-item" style="border: 1px solid #e2e2e2;border-top-radius:5px;padding:.4rem;">
                        <div class="image">
                            <img id="payShopimg" src="<?php echo $tool['shopimg']?>" onerror="this.src='./assets/img/Product/noimg.png'" alt="646" height="100%">
                        </div>
                        <div class="detail" style="height:unset;">
                            <div class="name" style="color: #000;font-size:.5rem;height:unset;" id="payGoodsName"><?php echo $tool['name']?></div>
                             <div style="display: flex; align-items: center;margin-top:.5rem">
    <span style="position: relative; flex: 1.2;">
        <p class="layui-icon layui-icon-auz" style="font-size: 13px; line-height: 15px; font-weight: 300;">售后保障</p>
    </span>
    <span style="position: relative; flex: 1.2;">
        <p class="layui-icon layui-icon-auz" style="font-size: 13px; line-height: 15px; font-weight: 300;">官方认证</p>
    </span>
    <span style="position: relative; flex: 1.2;">
        <p class="layui-icon layui-icon-auz" style="font-size: 13px; line-height: 15px; font-weight: 300;">安全可靠</p>
    </span>
</div>
                            <div class="price" style="height:2.6rem;">
                                <span class="text" style="color: #ff5555;">
                                    <p class="minprice" style="font-size:.6rem" id="pay_price"><input type="text" id="need" disabled style="border:0;font-size:15px;" class="layui-input" value="<?php echo $price?> 元"></p>
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="fui-cell-group" <?php echo $tool['multi']==0?'style="display: none"':null;?> style="padding: 10px 0px; border-width: 0px 1px 1px; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; border-right-color: rgb(226, 226, 226); border-bottom-color: rgb(226, 226, 226); border-left-color: rgb(226, 226, 226); border-image: initial; border-top-style: initial; border-top-color: initial; margin-top: 0px;" id="multiDisplay">
                        <div class="fui-cell" style="padding: 0px 0.6rem;">
                            <div class="fui-cell-label">选择数量</div>
                            <div class="fui-cell-info"></div>
                            <div class="fui-cell-mask">
                                <div class="fui-number">
                                    <div class="minus" id="num_min">-</div>
                                     <input id="num" name="num" style="margin:0;border:0;width:50px;    text-align: center;" type="number" value="1" placeholder="请输入购买数量" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>>
                                    <div class="plus" id="num_add">+</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
              
                <div id="inputsname" style="margin-top:20px;"></div>
                
             
                <div id="matching_msg"
                     style="display:none;box-shadow: -3px 3px 16px #eee;margin-bottom: 0em;padding: 1em;text-align: center"></div>
            </div>
             <div class="form-group" style="    margin-bottom: 15px;text-align:center;">
                 
                <button type="button"
                        style="margin:auto;   line-height: 1.3rem;margin-top: 0.4rem;   background:#000000;    letter-spacing: 1px;    font-size: 0.7rem;    border: none;    outline: none;        height: 10.667vw;font-weight:700;width:95%;display: inline-block; justify-content: center; align-items: center;"
                        id="submit_buy" class="btn btn-danger btn-block" >
                    立即支付
                </button>
            </div>
                    
        </div>
    </div>
</div>
 
 


<script> var hashsalt=<?php echo $addsalt_js?>;</script>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="<?php echo $cdnpublic?>layui/2.5.7/layui.all.js"></script>
<script src="assets/store/js/marquee.js?a=2341"></script>

    <script>
     
function showPaymentMethod() {
    $('#paymentmethod').css({
        'display': 'block',
        'animation': 'slide-up 0.5s ease'
    });
}

function hidePaymentMethod() {
    $('#paymentmethod').css({
        'animation': 'slide-down 0.5s ease'
    });
    
    setTimeout(function() {
        $('#paymentmethod').css('display', 'none');
    }, 500); // 动画持续时间为 0.5 秒
}
document.addEventListener('DOMContentLoaded', function() {
    var paymentMethod = document.getElementById('paymentmethod');
    // 监听#paymentmethod的点击事件
    paymentMethod.addEventListener('click', function(event) {
        // 检查点击的是否是#paymentmethod元素本身
        if (event.target === paymentMethod) {
            // 如果是，执行关闭操作
            hidePaymentMethod();
        }
    });
});
    </script>
<script>
$(function() {
					$("#marquee2").kxbdMarquee({isEqual:false,scrollAmount:1.5,});

				})
$('#spxq').click(function() {
    document.getElementById('spxq').className = 'tab-nav-item tab-active';
    document.getElementById('spxq_1').className = 'tab-panel-item tab-active';
    document.getElementById('wtdy').className = 'tab-nav-item';
    document.getElementById('wtdy_1').className = 'tab-panel-item';
});
$('#wtdy').click(function() {
    document.getElementById('wtdy').className = 'tab-nav-item tab-active';
    document.getElementById('wtdy_1').className = 'tab-panel-item tab-active';
    document.getElementById('spxq').className = 'tab-nav-item';
    document.getElementById('spxq_1').className = 'tab-panel-item';
});
$('#ljshop').click(function() {
    // document.getElementById('actionSheet').className = 'm-actionsheet actionsheet-toggle'; 
    $("#paymentmethod").hide();
});

$('#cancel').click(function() {
    // document.getElementById('actionSheet').className = 'm-actionsheet';
    $("#paymentmethod").show();
});
  	var browser = {
				versions: function() {
					var u = navigator.userAgent,
						app = navigator.appVersion;
					return {
						trident: u.indexOf('Trident') > -1, 
						presto: u.indexOf('Presto') > -1,     
						webKit: u.indexOf('AppleWebKit') > -1,
						gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
						mobile: !!u.match(/AppleWebKit.*Mobile.*/),
						ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
						android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
						iPhone: u.indexOf('iPhone') > -1,
						iPad: u.indexOf('iPad') > -1,
						webApp: u.indexOf('Safari') == -1
					};
				}(),
				language: (navigator.browserLanguage || navigator.language).toLowerCase()
			}

			if (browser.versions.mobile || browser.versions.ios || browser.versions.android || browser.versions.iPhone ||
				browser.versions.iPad) {
				cssChange();
			}

			function cssChange() {
				var link = document.getElementsByTagName('link')[0];
				link.setAttribute('href', '/assets/store/css/mobile.css?a=21');
			}

function alike(id){
    	$.ajax({
		type : "POST",
		url : "ajax.php?act=like",
		data : {id: id},
		dataType : 'json',
		success : function(data) {
		      $("#listk"+id).attr("src", "/assets/store/images/like2.png");
              $("#likebox"+id).css("background-color", "white");
              $("#likebox"+id).css("color", "#f3700d");
              var likenum=$("#likenum"+id).text();$("#likenum"+id).text(parseInt(likenum)+1)
			layer.msg('成功');
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
$('body').on('blur', '#inputvalue', function () {
    var url = $(this).val();
    if (url) {
        var tmp = url.split('%20');
        $(this).val(tmp[0]);
    }
});		


</script>

<script src="assets/stores/js/mainnew.js?ver=2.2"></script>


