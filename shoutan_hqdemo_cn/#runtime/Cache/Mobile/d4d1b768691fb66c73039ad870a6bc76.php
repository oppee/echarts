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
	<a href="javascript:history.back(-1);" class="turn" title=""><i class="iconfont">&#xe601;</i>一皆通</a>
    <h1 class="weui-title">注册</h1>
</header>

<!--logo-->
<div class="logo">
    <a href=""><img src="/project/yijietong/shoutan_hqdemo_cn/Application/Mobile/View/shoutan/Public/images/login_logo.png"/></a>
</div>

<!--表单-->
<div class="weui_cells_form">
	<form class="login" id="login_form" action="<?php echo U('register');?>" >
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" for="phone"><i class="iconfont">&#xe604;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input id="phone" class="weui_input" name="phone" type="tel" placeholder="手机号">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label pass-label" for="fujiama"><i class="iconfont">&#xe608;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary addition">
                <input name="code" data-count="60" class="weui_input" type="text" placeholder="验证码">
                
                <a class="getcode" href="javascript:;" id="getcode"> <span>获取验证码</span></a>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label pass-label" for="pass"><i class="iconfont">&#xe603;</i></label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input  class="weui_input" type="password" name="password" placeholder="密码">
            </div>
        </div>
        <div class="weui_cell">
               <div class="weui_cell_hd"><label class="weui_label pass-label" for=""><i class="iconfont">&#xe603;</i></label></div>
               <div class="weui_cell_bd weui_cell_primary">
                   <input class="weui_input" type="password" name="confirm_password" placeholder="确认密码">
               </div>
        </div>
        <input type="hidden" name="openid" value="<?php echo ($openid); ?>" />
    </form>

    <p class="weui_btn_area">
		<a href="javascript:;" class="weui_btn weui_btn_warn " id="login_btn" title="下一步">下一步</a>
    </p>
</div>


<script type="text/javascript">
	$("#getcode").click(function () {
		if(!$(this).hasClass('send_on')){
			var phone = $('#phone').val();
			if(!phone){
				$.alert('请输入手机号！');
				return false;
			}	
			$(this).addClass('send_on');
			//发送验证码
			var url = "<?php echo U('sendCode');?>";
			$.post(url,"",function(msg){
				$.toast(msg.info);
				dao();
			});
		}
	});
 
 // 5秒倒计时
    function dao(){
        var count = $("input[name=code]").data('count');
        if((parseInt(count)-1)>0){
            $("input[name=code]").data('count',parseInt(count)-1);
            $("#getcode span").text('剩余'+(parseInt(count)-1)+'秒');
            setTimeout('dao()',1000);
        }else{
            $("input[name=code]").data('count',60);
            $("#getcode").removeClass('send_on');
            $("#getcode span").text('重新获取验证码');
        }
    }
	
    $("#login_btn").click(function () {
		var regex = /^[a-zA-Z]\w{5,15}$/;

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
		
		var code = $("input[name=code]").val();
        if(code==''){
            $.alert('请输入验证码！');
            return false;
        }
		
		var password = $("input[name=password]").val();
        if(password==''){
            $.alert('请输入密码！');
            return false;
        }
		
		if(!regex.test(password)){
			$.alert('密码必须以字母开头,长度在6-16之间,只能包含字符、数字和下划线！！');
            return false;
		}
		

		var confirm_password = $("input[name=confirm_password]").val();
        if(confirm_password==''){
            $.alert('请输入确认密码！');
            return false;
        }
		
		if(password!=confirm_password){
            $.alert('两次输入的密码不一致，请重新输入！');
            return false;
        }
		
		//window.location.href = "<?php echo U('agree');?>"
		
		/*$.alert("注册成功!", function() { 
			window.location.href = "<?php echo U('agree');?>"
		})*/;

		
        var sendurls = $("#login_form").attr("action");
        var sendForms = $("#login_form").serialize();
        $.ajax({
            type:'post',
            url:sendurls,
            data:sendForms,
            dataType:'json',
            success:function(data){
                if(data.status == 1){
                    /* $.alert(data.info, function() {
                        window.location.href = data.url;
                    }); */
                    window.location.href = data.url;
                }else{
                    $.alert(data.info);
                }
            }
        });
    });
</script>

 

</body>
</html>