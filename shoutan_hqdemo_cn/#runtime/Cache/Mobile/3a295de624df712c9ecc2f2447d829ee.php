<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title><?php if(!empty($title)): echo ($title); else: ?>{meta.$title}<?php endif; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="keywords" content="<?php echo ($meta["keywords"]); ?>"/>
    <meta name="description" content="<?php echo ($meta["description"]); ?>"/>
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/weui.min.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/iconfont.css">
    <link rel="stylesheet" href="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/css/global.css">
	
	<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/js/jquery-2.1.4.js"></script>
	<script src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/js/jquery-weui.min.js"></script>
</head>

<body ontouchstart>
<!--header-->
<header class='weui-header'>
    <a href="javascript:history.back(-1);" class="turn" title=""><i class="iconfont">&#xe601;</i>订阅号</a>
    <h1 class="weui-title">我的账本</h1>
</header>
<!--search-->
<div class="weui_search_bar weui_search_focusing search_focus">
    <form class="weui_search_outer">
        <div class="weui_search_inner">
            <i class="weui_icon_search"></i>
            <input type="search" class="weui_search_input" id="search_input" placeholder="搜索" required/>
            <a href="javascript:" class="weui_icon_clear" id="search_clear"></a>
        </div>

    </form>
     
</div>

<!--btn_group-->
<div class="weui_panel btn_group">
    <div class="btn_items check_op">
        <div class="weui_panel_hd">名下企业</div>
        <div class="weui_panel_bd">
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">上海首坦金融服务信息有限公司</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor">一皆通科技有限公司</a>
        </div>
    </div>
    <div class="btn_items btn_sm check_op clearfix">
        <div class="weui_panel_hd">机具</div>
        <div class="weui_panel_bd machine clearfix">
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">Na1234567890</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor black_bg">Na1234567890</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor">Na1234567890</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor black_bor black_bg">Na1234567890</a>
        </div>
    </div>
    <div class="btn_items btn_sm check_op clearfix">
        <div class="weui_panel_hd">支付方式</div>
        <div class="weui_panel_bd machine clearfix">
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">支付宝</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">微信</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">刷卡</a>
        </div>
    </div>
    <div class="btn_items btn_sm clearfix">
        <div class="weui_panel_hd">交易日期</div>
        <div class="weui_panel_bd machine clearfix">
            <input type="text" class="black_bor" id="date" placeholder="2016-05-03" data-toggle='date'>
            <input type="text" class="black_bor" id="date2" placeholder="至今" data-toggle='date'>
        </div>
    </div>
    <div class="btn_items btn_sm check_op">
        <div class="weui_panel_hd">交易状态</div>
        <div class="weui_panel_bd machine clearfix">
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor">收款成功</a>
            <a href="javascript:;" class="weui_btn weui_btn_plain_default black_bor blue_bor">收款失败</a>
        </div>
    </div>
</div>

<!--booton_bar-->
<div class="botton_btn">
    <div class="weui-row weui-no-gutter">
        <div class="weui-col-50">
            <a href="javascript:;" class="weui_btn reset_btn">重置</a>
        </div>
        <div class="weui-col-50">
            <a href="<?php echo U('index');?>" class="weui_btn success_btn">完成</a>
        </div>
    </div>
</div>
<script>
    $(".check_op .weui_panel_bd a").click(function(){
//        $(this).addClass("blue_bor");
        if($(this).hasClass("blue_bor")){
            $(this).removeClass("blue_bor");
        } else $(this).addClass("blue_bor");
    })

//    日历
//    $("#date,#date2").calendar({
//        onChange: function (p, values, displayValues) {
//            console.log(values, displayValues);
//        }
//    });

    $("#date,#date2").calendar({
        value: ['2016-12-12'],
        dateFormat: 'yyyy年mm月dd日'
    });
</script>

 

</body>
</html>