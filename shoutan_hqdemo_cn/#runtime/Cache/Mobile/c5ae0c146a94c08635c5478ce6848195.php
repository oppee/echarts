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
	<form class="login" id="login_form" action="<?php echo U('agree');?>" >
        <p class="weui_msg_desc">
			<!-- 《用户注册协议》是制约用户滥用违法的手段干扰网站的运行的手段。

“法律百科”所提供的各项服务的所有权和运作权属于其运营商。用户必须同意下述所有服务条款并完成注册程序，才能成为“法律百科”的正式会员并使用“法律百科”提供的各项服务。服务条款的修改权归“法律百科”运营商所有。
　　一、 “法律百科”运用自己的操作系统，通过国际互联网络等手段为会员提供法律信息交流平台。“法律百科”有权在必要时修改服务条款，服务条款一旦发生变动，将会在重要页面上提示修改内容或通过其他形式告知会员。如果会员不同意所改动的内容，可以主动取消获得的网络服务。如果会员继续享用网络服务，则视为接受服务条款的变动。“法律百科”保留随时修改或中断服务而不需知照会员的权利。“法律百科”行使修改或中断服务的权利，不需对会员或第三方负责。
　　二、保护会员隐私权
　　您注册“法律百科”相关服务时，跟据网站要求提供相关个人信息；在您使用“法律百科”服务、参加网站活动、或访问网站网页时，网站自动接收并记录的您浏览器上的服务器数据，包括但不限于IP地址、网站Cookie中的资料及您要求取用的网页记录；“法律百科”承诺不公开或透露您的密码、手机号码等在本站的非公开信息。除非因会员本人的需要、法律或其他合法程序的要求、服务条款的改变或修订等。
　　为服务用户的目的，“法律百科”可能通过使用您的个人信息，向您提供服务，包括但不限于向您发出活动和服务信息等。
　　同时会员须做到：
　　● 用户名和昵称的注册与使用应符合网络道德，遵守中华人民共和国的相关法律法规。
　　● 用户名和昵称中不能含有威胁、淫秽、漫骂、非法、侵害他人权益等有争议性的文字。
　　● 注册成功后，会员必须保护好自己的帐号和密码，因会员本人泄露而造成的任何损失由会员本人负责。
　　● 不得盗用他人帐号，由此行为造成的后果自负。
　　您的个人信息将在下述情况下部分或全部被披露：
　　● 经您同意，向第三方披露；
　　● 如您是合资格的知识产权投诉人并已提起投诉，应被投诉人要求，向被投诉人披露，以便双方处理可能的权利纠纷；
　　● 根据法律的有关规定，或者行政或司法机构的要求，向第三方或者行政、司法机构披露；
　　● 如果您出现违反中国有关法律或者网站政策的情况，需要向第三方披露；
　　● 为提供你所要求的产品和服务，而必须和第三方分享您的个人信息；
　　● 其他本网站根据法律或者网站政策认为合适的披露 -->
			</p>
			<?php echo ($age); ?>
        <input type="hidden" name="openid" value="<?php echo ($openid); ?>" />
    </form>

    <p class="weui_btn_area">
		<a href="javascript:;" class="weui_btn weui_btn_warn " id="login_btn" title="同意并且创建用户">同意并创建用户</a>
    </p>
</div>


<script type="text/javascript">
	 
    $("#login_btn").click(function () {
		/* $.alert("用户创建成功!", function() {
			window.location.href = "<?php echo U('login');?>"
		}); */

        var sendurls = $("#login_form").attr("action");
        var sendForms = "check=1";//$("#login_form").serialize();
        $.ajax({
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
        });
    });
</script>

 

</body>
</html>