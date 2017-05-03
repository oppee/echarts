<?php
return array(
    'URL_MODEL' => 2,
	'DEFAULT_MODULE'     => 'Mobile',
	'MODULE_ALLOW_LIST' => array('Home','Common','HQ','Mobile','Api'),

	/* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'shoutan', // 数据库名
    'DB_USER'   => 'shoutan', // 用户名
    'DB_PWD'    => 'shoutan2016',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'hq_', // 数据库表前缀

	//请根据项目自行更改参数
	'PATH_UPLOAD'   => './Uploads/',  //文件上传路径
	'COOKIE_PREFIX' => 'ST',     //cookie前缀
	'DEFAULT_LANG' 		    => 'zh-cn', // 默认语言
	'LANG_LIST'             => 'zh-cn,en-us', // 允许切换的语言列表 用逗号分隔
	'LANG_SWITCH_ON'        => true,   // 开启语言包功能
	'LANG_AUTO_DETECT'      => true, // 自动侦测语言 开启多语言功能后有效
	'VAR_LANGUAGE'    	 	=> 'l', // 默认语言切换变量
	'ERROR_PAGE' 			=> 'Public:error', // 错误定向页面
	'URL_CASE_INSENSITIVE'  => false,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'TMPL_DETECT_THEME'     => true, // 自动侦测模板主题
	'SESSION_AUTO_START'    => false,  //session_start
	//'SHOW_PAGE_TRACE'    	=> true,  //开启调试平台
	'HTML_CACHE_ON'			=>TRUE,   //开启静态缓存

	//拓展配置
	'APP_SUB_DOMAIN_DEPLOY'   =>    1,
	'APP_SUB_DOMAIN_RULES'    =>    array(
		//'admin'=>array('HQ/'),
		//'m'=>array('Mobile/'),
		//'ashop'=>array('Api/Api'),
	),
);