<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title><?php if(!empty($title)): echo ($title); else: ?>{meta.$title}<?php endif; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="keywords" content="<?php echo ($meta["keywords"]); ?>"/>
    <meta name="description" content="<?php echo ($meta["description"]); ?>"/>
    <link rel="stylesheet" href="/Application/Mobile/View/shoutan_v2/Public/css/weui.min.css">
    <link rel="stylesheet" href="/Application/Mobile/View/shoutan_v2/Public/css/jquery-weui.min.css">
    <link rel="stylesheet" href="/Application/Mobile/View/shoutan_v2/Public/css/iconfont.css">
    <link rel="stylesheet" href="/Application/Mobile/View/shoutan_v2/Public/css/global.css">
    <link rel="stylesheet" href="/Application/Mobile/View/shoutan_v2/Public/css/font-awesome.min.css">
	
	<script src="/Application/Mobile/View/shoutan_v2/Public/js/jquery-2.1.4.js"></script>
	<script src="/Application/Mobile/View/shoutan_v2/Public/js/jquery-weui.min.js"></script>
</head>

<body ontouchstart>
<!--header-->
<header class='weui-header'>
    <a href="<?php echo U('login');?>" class="turn" title=""><i class="iconfont">&#xe601;</i>一皆通</a>
    <h1 class="weui-title">登录<?php echo ($mata["title"]); ?></h1>
</header>

<!--logo-->
<div class="logo">
    <a href=""><img src="/Application/Mobile/View/shoutan_v2/Public/images/login_logo.png"/></a>
</div>

<!--表单-->
<div class="weui_cells_form">
    <div class="form_box">
        
        <form class="login content form1" id="login_form" action="<?php echo U('login');?>" >
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label" for="username"><i class="iconfont">&#xe606;</i></label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="username" type="text" placeholder="用户名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label pass-label" for="password"><i class="iconfont">&#xe603;</i></label></div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" id="password" type="password" name="password" placeholder="密码">
                </div>
            </div>
            <input type="hidden" name="openid" value="<?php echo ($openid); ?>" />
        </form>
         
    </div>

    <p class="weui_btn_area">
		<a href="javascript:;" class="weui_btn weui_btn_warn wechat_bind" id="login_btn" title="下一步">下一步</a>
    </p>
	<p class="code_link ">
		<a href="<?php echo U('register');?>" class="left">会员注册</a>
   	 	<a href="<?php echo U('forgotpwd');?>" class="right">忘记密码</a><br>
	</p>
</div>
 
<script type="text/javascript">
    $("#login_btn").click(function () {
        var username = $("input[name=username]").val();
        if(username==''){
            $.alert('请输入用户名或者手机号！');
            return false;
        }
        var password = $("input[name=password]").val();
        if(password==''){
            $.alert('请输入密码！');
            return false;
        }
		//window.location.href = "<?php echo U('bind');?>";
		
        var sendurls = $("#login_form").attr("action");
        var sendForms = $("#login_form").serialize();
        $.ajax({
            type:'post',
            url:sendurls,
            data:sendForms,
            dataType:'json',
            success:function(data){
                if(data.status == 1){
                   // $.alert(data.info, function() {
                        window.location.href = data.url;
                   // });
                }else{
                    $.alert(data.info);
                }
            }
        });
    });
</script>

 

</body>
</html>