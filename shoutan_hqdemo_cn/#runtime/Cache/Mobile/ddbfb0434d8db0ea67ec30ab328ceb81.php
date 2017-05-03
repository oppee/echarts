<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title><?php echo ($meta["title"]); ?></title>
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
    <a href="javascript:history.back(-1);" class="turn" title=""><i class="iconfont">&#xe601;</i>一皆通</a>
    <h1 class="weui-title">绑定账号</h1>
</header>

<!--logo-->
<div class="logo">
    <a href=""><img src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/images/login_logo.png"/></a>
</div>

<!--表单-->
<div class="weui_cells_form">
    
	<form class="login" id="login_form" action="<?php echo U('bind');?>" >
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" for="phone"><i class="iconfont">&#xe604;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" name="phone" placeholder="登记手机">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label pass-label" for="pass"><i class="iconfont">&#xe606;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" name="store" placeholder="商户">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label pass-label" for="fujiama"><i class="iconfont">&#xe608;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary addition">
                <input id="code" class="weui_input" type="text" name="code" placeholder="激活码">
                 
            </div>
        </div>
    </form>

    <p class="weui_btn_area">
        <!--<a href="<?php echo U('bind');?>" class="weui_btn weui_btn_warn" title="">绑定</a>-->
		<a href="javascript:;" class="weui_btn weui_btn_warn " id="login_btn" title="绑定">绑定</a>
    </p>
    <p class="code_link"><a href="javascript:;" id="getcode">获取激活码</a></p>
</div>

 
<script type="text/javascript">
	$("#getcode").click(function () {
		$.alert('激活码获取成功！请查看你的手机短信！');
	});
 
    $("#login_btn").click(function () {
        var phone = $("input[name=phone]").val();
        if(phone==''){
            $.alert('请输入手机号！');
            return false;
        }else{
            if(!(/^1[3|4|5|7|8][0-9]\d{8,8}$/).test(phone)){
                $.alert('手机格式错误！');
                return false;
            }
        }
		
        var store = $("input[name=store]").val();
        if(store==''){
            $.alert('请输入/选择商户！');
            return false;
        }
		
		var code = $("input[name=code]").val();
        if(code==''){
            $.alert('请输入激活码！');
            return false;
        }
		
		$.alert('绑定成功！');
		
		window.location.href = "<?php echo U('bind');?>";
		
        var sendurls = $("#login_form").attr("action");
        var sendForms = $("#login_form").serialize();
        /*$.ajax({
            type:'post',
            url:sendurls,
            data:sendForms,
            dataType:'json',
            success:function(data){
                if(data.status == 1){
                    $.alert(data.info, function() {
                        window.location.href = data.url;
                    });
                }else{
                    $.alert(data.info);
                }
            }
        });*/
    });
</script>

 

</body>
</html>