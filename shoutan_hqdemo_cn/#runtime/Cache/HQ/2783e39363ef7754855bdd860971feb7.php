<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>
        <?php if($Config["be_logo_name"] != ''): echo ($Config["be_logo_name"]); ?>
            <?php else: ?>
            后台管理系统<?php endif; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="HQCMS管理系统 by V5.0"/>
    <meta name="author" content="HeQi"/>
    <link rel="shortcut icon" href="/Application/HQ/View/default/Public/img/favicon_dj.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/Application/HQ/View/default/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/css/style-metro.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>


    <!-- END GLOBAL MANDATORY STYLES -->
    <link href="/Application/HQ/View/default/Public/css/global.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
    <link href="/Application/HQ/View/default/Public/css/code.css" rel="stylesheet" type="text/css"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
    <?php if($Config["be_logo"] != ''): ?><img src="/Uploads/<?php echo ($Config["be_logo"]); ?>" alt="<?php echo ($Config["be_logo_name"]); ?>"/>
        <?php elseif($Config["be_logo_name"] != ''): ?>
        <?php echo ($Config["be_logo_name"]); ?>
        <?php else: ?>
        HQCMS<?php endif; ?>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="form-vertical login-form" action="/index.php?m=HQ&c=Login&a=index" method="post">
        <h3 class="form-title">后台登录</h3>

        <div class="alert alert-error hide">
            <button class="close" data-dismiss="alert"></button>
            <span>请输入用户名和密码！</span>
        </div>
        <div class="control-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">用户名</label>

            <div class="controls">
                <div class="input-icon left">
                    <i class="icon-user"></i>
                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="用户名" name="username"/>
                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label visible-ie8 visible-ie9">密码</label>

            <div class="controls">
                <div class="input-icon left">
                    <i class="icon-lock"></i>
                    <input class="m-wrap placeholder-no-fix" type="password" placeholder="密码" name="password"/>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="code-box">
                <span class="code-update" id="changeCode"></span>
                <span class="code-pic"><img src="<?php echo U('Login/code');?>" id="verifyImg"/></span>
                <div class="input-icon left">
                    <i class="icon-barcode"></i>
                    <input class="form-control" type="text" name="code" autocomplete="off" placeholder="验证码"/>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="1"/> 记住我
            </label>
            <button type="submit" class="btn green pull-right">
                登录 <i class="m-icon-swapright m-icon-white"></i>
            </button>
            <input type="hidden" name="logintype" value="login"/>
        </div>
        <div class="forget-password">
            <h4>忘记密码 ?</h4>

            <p>别担心, 点击 <a href="javascript:;" class="" id="forget-password">这里</a> 找回你的密码.
            </p>
        </div>
    </form>
    <!-- END LOGIN FORM -->
    <!-- BEGIN FORGOT PASSWORD FORM -->
    <form class="form-vertical forget-form" action="<?php echo U('Login/forgot');?>" method="post">
        <h3 class="">忘记密码 ?</h3>

        <p>请输入你的邮箱用于找回密码</p>

        <div class="control-group">
            <div class="controls">
                <div class="input-icon left">
                    <i class="icon-envelope"></i>
                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="邮箱" name="email"/>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="back-btn" class="btn">
                <i class="m-icon-swapleft"></i> 返回
            </button>
            <button type="submit" class="btn green pull-right">
                提交 <i class="m-icon-swapright m-icon-white"></i>
            </button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
    <?php echo date('Y');?> &copy; HQCMS V5.0
</div>
<!-- END COPYRIGHT -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<script src="/Application/HQ/View/default/Public/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/Application/HQ/View/default/Public/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="/Application/HQ/View/default/Public/plugins/excanvas.min.js"></script>
<script src="/Application/HQ/View/default/Public/plugins/respond.min.js"></script>
<![endif]-->
<script src="/Application/HQ/View/default/Public/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Application/HQ/View/default/Public/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Application/HQ/View/default/Public/scripts/app.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/scripts/login-soft.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function () {
        App.init();
        Login.init();
        $.backstretch([
            "/Application/HQ/View/default/Public/img/bg/1.jpg",
            "/Application/HQ/View/default/Public/img/bg/2.jpg",
            "/Application/HQ/View/default/Public/img/bg/3.jpg",
            "/Application/HQ/View/default/Public/img/bg/4.jpg"
        ], {
            fade: 1000,
            duration: 8000
        });
    });
    //更换验证码
    $('body').on('click','#changeCode',function(){
        var num=Math.random(0,100000);
        var verifyImgUrl = 'index.php?m=HQ&c=Login&a=code&nm='+num;
        $.ajax({
            type:"get",
            url:"<?php echo U('Login/code');?>",
            success:function(){
                $('#verifyImg').attr('src',verifyImgUrl);
            },
            beforeSend:function(){
                $("#changeCode").addClass('code-rotate');
            },
            complete:function(){
                $("#changeCode").removeClass('code-rotate');
            }
        });
    });
    $('body').on('focus','.error .code-box input',function(){
        $(this).parents('.code-box').css('border-color','#ddd');
    });
    $('body').on('blur','.error .code-box input',function(){
        $(this).parents('.code-box').css('border-color','#b94a48');
    });
</script>
<!-- END JAVASCRIPTS -->

</body>
<!-- END BODY -->
</html>