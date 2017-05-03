<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js"> <!--<![endif]-->
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
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div id="portlet-config" class="modal hide">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button"></button>
                <h3>Widget Settings</h3>
            </div>
            <div class="modal-body">
                Widget settings form goes here
            </div>
        </div>
        <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
                <div class="span12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo ($ParentPageName); ?>
                        <small><?php echo (CONTROLLER_NAME); ?></small>
                    </h3>
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home"></i>
                            <a href="<?php echo U('Index/index');?>">管理中心</a>

                        </li>

                        <li class="pull-right no-text-shadow">
                            <div id="dashboard-report-range"
                                 class="dashboard-date-range tooltips no-tooltip-on-touch-device responsive"
                                 data-tablet="" data-desktop="tooltips" data-placement="top"
                                 data-original-title="Change dashboard date range">
                                <i class="icon-calendar"></i>
                                <span></span>
                                <i class="icon-angle-down"></i>
                            </div>
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <div id="dashboard">
                <!-- BEGIN DASHBOARD STATS -->
                <div class="row-fluid">
                    <div class="span6 responsive fix-offset" data-tablet="span6" data-desktop="span6">
                        <div class="dashboard-stat purple">
                            <div class="visual">
                                <i class="icon-comments"></i>
                            </div>
                            <div class="details">
                                <div class="number"><?php echo ((isset($count_today_message) && ($count_today_message !== ""))?($count_today_message):0); ?></div>
                                <div class="desc">今日留言</div>
                            </div>
                            <a class="more" href="<?php echo U('Message/index');?>">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
                        </div>
                    </div>
                    <div class="span6 responsive" data-tablet="span6" data-desktop="span6">
                        <div class="dashboard-stat green">
                            <div class="visual"><i class="icon-shopping-cart"></i></div>
                            <div class="details">
                                <div class="number"><?php echo ((isset($count_goods) && ($count_goods !== ""))?($count_goods):0); ?></div>
                                <div class="desc">帖子总数</div>
                            </div>
                            <a class="more" href="<?php echo U('topic/index');?>">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
                        </div>
                    </div>
                    <div class="span6 responsive fix-offset" data-tablet="span6" data-desktop="span6">
                        <div class="dashboard-stat blue">
                            <div class="visual">
                                <i class="icon-user"></i>
                            </div>
                            <div class="details">
                                <div class="number"><?php echo ((isset($count_user) && ($count_user !== ""))?($count_user):0); ?></div>
                                <div class="desc">会员总数</div>
                            </div>
                            <a class="more" href="<?php echo U('User/index');?>">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
                        </div>
                    </div>
                    <div class="span6 responsive" data-tablet="span6" data-desktop="span6">
                        <div class="dashboard-stat yellow">
                            <div class="visual">
                                <i class="icon-globe"></i>
                            </div>
                            <div class="details">
                                <div class="number"><?php echo ((isset($count_news) && ($count_news !== ""))?($count_news):0); ?></div>
                                <div class="desc">文章总数</div>
                            </div>
                            <a class="more" href="<?php echo U('News/index');?>">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
                        </div>
                    </div>
                    <!--<div class="span6 responsive fix-offset" data-tablet="span6" data-desktop="span6">
                        <div class="dashboard-stat blue">
                            <div class="visual">
                                <i class="icon-jpy"></i>
                            </div>
                            <div class="details">
                                <div class="number"><?php echo ((isset($count_today_orders) && ($count_today_orders !== ""))?($count_today_orders):0); ?></div>
                                <div class="desc">今日订单</div>
                            </div>
                            <a class="more" href="<?php echo U('Orders/index');?>">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
                        </div>
                    </div>-->
                </div>
                <div class="clearfix"></div>
               

            </div>
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->
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

<script src="/Application/HQ/View/default/Public/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
<script src="/Application/HQ/View/default/Public/scripts/index.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        Index.init();
        //Index.initJQVMAP(); // init index page's custom scripts
        //Index.initCalendar(); // init index page's custom scripts
        //Index.initCharts(); // init index page's custom scripts
        //Index.initChat();
        //Index.initMiniCharts();
        //Index.initDashboardDaterange();
        //Index.initIntro();
    });
</script>
</body>
<!-- END BODY -->
</html>