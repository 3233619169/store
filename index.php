<?php

if (!defined('IN_CRONLITE')) die();

if ($_GET['buyok'] == 1 || $_GET['chadan'] == 1) {
    include_once TEMPLATE_ROOT . 'stores/query.php';
    exit;
}
if (isset($_GET['tid']) && !empty($_GET['tid'])) {
    $tid = intval($_GET['tid']);
    $tool = $DB->getRow("select tid from pre_tools where tid='$tid' limit 1");
    if ($tool) {
        exit("<script language='javascript'>window.location.href='./?store=buy&tid=" . $tool['tid'] . "';</script>");
    }
}

$cid = intval($_GET['cid']);
if (!$cid && !empty($conf['defaultcid']) && $conf['defaultcid'] !== '0') {
    if($siterow['dcid']>=1){$cid=intval($siterow['dcid']);}else{$cid= intval($conf['defaultcid']);}
}
$ar_data = [];
$classhide = explode(',', $siterow['class']);
$re = $DB->query("SELECT * FROM `pre_class` WHERE `active` = 1 ORDER BY `sort` ASC ");
$qcid = "";
$cat_name = "";
while ($res = $re->fetch()) {
    if ($is_fenzhan && in_array($res['cid'], $classhide)) continue;
    if ($res['cid'] == $cid) {
        $cat_name = $res['name'];
        $qcid = $cid;
    }
    $ar_data[] = $res;
}


$class_show_num = intval($conf['index_class_num_style']) ? intval($conf['index_class_num_style']) : 2; //分类展示几组


?>
<!DOCTYPE html>
<html lang="zh" style="font-size: 102.4px;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no" />
		<script> document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + "px";</script>
		<meta name="format-detection" content="telephone=no">
		<meta name="csrf-param" content="_csrf">
		<title>
			<?php echo $hometitle?>
		</title>
		<meta name="keywords" content="">
		<meta name="description" content="">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/foxui.diy.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/iconfont.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnserver; ?>assets/stores/css/index.css?ver=1.1">
		<link rel="stylesheet" type="text/css" href="<?php echo $cdnpublic ?>layui/2.5.7/css/layui.css">
		<link href="<?php echo $cdnpublic?>Swiper/6.4.5/swiper-bundle.min.css" rel="stylesheet">
		<?php echo str_replace('body','html',$background_css)?>
		<script>
			var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?bd09b3ca33d081f728dbcf5a006b2ac5";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
	</head>
	<style type="text/css">
		.fui-goods-group.block .fui-goods-item .image {
		     width: 100%; 
		     margin: unset; 
		     padding-bottom: unset; 
		     <?php if(checkmobile()){ ?>
		        height:5.5rem;
		     <?php }else{ ?>
		        height:8rem;
		     <?php } ?>
		     
		
		}
		.content-slide .shop_active .icon-title {
		    color: #ffffff;
		    background-color: #f44;
		    border-radius: 10px;
		    font-weight: 700;
		    padding: 2px;
		}
		.annc-bar {
		    position: relative;
		    display: flex;
		    align-items: center;
		    cursor: pointer;
		    height: 30px;
		    background: #f9f9f9;
		    border: 1px solid #e1e1e1;
		    box-shadow: 0 2px 4px 0 rgba(0,0,0,.02);
		    border-radius: 8px;
		    line-height: 20px;
		    font-size: 12px;
		    color: red;
		    font-weight: 600;
		    padding: 0 7px 0 8px;
		}
		.swiper-container{
		    --swiper-theme-color: #009aff;/* 设置Swiper风格 */
		    --swiper-navigation-color: #009aff;/* 单独设置按钮颜色 */
		    --swiper-navigation-size: 30px;/* 设置按钮大小 */
		}
		.blur-image::before {
		    content: "";
		    position: absolute;
		    top: 0;
		    left: 0;
		    width: 100%;
		    height: 100%;
		    background-image: url('./bn.jpg');
		    background-size: cover;
		    background-position: center;
		    filter: blur(5px); /* 调整模糊程度，数值越大越模糊 */
		    z-index: -1;
		}
		
		.swiper-pagination-bullet {
		  width: 20px;
		  height: 20px;
		  text-align: center;
		  line-height: 20px;
		  font-size: 15px;
		  color: #000;
		  opacity: 1;
		  background: rgba(0, 0, 0, 0.2);
		}
		
		.swiper-pagination-bullet-active {
		  color: #fff;
		  background: #ed414a;
		}
		.swiper-pagination{
		    position: unset;
		}
		.pop-announ .layui-layer-setwin{right:1vw;top:1vw}
		.pop-announ .layui-layer-setwin .layui-layer-close2{top:0;right:0;background:url(../assets/zhike/images/icon_close.png) no-repeat center}
		.layui-layer.pop-announ{ border-radius: 12px;width:90%; background-image: url(../assets/img/index/tzbg.jpg);background-repeat: no-repeat; background-position: 0 0;background-size: 100%; min-height: 312px;width: 312px;margin: 0 auto; padding-top: 22px;-webkit-background-clip: border-box;
		}
	</style>
	<body ontouchstart="" style="overflow: auto;height: auto !important;max-width: 650px;">
		<div id="body">
			<div class="fui-page-group " style="height: auto;max-width:650px">
				<div class="fui-page  fui-page-current " style="height:auto; overflow: inherit;">
					<div class="fui-content navbar" id="container" style="background-color: #fafafc;overflow: inherit;">
						<div class="default-items">
							<div class="blur-image">
								<div class="fui-swipe" style="height:7.75rem;width:95%;border-radius:10px;background-size:cover ;">
									<form action="" method="get" id="goods_search" style="position: relative; z-index: 10;">
										<input type="hidden" value="yes" name="search">
										<div class="searchbar" style="margin: 0; position: relative;    padding: 5px;">
											<div class="search-input" style="border-radius: 15px;    border: 2px solid #ebebeb; background: white; padding: 3px 8px; height: 28px; line-height: 28px;">
												<i class="icon icon-search" style="color: #888; position: absolute; left: 8px; top: 50%; transform: translateY(-50%);"></i>
												<input type="text" class="search"
													   value="<?php echo trim(daddslashes($_GET['kw'])); ?>" name="kw"
													   placeholder="输入商品名称" id="kw" style="border: none; padding: 0 10px 0 25px; width: calc(100% - 20px); outline: none; background: transparent; font-size: 14px; height: 100%;">
											</div>
											<input type="submit" class="searchbtn" value="搜索" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); height: 20px; line-height: 20px; padding: 0 8px; background: transparent; border: none; cursor: pointer; z-index: 2;">
										</div>
									</form>
									<style>

										}
                            .fui-swipe-page .fui-swipe-bullet {
                                background: #ffffff;
                               
                            }

                            .fui-swipe-page .fui-swipe-bullet.active {
                                opacity: 1;
                            }
                        </style>
									<div class="fui-swipe-wrapper" style="transition-duration: 500ms;">
										<?php
                            $banner = explode('|', $conf['banner']);
                            foreach ($banner as $v) {
                                $image_url = explode('*', $v);
                                echo '<a class="fui-swipe-item" href="' . $image_url[1] . '"><img src="' . $image_url[0] . '" style="display: block; width: 100%; height: auto;" /></a>';
                            }
                            ?>
									</div>
									<div class="fui-swipe-page right round" style="padding: 0 5px; bottom: 5px; "></div>
								</div>
								<div class="announcement part-wrap">
									<div class="anc-txt left-wrap">
										<div class="annc-bar">
											<i class="icon annc-bar-icon">
												<img src="<?php echo $cdnserver?>assets/zhike/images/icon_annc01_blue.svg" class="icon-dimage">
											</i>
											<div class="annc-bar-wrap" id="annou-list">
												<ul>
													<li name="showAnn" style="white-space:nowrap; margin-right: 10px;">
														<p>
															<?php echo $conf['anounce']?>
														</p>
													</li>
													<i class="layui-icon layui-icon-next" style="position: absolute; right: 0px; top: 50%; transform: translateY(-50%); z-index: 1000;"></i>
												</ul>
											</div>
										</div>
										<br>
									</div>
								</div>
							</div>
							<div class="device" style="margin-top:-20px">
								<br>
								<div class="swiper-container">
									<div class="swiper-wrapper"
										 style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
										<?php
                                $arry = 0;
                                $au = 1;
                                foreach ($ar_data as $v) {
                                    if (($arry / ($class_show_num * 6)) == ($au - 1)) { //循环首
                                        echo '<div class="swiper-slide swiper-slide-visible swiper-slide-prev" data-swiper-slide-index="' . $au . '" style="margin: auto;margin-top: 0px;"><div class="content-slide">';
                                    }
                                    echo '<a data-cid="' . $v['cid'] . '" data-name="' . $v['name'] . '" class="get_cat"><div class="mbg"><p class="ico"><img src="' . $v['shopimg'] . '" onerror="this.src=\'assets/store/picture/1562225141902335.jpg\'"></p><p class="icon-title">' . $v['name'] . '</p></div></a>';

                                    if ((($arry + 1) / ($class_show_num * 6)) == ($au)) { //循环尾
                                        echo '</div></div>';
                                        $au++;
                                    }
                                    $arry++;
                                }
                                if (floor((($arry) / ($class_show_num * 6))) != (($arry) / ($class_show_num * 6))) {
                                    echo '</div></div>';
                                }
                                ?>
									</div>
									<div class="swiper-pagination"></div>
									<div class="swiper-button-next" style="display:none"></div>
									<div class="swiper-button-prev" style="display:none"></div>
								</div>
							</div>
							<script></script>
						</div>
						<div class="fui-notice">
							<div class="slider">
								<div class="slider-wrapper tags"></div>
							</div>
							<section style="text-align: center;display:none;height: 1rem;line-height: 1.1rem;" class="show_class">
								<section style="display: inline-block;" class="">
									<section class="135brush" data-brushtype="text" style="clear:both;margin:-18px 0px;text-align: center;color:#333;border-radius: 6px;padding:0px 1.5em;letter-spacing: 1.5px;">
										<span style="color: #6C6C6C;">
											<strong>
												<span style="font-size: 15px;">
													<span class="catname_show">正在获取数据...</span>
												</span>
											</strong>
										</span>
									</section>
								</section>
							</section>
						</div>
						<div style="height: 3px"></div>
						<div class="layui-tab tag_name tab_con" style="margin:0;display:none;">
							<ul class="layui-tab-title" style="margin: 0;background:#fff;overflow: hidden;"></ul>
						</div>
						<div class="fui-goods-group" style="background: #f3f3f3;" id="goods-list-container">
							<div class="flow_load">
								<div id="goods_list"></div>
							</div>
							<div class="footer" style="width:100%; margin-top:0.5rem;margin-bottom:2.5rem;display: block;">
								<ul>
									<li>©
										<?php echo $conf['sitename'] ?>. All rights reserved</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="_cid" value="<?php echo $cid; ?>">
			<input type="hidden" name="_cidname" value="<?php echo $cat_name; ?>">
			<input type="hidden" name="_curr_time" value="<?php echo time(); ?>">
			<input type="hidden" name="_template_virtualdata" value="<?php echo $conf['template_virtualdata']?>">
			<input type="hidden" name="_template_showsales" value="<?php echo $conf['template_showsales']?>">
			<input type="hidden" name="_sort_type" value="">
			<input type="hidden" name="_sort" value="">
			<div class="fui-navbar" style="max-width: 650px;z-index: 100; height: 2.8rem;">
				<a href="./" class="nav-item active">
					<span class=" " style="width:35px;font-size:1.5rem;">
						<img src="./assets/img/index/indexs.svg" style="width:32px;">
					</span>
					<span class="label" style="color:#ff6c44">首页</span>
				</a>
				<a href="./?store=query" class="nav-item ">
					<span class="" style="font-size:1.5rem">
						<img src="./assets/img/index/order.svg" style="width:29px;">
					</span>
					<span class="label">订单</span>
				</a>
				<a href="./?store=kf" class="nav-item ">
					<span class="" style="font-size:1.5rem">
						<img src="./assets/img/index/kf.svg" style="width:30px;">
					</span>
					<span class="label">客服</span>
				</a>
				<?php if($conf['appurl']){?>
				<a href="<?php echo $conf['appurl']; ?>" class="nav-item ">
					<span class="" style="font-size:1.5rem;margin-top:15px;">
						<img src="./assets/img/index/app.svg" style="width:28px;">
					</span>
					<span class="label">APP下载</span>
				</a>
				<?php }else{?>
				<?php }?>
				<a href="./user/jm.php" class=" nav-item  ">
					<span class="" style="font-size:1.5rem">
						<img src="./assets/img/index/jia.svg" style="width:29px;">
					</span>
					<span class=" label titlename">加盟</span>
				</a>
				<a href="./user/" class="nav-item " Target="_blank">
					<span class="" style="font-size:1.5rem">
						<img src="./assets/img/index/user.svg" style="width:27px;">
					</span>
					<span class="label">会员中心</span>
				</a>
			</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div class="ant-modal-content" style="display: none;">
			<div class="ant-modal-header">
				<div class="ant-modal-title" style="line-height: 10px;">
					<div class="modal-title">
						<span></span>
					</div>
				</div>
			</div>
			<div class="ant-modal-body">
				<div class="modal-body">
					<div class="body-view">
						<div class="view-wrap">
							<div class="rich-text" id="tanchuang">
								<p style="text-align: center;">&nbsp;</p>
								<?php echo $conf['alert']?>
								<p style="text-align: center;">&nbsp;</p>
							</div>
						</div>
						<div class="view-footer">
							<button type="button" class="btn  layui-layer-close">
								<span>知道了</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="content">
  <!-- 页面内容会在此区域加载 -->
</div>
		<script src="<?php echo $cdnpublic ?>jquery/3.4.1/jquery.min.js"></script>
		<script src="<?php echo $cdnpublic ?>layui/2.5.7/layui.all.js"></script>
		<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
		<script src="<?php echo $cdnpublic ?>Swiper/6.4.5/swiper-bundle.min.js"></script>
		<script src="<?php echo $cdnserver ?>assets/stores/js/foxui.js"></script>
		<script src="<?php echo $cdnserver ?>assets/stores/js/layui.flow.js"></script>
		<script src="<?php echo $cdnserver ?>assets/stores/js/shouye3.js?ver=1011"></script>
		<script>
			// 动态计算字体大小以适应屏幕宽度
			        document.documentElement.style.fontSize = document.documentElement.clientWidth / 750 * 40 + 'px';
		</script>


		<script>
			var selectedCategoryId = <?php echo $cid; ?>;
			$(document).ready(function() {    $('.get_cat[data-cid="' + selectedCategoryId + '"]').addClass('shop_active');
			});
			tags(<?php if($siterow['dcid']>=1){echo $siterow['dcid'];}else{echo $conf['defaultcid'];}?>);
			
			function tags(cid){
			  $.ajax({
			    url: 'ajax.php?act=tags',
			    method: 'POST',
			    data: { cid: cid },
			    success: function(response) {
			                $('.tags').html('');
			                $(".tags").hide();
			if(response.code==0){
			                    $(".tags").show();
			                    var i=0;
			      response.data.forEach(function(item) {
			          i++;
			          if(i==1){
			        var html = '<div class="tag1 classavbd" id="tag'+i+'" onclick="ckw(' + cid + ', \'' + item + '\','+i+')">' + item + '</div>';
			          }else{
			               var html = '<div class="tag1" id="tag'+i+'" onclick="ckw(' + cid + ', \'' + item + '\','+i+')">' + item + '</div>';
			          }
			        $('.tags').append(html);
			      });
			}
			    },
			    error: function(xhr, status, error) {
			      console.error(error);
			    }
			  });
			  
			        
			
			}
			
			    function ckw(cid,tag,wz){
			        $("#tag"+wz).addClass("classavbd").siblings().removeClass("classavbd");
			        
			        if(tag!=="全部"){
			         $("input[name=_cid]").val(cid);
			        $("input[name=kw]").val(tag);
			        }else{
			            $("input[name=kw]").val('');
			        }
			        get_goods();
			    }
			  
			   $("#annou-list").click(function(){
			       
			                            layui.use('layer', function(){
			                                layer.open({
			                                    type: 1,
			                                    title: false,
			                                    content: $('.ant-modal-content'),
			                                    skin: 'pop-announ'
			                                    
			                                    
			                                });
			                            });
			
			                });
			                 $(document).ready(function() {  
    // 延迟1.5秒（1500毫秒）后执行函数  
    setTimeout(function() {  
        // 检查 localStorage 中是否有 'seenAnnouncement' 标记以及它的时间戳  
        var seenAnnouncement = localStorage.getItem('seenAnnouncement');  
        var currentDate = new Date();  
          
        // 如果没有标记，或者标记的时间超过 1 小时（60 * 60 * 1000 毫秒）  
        if (!seenAnnouncement || (currentDate - new Date(seenAnnouncement)) > (60 * 60 * 1000)) {  
            // 自动触发点击事件  
            $("#annou-list").trigger("click");  
              
            // 设置新的标记  
            localStorage.setItem('seenAnnouncement', currentDate.toISOString());  
        }  
    }, 1500); // 延迟时间为1500毫秒（1.5秒）  
}); 
		</script>
	
	</body>
</html>
