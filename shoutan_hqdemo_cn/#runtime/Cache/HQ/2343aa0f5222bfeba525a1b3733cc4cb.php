<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo ($PageName); ?> -
    <?php if(!empty($Config["logo_name"])): echo ($Config["logo_name"]); ?>
    <?php else: ?>
        后台管理系统<?php endif; ?>
    by HQCMS V5.0
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="description" content="HQCMS管理系统 by V5.0"/>
<meta name="author" content="HeQi"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/Application/HQ/View/default/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

<link href="/Application/HQ/View/default/Public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/css/style-metro.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="/Application/HQ/View/default/Public/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/plugins/chosen-bootstrap/chosen/chosen.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->

<link href="/Application/HQ/View/default/Public/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css"/>

<link href="/Application/HQ/View/default/Public/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css"
      media="screen"/>
<link href="/Application/HQ/View/default/Public/plugins/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css"
      rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/plugins/select2/select2_metro.css" rel="stylesheet" type="text/css"/>
<!-- toggle_select -->
<link href="/Application/HQ/View/default/Public/plugins/toggleselect/multi-select-metro.css" rel="stylesheet" type="text/css" />
<!--add-tags-->
<link href="/Application/HQ/View/default/Public/plugins/jquery-tags-input/jquery.tagsinput.css" rel="stylesheet" type="text/css"/>

<link href="/Application/HQ/View/default/Public/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link href="/Application/HQ/View/default/Public/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->
<link href="/Application/HQ/View/default/Public/css/global.css" rel="stylesheet" type="text/css"/>
<link rel="shortcut icon" href="/Application/HQ/View/default/Public/img/favicon_dj.ico"/>
<script type="text/javascript">
    var THEME_PATH = "/Application/HQ/View/default/";
</script>
    <link rel="stylesheet" type="text/css" href="/Application/HQ/View/default/Public/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
<!-- BEGIN TOP NAVIGATION BAR -->
<div class="navbar-inner">
<div class="container-fluid">
<!-- BEGIN LOGO -->
<a class="brand" href="/index.php/HQ">
    <?php if($Config["be_logo"] != ''): echo ($Config["be_logo_name"]); ?>
    <!--<img src="./Uploads/<?php echo ($Config["be_logo"]); ?>" alt="<?php echo ($Config["be_logo_name"]); ?>"/>-->
        <?php elseif($Config["be_logo_name"] != ''): ?>
        <?php echo ($Config["be_logo_name"]); ?>
        <?php else: ?>
        HQCMS<?php endif; ?>
</a>
<!-- END LOGO -->
<!-- BEGIN RESPONSIVE MENU TOGGLER -->
<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
    <img src="/Application/HQ/View/default/Public/img/menu-toggler.png" alt=""/>
</a>
<!-- END RESPONSIVE MENU TOGGLER -->
<!-- BEGIN TOP NAVIGATION MENU -->
<ul class="nav pull-right">
<!-- BEGIN NOTIFICATION DROPDOWN -->
<li class="dropdown" id="header_notification_bar" style="display:none">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="icon-warning-sign"></i>
        <span class="badge">6</span>
    </a>
    <ul class="dropdown-menu extended notification">
        <li>
            <p>You have 14 new notifications</p>
        </li>
        <li>
            <a href="#">
                <span class="label label-success"><i class="icon-plus"></i></span>
                New user registered.
                <span class="time">Just now</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="label label-important"><i class="icon-bolt"></i></span>
                Server #12 overloaded.
                <span class="time">15 mins</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="label label-warning"><i class="icon-bell"></i></span>
                Server #2 not respoding.
                <span class="time">22 mins</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="label label-info"><i class="icon-bullhorn"></i></span>
                Application error.
                <span class="time">40 mins</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="label label-important"><i class="icon-bolt"></i></span>
                Database overloaded 68%.
                <span class="time">2 hrs</span>
            </a>
        </li>
        <li>
            <a href="#">
                <span class="label label-important"><i class="icon-bolt"></i></span>
                2 user IP blocked.
                <span class="time">5 hrs</span>
            </a>
        </li>
        <li class="external">
            <a href="#">See all notifications <i class="m-icon-swapright"></i></a>
        </li>
    </ul>
</li>
<!-- END NOTIFICATION DROPDOWN -->

<!-- BEGIN USER LOGIN DROPDOWN -->
<li class="dropdown user">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img class="user-logo" alt="" src="<?php if(!empty($user_logo)): echo ($user_logo); else: ?>/Application/HQ/View/default/Public/img/avatar1_small.jpg<?php endif; ?>"  onerror="this.src='/Application/HQ/View/default/Public/img/avatar1_small.jpg'" />
        &nbsp;<span class="username"><?php if(!empty($_SESSION['BEUSER']['name'])): echo ($_SESSION['BEUSER']['name']); else: echo ($_SESSION['BEUSER']['username']); endif; ?></span>
        <i class="icon-angle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <li><a href="<?php echo U('Beusers/user_save', 'id='.$_SESSION['BEUSER']['id']);?>"><i class="icon-user"></i>&nbsp;我的资料</a></li>
        <li class="divider"></li>
				<li><a href="<?php echo ($Config["baseurl"]); ?>" target="_blank"><i class="icon-home"></i>&nbsp;查看前台</a></li>
        <li class="divider"></li>
				<li><a href="javascript:void(0)" class="cache" id="clear_cache" data-url="<?php echo U('Index/clearcache');?>"><i class="icon-wrench"></i>&nbsp;清除缓存</a></li>
        <li class="divider"></li>
        <!-- <li><a href="/index.php?logintype=logout"><i class="icon-key"></i>&nbsp;退出</a></li> -->
        <li><a href="<?php echo U('Index/index','logintype=logout');?>"><i class="icon-key"></i>&nbsp;退出</a></li>
    </ul>
</li>
<!-- END USER LOGIN DROPDOWN -->
</ul>
<!-- END TOP NAVIGATION MENU -->
</div>
</div>
<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
<div class="page-sidebar nav-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">
        <li>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone"></div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>

        <?php echo ($menu); ?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <div class="page-content">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">站点配置 <small>site configuration</small></h3>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid profile">
                <div class="span12">
                    <form action="<?php echo U('Index/config');?>" class="form-horizontal" enctype="multipart/form-data" method="post">
                        <!--BEGIN TABS-->
                        <div class="tabbable tabbable-custom tabbable-full-width">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1_1" data-toggle="tab">站点信息</a></li>
                                <li class=""><a href="#tab_1_2" data-toggle="tab">显示数量</a></li>
                                <!--<li class=""><a href="#tab_1_3" data-toggle="tab">邮件设置</a></li>-->
                                <li class=""><a href="#tab_1_4" data-toggle="tab">短信设置</a></li>
                                <li class=""><a href="#tab_1_5" data-toggle="tab">其他设置</a></li>
                                <!--<li class=""><a href="#tab_1_6" data-toggle="tab">登录接口</a></li>-->
                                <!--<li class=""><a href="#tab_1_7" data-toggle="tab">支付接口</a></li>-->
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane row-fluid active" id="tab_1_1">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption"><i class="icon-globe"></i>站点信息</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">网站名称</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="sitename" value="<?php echo ($Config["sitename"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">首页标题</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="home_title" value="<?php echo ($Config["home_title"]); ?>" /><span class="help-inline">一般不超过80个字符</span></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">访问地址</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="baseurl" value="<?php echo ($Config["baseurl"]); ?>" /><span class="help-inline">请填写完整地址</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">网站关键字</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="keywords" value="<?php echo ($Config["keywords"]); ?>" /><span class="help-inline">如有多个，请在每个之间使用半角逗号（,）分隔</span></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span12">
                                                            <div class="control-group">
                                                                <label class="control-label">网站描述</label>
                                                                <div class="controls"><textarea class="span12 m-wrap" rows="3" name="description"><?php echo ($Config["description"]); ?></textarea><span class="help-inline">一般不超过200个字符</span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--<div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">热门搜索</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="hot_keyword" value="<?php echo ($Config["hot_keyword"]); ?>" /><span class="help-inline">热门搜索关键字,请用半角逗号(,)分隔多个关键字</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">客服QQ</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="qq" value="<?php echo ($Config["qq"]); ?>" /><span class="help-inline">如有多个请在每个之间使用半角逗号（,）分隔</span></div><br>
                                                            </div>
                                                        </div>
                                                    </div>--> 

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">客服邮箱</label>
                                                                <div class="controls"><div class="input-icon left"><i class="icon-envelope"></i><input type="text" class="span12 m-wrap" name="email" value="<?php echo ($Config["email"]); ?>" /></div></div>																											</div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">客服电话</label>
                                                                <div class="controls"><div class="input-icon left"><i class="icon-phone"></i><input type="text" class="span12 m-wrap" name="tel" value="<?php echo ($Config["tel"]); ?>" /></div></div>
                                                            </div>
                                                        </div>
                                                    </div> 

                                                    <!--<div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">阿里旺旺</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="wangwang" value="<?php echo ($Config["wangwang"]); ?>" /><span class="help-inline">如有多个请在每个之间使用半角逗号（,）分隔</span></div><br>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">Skype账号</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="skype" value="<?php echo ($Config["skype"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>-->

                                                    <div class="row-fluid">
                                                         
                                                        <div class="span6">
                                                            <div class="control-group">
																<label class="control-label">敏感词过滤</label>
                                                                <div class="controls">
																	<textarea class="span12 m-wrap" rows="5" name="filter_keyword"><?php echo ($Config["filter_keyword"]); ?></textarea>
																	<span class="help-inline">如有多个，请在每个之间使用半角逗号（,）分隔</span>
																</div>
                                                            	
															</div>
                                                        </div>
														
														<div class="span6">
                                                            <div class="control-group">
																<label class="control-label">统计代码</label>
                                                                <div class="controls">
																	<textarea class="span12 m-wrap" rows="5" name="stat"><?php echo ($Config["stat"]); ?></textarea>
																	<span class="help-inline">存放第三方JS统计代码</span>
																</div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">版权信息</label>
                                                                <div class="controls"><div class="span12"><script id="container" show_type="little" name="copyright" type="text/plain" style="height:100px;"><?php echo ($Config["copyright"]); ?></script></div></div>
                                                            </div>
                                                        </div>

                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">注册协议</label>
                                                                <div class="controls"><div class="span12"><script id="container2" show_type="little" name="agb" type="text/plain" style="height:100px;"><?php echo ($Config["agb"]); ?></script></div></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!--<div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">网站二维码</label>
                                                                <div class="controls">
                                                                    <div class="span12 fileupload <?php if(!empty($Config[code_img])): ?>fileupload-exists<?php else: ?>fileupload-new<?php endif; ?>" data-provides="fileupload">
                                                                        <div class="input-append">
                                                                            <div class="uneditable-input">
                                                                                <i class="icon-file fileupload-exists"></i> 
                                                                                <span class="fileupload-filename">
                                                                                    <?php if(!empty($Config[code_img])): echo ($Config[code_img]); endif; ?>
                                                                                </span>
                                                                            </div>
                                                                            <span class="btn btn-file">
                                                                                <span class="fileupload-new">选择图片</span>
                                                                                <span class="fileupload-exists">更改图片</span>
                                                                                <input type="file" class="default" name="code_img" />
                                                                            </span>
                                                                        </div>
                                                                        <div class="fileupload-preview thumbnail">
                                                                            <?php if(!empty($Config[code_img])): ?><img src="../Uploads/<?php echo ($Config["code_img"]); ?>"/><?php endif; ?>
                                                                        </div>
                                                                        <div class="btn-box">
                                                                            <?php if(!empty($Config[code_img])): ?><a class="btn green-stripe mini image_view fileupload-change" href="#show_pic" data-toggle="modal" data-width="auto" rel="../Uploads/<?php echo ($Config["code_img"]); ?>">预览<i class="icon-eye-open"></i></a><?php endif; ?>
                                                                            <div class="clear"></div>
                                                                            <a class="btn red-stripe mini image_delete fileupload-exists" href="javascript:void(0);" data-dismiss="fileupload">删除<i class="icon-trash"></i></a>
                                                                            <input type="hidden" value="0" name="delete_code_img" class="fileupload-delete" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">商品保障</label>
                                                                <div class="controls"><div class="span12"><script id="container4" show_type="little" name="goods_security" type="text/plain" style="height:100px;"><?php echo ($Config["goods_security"]); ?></script></div></div>
                                                            </div>
                                                        </div>
                                                    </div>-->	

                                                    <div class="portlet-title">
                                                        <div class="caption"><i class="icon-wrench"></i>后台系统</div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">系统名称</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="be_logo_name" value="<?php echo ($Config["be_logo_name"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">系统Logo</label>
                                                                <div class="controls">
                                                                    <div class="span12 fileupload <?php if(!empty($Config[be_logo])): ?>fileupload-exists<?php else: ?>fileupload-new<?php endif; ?>" data-provides="fileupload">
                                                                        <div class="input-append">
                                                                            <div class="uneditable-input">
                                                                                <i class="icon-file fileupload-exists"></i> 
                                                                                <span class="fileupload-filename">
                                                                                    <?php if(!empty($Config[be_logo])): echo ($Config[be_logo]); endif; ?>
                                                                                </span>
                                                                            </div>
                                                                            <span class="btn btn-file">
                                                                                <span class="fileupload-new">选择图片</span>
                                                                                <span class="fileupload-exists">更改图片</span>
                                                                                <input type="file" class="default" name="be_logo" />
                                                                            </span>
                                                                        </div>
                                                                        <div class="fileupload-preview thumbnail">
                                                                            <?php if(!empty($Config[be_logo])): ?><img src="Uploads/<?php echo ($Config["be_logo"]); ?>"/><?php endif; ?>
                                                                        </div>
                                                                        <div class="btn-box">
                                                                            <?php if(!empty($Config[be_logo])): ?><a class="btn green-stripe mini image_view fileupload-change" href="#show_pic" data-toggle="modal" data-width="auto" rel="Uploads/<?php echo ($Config["be_logo"]); ?>">预览<i class="icon-eye-open"></i></a><?php endif; ?>
                                                                            <div class="clear"></div>
                                                                            <a class="btn red-stripe mini image_delete fileupload-exists" href="javascript:void(0);" data-dismiss="fileupload">删除<i class="icon-trash"></i></a>
                                                                            <input type="hidden" value="0" name="delete_be_logo" class="fileupload-delete" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_2">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="portlet">
                                                <div class="portlet-body">
                                                    
                                                    <div class="portlet-title">
                                                        <div class="caption"><i class="icon-comments"></i>图片宽度设置</div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">图宽度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="thumb_width" value="<?php echo ($Config["thumb_width"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">图高度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="thumb_height" value="<?php echo ($Config["thumb_height"]); ?>" /></div>
                                                            </div>
                                                        </div>
														<!--<div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">分类图宽度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="cate_thumb_width" value="<?php echo ($Config["cate_thumb_width"]); ?>" /></div>
                                                            </div>
                                                        </div>
														<div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">分类图高度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="cate_thumb_height" value="<?php echo ($Config["cate_thumb_height"]); ?>" /></div>
                                                            </div>
                                                        </div>-->
                                                    </div>

                                                    <!--<div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">商品图宽度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="goods_thumb_width" value="<?php echo ($Config["goods_thumb_width"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">商品图高度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="goods_thumb_height" value="<?php echo ($Config["goods_thumb_height"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">积分商品图宽度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="goodsintegral_thumb_width" value="<?php echo ($Config["goodsintegral_thumb_width"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">积分商品图高度</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="goodsintegral_thumb_height" value="<?php echo ($Config["goodsintegral_thumb_height"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>-->
 
                                                    <!--<div class="portlet-title">
                                                        <div class="caption"><i class="icon-comments"></i>文章数量</div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">最新文章</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="latest_news_num" value="<?php echo ($Config["latest_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">热门文章</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="hit_news_num" value="<?php echo ($Config["hit_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">推荐文章</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="hot_news_num" value="<?php echo ($Config["hot_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">新闻tabBox</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="box_news_num" value="<?php echo ($Config["box_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">相关文章</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="related_news_num" value="<?php echo ($Config["related_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">文本列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_news_num" value="<?php echo ($Config["list_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">图片列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="grid_news_num" value="<?php echo ($Config["grid_news_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">图文列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="imgtxt_news_num" value="<?php echo ($Config["imgtxt_news_num"]); ?>" /></div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>-->
 
                                                    <div class="portlet-title hide">
                                                        <div class="caption"><i class="icon-comments"></i>商品数量设置</div>
                                                    </div>
                                                    <!--<div class="row-fluid">
                                                         <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">最新商品</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="latest_goods_num" value="<?php echo ($Config["latest_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">热门商品</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="hit_goods_num" value="<?php echo ($Config["hit_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div> 
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">商品列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_goods_num" value="<?php echo ($Config["list_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">推荐商品</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="hot_goods_num" value="<?php echo ($Config["hot_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">积分商品</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_goodsintegral_num" value="<?php echo ($Config["list_goodsintegral_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                         <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">商品tabBox</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="box_goods_num" value="<?php echo ($Config["box_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div> 
                                                    </div>-->
                                                     
                                                    <!--<div class="row-fluid">
														<div class="span3">
                                                            <label class="control-label">相关商品</label>
                                                            <div class="controls"><input type="text" class="span12 m-wrap" name="related_goods_num" value="<?php echo ($Config["related_goods_num"]); ?>" /></div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
																<label class="control-label">图片列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="grid_goods_num" value="<?php echo ($Config["grid_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">商品列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_goods_num" value="<?php echo ($Config["list_goods_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
														
                                                    </div>-->
													<!--<div class="row-fluid">
														<div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">推荐评论</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="comment_num" value="<?php echo ($Config["comment_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
														<div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">评论列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_comment_num" value="<?php echo ($Config["list_comment_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
														<div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">评论字数</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="content_comment_num" value="<?php echo ($Config["content_comment_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>-->
                                                    <!--<div class="portlet-title">
                                                        <div class="caption"><i class="icon-stackexchange"></i>订单数量</div>
                                                    </div>
                                                    <div class="row-fluid">
                                                         <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">最近订单</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="latest_orders_num" value="<?php echo ($Config["latest_orders_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">订单列表</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="list_orders_num" value="<?php echo ($Config["list_orders_num"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>-->
													
 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_3">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption"><i class="icon-envelope-alt"></i>基础配置</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">发件人邮箱</label>
                                                                <div class="controls">
                                                                    <div class="input-icon left">
                                                                        <i class="icon-envelope"></i>
                                                                        <input class="span12 m-wrap" type="text" placeholder="邮箱地址" name="mail_from" value="<?php echo ($Config["mail_from"]); ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">发件人姓名</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="mail_fromname" value="<?php echo ($Config["mail_fromname"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="portlet-title">
                                                        <div class="caption"><i class="icon-envelope"></i>邮件服务器配置</div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group mail_mode">
                                                                <label class="control-label">邮件服务</label>
                                                                <div class="controls">
                                                                    <label class="radio"><input type="radio" name="mail_mode" value="0" <?php if($Config['mail_mode'] != 1): ?>checked="checked"<?php endif; ?> />Mail服务</label>
                                                                    <label class="radio"><input type="radio" name="mail_mode" value="1" <?php if($Config['mail_mode'] == 1): ?>checked="checked"<?php endif; ?> />SMTP服务</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">发送地址</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="smtp_host" value="<?php echo ($Config["smtp_host"]); ?>" /><span class="help-inline">发送邮件服务器地址</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">服务器端口</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="smtp_port" value="<?php echo ($Config["smtp_port"]); ?>" /><span class="help-inline">邮件服务器默认端口为：25(Port)</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">发送帐号</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="smtp_username" value="<?php echo ($Config["smtp_username"]); ?>" /><span class="help-inline">邮件发送账号</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">账号密码</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="smtp_password" value="<?php echo ($Config["smtp_password"]); ?>" /><span class="help-inline">发送邮箱的密码</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="portlet-title">
                                                        <div class="caption"><i class="icon-envelope"></i>邮件发送功能测试</div>
                                                    </div>
                                                    <div class="row-fluid testEmail">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">邮件内容</label>
                                                                <div class="controls"><textarea class="span12 m-wrap" rows="3" id="smtp_testcontent" name="smtp_testcontent" placeholder="邮件内容不能为空..."></textarea></div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">接收账号</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span9 m-wrap" id="smtp_testemail" name="smtp_testemail" placeholder="您有权登录的邮箱账号..."/>
                                                                    <input type="button" class="btn green pull-left span3 subBtn" id="testEmailButton" value="点击发送" data-url="">
                                                                    <span class="help-inline warning-default">如果邮件服务器配置已更改，请保存后再测试</span>
                                                                    <span class="help-inline warning-change" style="display:none;">请按要求将以上信息填写完整</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="portlet-title">
                                                        <div class="caption"><i class="icon-envelope-alt"></i>邮件模板配置</div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">后台忘密码</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_forgot_be" value="1" <?php if($Config['email_forgot_be'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_forgot_be'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">前台忘密码</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_forgot_fe" value="1" <?php if($Config['email_forgot_fe'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_forgot_fe'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">会员注册</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_register" value="1" <?php if($Config['email_register'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_register'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">订单已下单</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_orders_state0" value="1" <?php if($Config['email_orders_state0'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_orders_state0'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">订单已支付</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_orders_state1" value="1" <?php if($Config['email_orders_state1'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_orders_state1'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span3">
                                                            <div class="control-group">
                                                                <label class="control-label">订单已发货</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button" style="float:left;">
                                                                        <input type="checkbox" class="toggle" name="email_orders_state2" value="1" <?php if($Config['email_orders_state2'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <a style="float:left;padding:4px 3px;margin:4px 0 0 1px;height:17px;line-height:17px;" class="btn mini green-stripe email_setting" href="javascript:;" ajax="<?php echo U('Index/config', array('key'=>'email_orders_state2'));?>">设置模板</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_4">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption"><i class="icon-comments"></i>短信设置</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">网关地址</label>
                                                                <div class="controls"><input type="text" class="span12 m-wrap" name="sms_url" value="<?php echo ($Config["sms_url"]); ?>" /></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">序列号</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="sms_serial" value="<?php echo ($Config["sms_serial"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">密码</label>
                                                                <div class="controls">
                                                                    <input type="test" class="span6 m-wrap" name="sms_pwd" value="<?php echo ($Config["sms_pwd"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                     
                                                </div>
                                                <div class="portlet-title">
                                                    <div class="caption"><i class="icon-comments"></i>短信发送功能测试</div>
                                                </div>
                                                <div class="row-fluid testSms">
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">短信内容</label>
                                                            <div class="controls"><textarea class="span12 m-wrap" rows="3" id="sms_testcontent" name="sms_testcontent" placeholder="短信内容，不能包含敏感词..."></textarea></div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">
                                                        <div class="control-group">
                                                            <label class="control-label">接收号码</label>
                                                            <div class="controls">
                                                                <input type="text" class="span9 m-wrap" id="sms_testphone" name="sms_testphone" placeholder="您有权查看的手机号码..."/>
                                                                <input type="button" class="btn green pull-left span3 subBtn" id="testSmsButton" value="点击发送" data-url="">
                                                                <span class="help-inline warning-default">如果短信配置已更改，请保存后再测试</span>
                                                                <span class="help-inline warning-change" style="display:none;">请按要求将以上信息填写完整</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_5">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="portlet">
                                                <div class="portlet-title">
                                                    <div class="caption"><i class="icon-comments"></i>附件</div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="row-fluid">
                                                        <!--<div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">订单号前缀</label>
                                                                <div class="controls"><input type="text" class="span6 m-wrap" name="order_prefix" value="<?php echo ($Config["order_prefix"]); ?>" /></div>
                                                            </div>
                                                        </div>-->
                                                         
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">切换主题</label>
                                                                <div class="controls">
                                                                    <select class="span6 m-wrap" tabindex="-1" name="theme">
                                                                        <?php if(is_array($theme_list)): $i = 0; $__LIST__ = $theme_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($Config[theme]) == $vo): ?><option value="<?php echo ($vo); ?>" selected="selected"><?php echo ($vo); ?></option>
                                                                                <?php else: ?>
                                                                                <option value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                                    </select>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row-fluid">
														<div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">发帖审核</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button">
                                                                        <input type="checkbox" class="toggle" name="is_check" value="1" <?php if($Config['is_check'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <span class="help-inline">如果开启发帖审核，就需要管理员手动审核通过</span>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                         <!--<div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">积分比例</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span6 m-wrap" name="jifen_rate" value="<?php echo ($Config["jifen_rate"]); ?>" />
                                                                    <span class="help-inline"></span>
                                                                </div>
                                                            </div>
                                                        </div>--> 
                                                    </div>
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">逻辑删除</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button">
                                                                        <input type="checkbox" class="toggle" name="is_del" value="1" <?php if($Config['is_del'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <span class="help-inline">是否开启数据逻辑删除，系统默认物理删除</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">伪静态</label>
                                                                <div class="controls">
                                                                    <div class="success-toggle-button">
                                                                        <input type="checkbox" class="toggle" name="mod_rewrite" value="1" <?php if($Config['mod_rewrite'] == 1): ?>checked="checked"<?php endif; ?> />
                                                                    </div>
                                                                    <span class="help-inline">是否开启伪静态</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_6">
									<div class="portlet-title">
                                       <div class="caption"><i class="icon-envelope-alt"></i>QQ登录</div>
                                   	</div>
									<div class="portlet-body">
                                                    <div class="row-fluid">
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">腾讯App Key</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="qq_app_key" value="<?php echo ($Config["qq_app_key"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="span6">
                                                            <div class="control-group">
                                                                <label class="control-label">腾讯App Secret</label>
                                                                <div class="controls">
                                                                    <input type="text" class="span12 m-wrap" name="qq_app_secret" value="<?php echo ($Config["qq_app_secret"]); ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="portlet-title">
                                       <div class="caption"><i class="icon-envelope-alt"></i>新浪微博登录</div>
                                   	</div>
									<div class="portlet-body">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">微博App Key</label>
                                                    <div class="controls">
                                                        <input type="text" class="span12 m-wrap" name="weibo_app_key" value="<?php echo ($Config["weibo_app_key"]); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">微博App Secret</label>
                                                    <div class="controls">
                                                        <input type="text" class="span12 m-wrap" name="weibo_app_secret" value="<?php echo ($Config["weibo_app_secret"]); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="portlet-title">
                                       <div class="caption"><i class="icon-envelope-alt"></i>微信登录</div>
                                   	</div>
									<div class="portlet-body">
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">微信App Key</label>
                                                    <div class="controls">
                                                        <input type="text" class="span12 m-wrap" name="weixin_app_key" value="<?php echo ($Config["weixin_app_key"]); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="span6">
                                                <div class="control-group">
                                                    <label class="control-label">微信App Secret</label>
                                                    <div class="controls">
                                                        <input type="text" class="span12 m-wrap" name="weixin_app_secret" value="<?php echo ($Config["weixin_app_secret"]); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane row-fluid" id="tab_1_7">
								<div class="portlet-title">
                                   <div class="caption"><i class="icon-envelope-alt"></i>线下支付</div>
                               	</div>
                                <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">启用状态</label>
                                                <div class="controls">
                                                    <div class="success-toggle-button">
                                                        <input type="checkbox" class="toggle" name="enable_offline_pay" value="1" <?php if($Config['enable_offline_pay'] == 1): ?>checked="checked"<?php endif; ?> />
                                                    </div>
                                                    <span class="help-inline">是否启用线下支付</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row-fluid">
                                    	<div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">支付说明</label>
												<div class="controls"><textarea class="span6 m-wrap" rows="3" name="offline_pay"><?php echo ($Config["offline_pay"]); ?></textarea><br><span class="help-inline">请填写线下付款的银行账号信息</span></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                <!--<div class="portlet-title">
                                   <div class="caption"><i class="icon-envelope-alt"></i>支付宝接口</div>
                               	</div>
                                <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">启用状态</label>
                                                <div class="controls">
                                                    <div class="success-toggle-button">
                                                        <input type="checkbox" class="toggle" name="enable_alipay" value="1" <?php if($Config['enable_alipay'] == 1): ?>checked="checked"<?php endif; ?> />
                                                    </div>
                                                    <span class="help-inline">是否启用支付宝担保交易支付</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                    <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">接口类型</label>
                                                <div class="controls">
                                                    <select class="span6 m-wrap" tabindex="-1" name="apply_type" >
                                                        <?php if(is_array($apply_types)): $i = 0; $__LIST__ = $apply_types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($Config['apply_type']) == $vo[0]): ?><option value="<?php echo ($vo[0]); ?>" selected="selected"><?php echo ($vo[1]); ?></option>
											                    <?php else: ?>
											                    <option value="<?php echo ($vo[0]); ?>"><?php echo ($vo[1]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                                     </select>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label">支付账户</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="apply_user" value="<?php echo ($Config["apply_user"]); ?>" />
                                            	<span class="help-inline">支付宝持有人</span>
                                            </div>
                                        </div>
	                                    </div>
                                    </div>
                                     <div class="row-fluid">
                                     <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">Key</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="apply_key" value="<?php echo ($Config["apply_key"]); ?>" />
                                            	<span class="help-inline">安全检验码，以数字和字母组成的32位字符</span>
                                            </div>
                                            </div>
                                    </div>
                                     <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">Partner ID</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="apply_partner" value="<?php echo ($Config["apply_partner"]); ?>" />
                                            	<span class="help-inline">合作身份者id，以2088开头的16位纯数字</span>
                                            </div>
                                        </div>
                                    </div>
                                     </div>
                                     
                                    <div class="row-fluid">
                                    <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">标题</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="apply_title" value="<?php echo ($Config["apply_title"]); ?>" />
                                            	<span class="help-inline">前台显示的标题</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">说明</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="apply_remark" value="<?php echo ($Config["apply_remark"]); ?>" />
                                            	<span class="help-inline">前台显示的说明</span>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row-fluid">
                                    <div class="span6">
                                           <div class="control-group">
                                               <label class="control-label">图标</label>
                                               <div class="controls">
                                                   <div class="span12 fileupload <?php if(!empty($Config[be_apply_logo])): ?>fileupload-exists<?php else: ?>fileupload-new<?php endif; ?>" data-provides="fileupload">
                                                       <div class="input-append">
                                                           <div class="uneditable-input">
                                                               <i class="icon-file fileupload-exists"></i> 
                                                               <span class="fileupload-filename">
                                                                   <?php if(!empty($Config[be_apply_logo])): echo ($Config[be_apply_logo]); endif; ?>
                                                               </span>
                                                           </div>
                                                           <span class="btn btn-file">
                                                               <span class="fileupload-new">选择图片</span>
                                                               <span class="fileupload-exists">更改图片</span>
                                                               <input type="file" class="default" name="be_apply_logo" />
                                                           </span>
                                                       </div>
                                                       <div class="fileupload-preview thumbnail">
                                                           <?php if(!empty($Config[be_apply_logo])): ?><img src="Uploads/<?php echo ($Config["be_apply_logo"]); ?>"/><?php endif; ?>
                                                       </div>
                                                       <div class="btn-box">
                                                           <?php if(!empty($Config[be_apply_logo])): ?><a class="btn green-stripe mini image_view fileupload-change" href="#show_pic" data-toggle="modal" data-width="auto" rel="Uploads/<?php echo ($Config["be_apply_logo"]); ?>">预览 <i class="icon-eye-open"></i></a><?php endif; ?>
                                                           <div class="clear"></div>
                                                           <a class="btn red-stripe mini image_delete fileupload-exists" href="javascript:void(0);" data-dismiss="fileupload">删除 <i class="icon-trash"></i></a>
                                                           <input type="hidden" value="0" name="delete_be_apply_logo" class="fileupload-delete" />
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                    </div>-->
                                     <div class="portlet-title">
                                   <div class="caption"><i class="icon-envelope-alt"></i>微信接口</div>
                               	</div>
                                <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">启用状态</label>
                                                <div class="controls">
                                                    <div class="success-toggle-button">
                                                        <input type="checkbox" class="toggle" name="enable_wx" value="1" <?php if($Config['enable_wx'] == 1): ?>checked="checked"<?php endif; ?> />
                                                    </div>
                                                    <span class="help-inline">是否启用微信支付</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">MCHID</label>
                                                <div class="controls">
                                                    <input type="text" class="span12 m-wrap" name="merchant_number" value="<?php echo ($Config["merchant_number"]); ?>" />
                                                    <span class="help-inline">商户号</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">KEY</label>
                                                <div class="controls">
                                                    <input type="text" class="span12 m-wrap" name="wx_key" value="<?php echo ($Config["wx_key"]); ?>" />
                                                	<span class="help-inline">商户支付的密钥</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label class="control-label">APPID</label>
                                                <div class="controls">
                                                    <input type="text" class="span12 m-wrap" name="wx_appid" value="<?php echo ($Config["wx_appid"]); ?>" />
                                                	<span class="help-inline">绑定支付的APPID</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                            <label class="control-label">APPSECRET</label>
                                                <div class="controls">
                                            	<input type="text" class="span12 m-wrap" name="wx_secert" value="<?php echo ($Config["wx_secert"]); ?>" />
                                                <span class="help-inline">公众帐号的secert</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                    <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">标题</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="wx_title" value="<?php echo ($Config["wx_title"]); ?>" />
                                            	<span class="help-inline">前台显示的标题</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
	                                        <div class="control-group">
                                            <label class="control-label">说明</label>
                                            <div class="controls">
                                                <input type="text" class="span12 m-wrap" name="wx_remark" value="<?php echo ($Config["wx_remark"]); ?>" />
                                            	<span class="help-inline">前台显示的说明</span>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row-fluid">
                                    <div class="span6">
                                           <div class="control-group">
                                               <label class="control-label">图标</label>
                                               <div class="controls">
                                                   <div class="span12 fileupload <?php if(!empty($Config[be_wx_logo])): ?>fileupload-exists<?php else: ?>fileupload-new<?php endif; ?>" data-provides="fileupload">
                                                       <div class="input-append">
                                                           <div class="uneditable-input">
                                                               <i class="icon-file fileupload-exists"></i> 
                                                               <span class="fileupload-filename">
                                                                   <?php if(!empty($Config[be_wx_logo])): echo ($Config[be_wx_logo]); endif; ?>
                                                               </span>
                                                           </div>
                                                           <span class="btn btn-file">
                                                               <span class="fileupload-new">选择图片</span>
                                                               <span class="fileupload-exists">更改图片</span>
                                                               <input type="file" class="default" name="be_wx_logo" />
                                                           </span>
                                                       </div>
                                                       <div class="fileupload-preview thumbnail">
                                                           <?php if(!empty($Config[be_wx_logo])): ?><img src="Uploads/<?php echo ($Config["be_wx_logo"]); ?>"/><?php endif; ?>
                                                       </div>
                                                       <div class="btn-box">
                                                           <?php if(!empty($Config[be_wx_logo])): ?><a class="btn green-stripe mini image_view fileupload-change" href="#show_pic" data-toggle="modal" data-width="auto" rel="Uploads/<?php echo ($Config["be_wx_logo"]); ?>">预览 <i class="icon-eye-open"></i></a><?php endif; ?>
                                                           <div class="clear"></div>
                                                           <a class="btn red-stripe mini image_delete fileupload-exists" href="javascript:void(0);" data-dismiss="fileupload">删除 <i class="icon-trash"></i></a>
                                                           <input type="hidden" value="0" name="delete_be_wx_logo" class="fileupload-delete" />
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--END TABS-->
                        <div class="form-actions">
                            <button type="submit" class="btn blue">保存数据 <i class="icon-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->

<div id="email_setting" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="760"></div>

<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="footer-inner">
        Powered by HQCMS &copy; <?php echo date('Y');?>.
    </div>
    <div class="footer-tools">
        <span class="go-top">
            <i class="icon-angle-up"></i>
        </span>
    </div>
</div>
<!-- END FOOTER -->

<!-- BEGIN ALERT -->
<div id="custom_alert" class="modal hide fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="modal-body">
        <p id="error_content"></p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn green">确 定</button>
    </div>
</div>
<!-- END ALERT -->

<!-- BEGIN CONFIRM -->
<div id="custom_confirm" class="modal hide fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="modal-body"></div>
    <div class="modal-footer">
        <div class="saved">
            <button type="button" data-dismiss="modal" class="btn" id="custom_confirm_cancel">取消</button>
            <button type="button" data-dismiss="modal" class="btn green" id="custom_confirm_ok">确定</button>
        </div>
    </div>
</div>
<!-- END CONFIRM -->

<!-- BEGIN SHOWPIC -->
<div id="show_pic" data-backdrop="static" class="modal hide fade show_pic_modal">
    <div class="image_auto">
        <div class="modal-header">
            <a href="javascript:void(0);" class="closed pull-right" data-dismiss="modal" aria-hidden="true"></a>
        </div>
        <div class="modal-body"><img src="" /></div>
    </div>
</div>
<!-- END SHOWPIC -->

<div id="ajax-modal" class="modal hide fade modal-common" tabindex="-1" data-backdrop="static" ></div>

<!-- Public BEGIN -->
<script src="/Application/HQ/View/default/Public/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/Application/HQ/View/default/Public/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="/Application/HQ/View/default/Public/plugins/excanvas.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/respond.min.js" type="text/javascript"></script>
<![endif]-->
<script src="/Application/HQ/View/default/Public/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery.cookie.min.js" type="text/javascript"></script>

<script src="/Application/HQ/View/default/Public/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<script src="./Application/Common/Util/ueditor/ueditor.config.js" type="text/javascript"></script>
<script src="./Application/Common/Util/ueditor/ueditor.all.js" type="text/javascript"></script>
<script src="./Application/Common/Util/ueditor/lang/zh-cn/zh-cn.js" type="text/javascript"></script>

<script src="/Application/HQ/View/default/Public/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js" type="text/javascript"><!-- select--></script>
<script src="/Application/HQ/View/default/Public/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/select2/select2.min.js" type="text/javascript"><!-- 下拉框--></script>

<script src="/Application/HQ/View/default/Public/scripts/jquery.form.js" type="text/javascript"><!-- 下拉框--></script>

<!-- toggle_select -->
<script src="/Application/HQ/View/default/Public/plugins/toggleselect/jquery.multi-select.js" type="text/javascript"></script>
<!--inputmask-->
<script src="/Application/HQ/View/default/Public/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<!--add-tags-->
<script src="/Application/HQ/View/default/Public/plugins/jquery-tags-input/jquery.tagsinput.min.js" type="text/javascript"></script>
<!-- Public END -->

<script src="/Application/HQ/View/default/Public/plugins/flot/jquery.flot.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/flot/jquery.flot.resize.js" type="text/javascript"></script>

<script src="/Application/HQ/View/default/Public/plugins/jquery.pulsate.min.js" type="text/javascript"></script>

<script src="/Application/HQ/View/default/Public/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"
type="text/javascript"></script>

<script src="/Application/HQ/View/default/Public/scripts/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/scripts/jquery-ui-timepicker-zh-CN.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<?php if(!empty($Menu)): ?><!-- BEGIN Menu -->
    <script src="/Application/HQ/View/default/Public/plugins/jquery-nestable/jquery.nestable.js" type="text/javascript"><!-- 拖动表格--></script>
    <!-- END Menu --><?php endif; ?>

<?php if(!empty($Multiple)): ?><script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/jquery.fileupload-fp.js"></script>
    <script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script><?php endif; ?>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Application/HQ/View/default/Public/scripts/app.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/scripts/common.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
    jQuery(document).ready(function () {
        App.init(); // initlayout and core plugins
        $.fn.modalmanager.defaults.resize = true;
        $.fn.modalmanager.defaults.spinner = '<div class="loading-spinner fade" style="width: 200px; margin-left: -100px;"><img src="/Application/HQ/View/default/Public/img/ajax-modal-loading.gif" align="middle">&nbsp;<span style="font-weight:300; color: #eee; font-size: 18px; font-family:Open Sans;">&nbsp;正在努力加载中...</div>';

    });
</script>
<!-- END JAVASCRIPTS -->


<script type="text/javascript" src="/Application/HQ/View/default/Public/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript">
    function checkServer(){
        var is_remote = $('span[class=checked]').find('input[name=is_remote]').val();
        if(is_remote!=0){
            $('.is_remote').show();
        }else{
            $('.is_remote').hide();
        }
    }
    $(function(){
        checkServer();
        $('input[name=is_remote]').live('click',function(){
            checkServer();
        })
    })
    jQuery(document).ready(function(){
        $('#testSmsButton').click(function(){
            var _this = $('#testSmsButton');
            var phone = $('#sms_testphone').val();
            var content = $('#sms_testcontent').val();
            if(phone==''||content==''){
                $('.testSms .warning-default').hide();
                $('.testSms .warning-change').html('请按要求将以上信息填写完整').css('color','red');
                $('.testSms .warning-change').show();
            }else{
                var sendUrl = _this.attr('data-url');
                $.ajax({
                    type: "POST",
                    url: sendUrl,
                    data: {'ajax':'testSms','phone':phone,'content':content},
                    dataType:"json",
                    success: function(data){
                        if(data.status==1){
                            var tmp = '发送成功！请查看'+phone+'确认短信...';
                            $('.testSms .warning-default').hide();
                            $('.testSms .warning-change').html(tmp).css('color','green');
                            $('.testSms .warning-change').show();
                        }else{
                            $('.testSms .warning-default').hide();
                            $('.testSms .warning-change').html('短信发送失败，请检查短信配置').css('color','red');
                            $('.testSms .warning-change').show();
                        }
                        _this.attr("disabled", false);
                        _this.val("点击发送"); 
                    },
                    beforeSend: function(){
                        var tmp = '正在发送给'+phone+'...';
                        $('.testSms .warning-default').hide();
                        $('.testSms .warning-change').html(tmp).css('color','green');
                        $('.testSms .warning-change').show();
                        _this.attr("disabled", true);
                        _this.val("正在发送..."); 
                    }
                });
            }
        });
        $('#testEmailButton').click(function(){
            var _this = $('#testEmailButton');
            var email = $('#smtp_testemail').val();
            var content = $('#smtp_testcontent').val();
            if(email==''||content==''){
                $('.testEmail .warning-default').hide();
                $('.testEmail .warning-change').html('请按要求将以上信息填写完整').css('color','red');
                $('.testEmail .warning-change').show();
            }else{
                var sendUrl = _this.attr('data-url');
                $.ajax({
                    type: "POST",
                    url: sendUrl,
                    data: {'ajax':'testEmail','email':email,'content':content},
                    dataType:"json",
                    success: function(data){
                        if(data.status==1){
                            var tmp = '发送成功！请进入'+email+'确认邮件...';
                            $('.testEmail .warning-default').hide();
                            $('.testEmail .warning-change').html(tmp).css('color','green');
                            $('.testEmail .warning-change').show();
                        }else{
                            $('.testEmail .warning-default').hide();
                            $('.testEmail .warning-change').html('邮件发送失败，请检查邮件服务器配置').css('color','red');
                            $('.testEmail .warning-change').show();
                        }
                        _this.attr("disabled", false);
                        _this.val("点击发送"); 
                    },
                    beforeSend: function(){
                        var tmp = '正在发送给'+email+'...';
                        $('.testEmail .warning-default').hide();
                        $('.testEmail .warning-change').html(tmp).css('color','green');
                        $('.testEmail .warning-change').show();
                        _this.attr("disabled", true);
                        _this.val("正在发送..."); 
                    }
                });
            }
        });
                                                    
        //邮件模板设置
        $('.email_setting').on('click', function(){
            // create the backdrop and wait for next modal to be triggered
            $('body').modalmanager('loading');
            $.post($(this).attr('ajax'), {ajax:'email_template_show'}, function(data){
                $('#email_setting').html(data).modal();
            });
            return false;
        });
        //ajax保存邮件模板
        $('#email_setting').on('click', '.email_save', function(){
            var formObj = $(this).parents('#email_setting').find('form');
            $.post(formObj.attr('action'), 'ajax=email_template_save&'+formObj.serialize(), function(data){
                $('#email_setting').modal('toggle');
            });
        });
    });
</script>
</body>
<!-- END BODY -->
</html>