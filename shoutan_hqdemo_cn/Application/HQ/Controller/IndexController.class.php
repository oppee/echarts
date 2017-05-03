<?php
namespace HQ\Controller;

class IndexController extends BaseController {

    protected $fixMenu = array(); //array(1,2,3,4,5); //固定菜单，不可编辑
    protected $sorting = 1; //用于更新菜单排序

    public function index() {

        $this->assign('Map', true); //定义页面是否需要MAP所需要的文件
        //alias后台常规页面
        if (I('get.alias')) {
            $this->assign('info', M('Menu')->where(array('deleted' => 0, 'alias' => I('get.alias')))->find());
            $this->display('page');
            exit;
        }
				

		$count_today_groupbuy = M('Groupbuy')->where("deleted=0 and hidden=0 and FROM_UNIXTIME(crdate,'%Y%m%d')='".date('Ymd')."'")->count();		//今日团购
		$this->assign('count_today_groupbuy', $count_today_groupbuy);

		$count_today_orders = M('Orders')->where("deleted=0 and hidden=0 and FROM_UNIXTIME(crdate,'%Y%m%d')='".date('Ymd')."'")->count();		//今日订单
		$this->assign('count_today_orders', $count_today_orders);

		$count_today_message = M('Message')->where("deleted=0 and hidden=0 and FROM_UNIXTIME(crdate,'%Y%m%d')='".date('Ymd')."'")->count();		//今日留言
		$this->assign('count_today_message', $count_today_message);
				
		
		$count_news = M('News')->where('deleted=0 and hidden=0')->count();				//文章总数
		$this->assign('count_news', $count_news);
		
		$count_user = M('User')->where('deleted=0 and hidden=0')->count();				//会员总数
		$this->assign('count_user', $count_user);
		
		$count_goods = M('Goods')->where('deleted=0 and hidden=0')->count();		//商品总数
		$this->assign('count_goods', $count_goods);
				
		$count_topic = M('topic')->where('deleted=0 and hidden=0')->count();	    //帖子总数
		$this->assign('count_topic',$count_topic);	
				
        $this->display();
    }

    /**
     * 菜单管理模块
     */
    public function menu() {
        //初始化
        import("@.Util.Tree");
        $Menu = D('Menu');
		//筛选条件
		$rootArr1 = $Menu->where("parent_id=0 and app='home'")->select();
		foreach($rootArr1 as $k => $v){
			$rootMenu1[$k]= array($v['id'],$v['id'].'.'.$v['title']);
		}
		$this->assign('rootMenu1',$rootMenu1);
		
		$rootArr2 = $Menu->where("parent_id=0 and app='admin'")->select();
		foreach($rootArr2 as $k => $v){
			$rootMenu2[$k]= array($v['id'],$v['id'].'.'.$v['title']);
		}
		$this->assign('rootMenu2',$rootMenu2);
		
		$filter='';
		if(I('request.rootMenu1')){
			$filter="and id = ".I('request.rootMenu1');
		}elseif(I('request.rootMenu2')){
			$filter="and id = ".I('request.rootMenu2');
		}
        if (I('request.keyword')) {
            $filter .=' and (id LIKE "%' . I('request.keyword') . '%" OR title LIKE "%' . I('request.keyword') . '%")';
        }
		
        $tree = new \Tree();
		
        $this->assign('Menu', true); //定义页面是否需要Menu所需要的文件
        //处理增删除查改
        if (I('request.cmd')) {
            switch (I('request.cmd')) {
                //添加菜单
                case 'menu_save':
                    $id = I('get.id') ? I('get.id') : null;
                    $info = $Menu->where('id = ' . $id)->find();
	                $Menu->where("active=1")->save(array('active'=>''));//进入详细页面，清除所有活动状态
					$pInfo = $Menu->where("id=".$info['parent_id'])->find();//父级详细信息
					$ppInfo = $Menu->where("id=".$pInfo['parent_id'])->find();
					if($id){
	                    $Menu->where("id=".$id." or id=".$info['parent_id']." or id=".$pInfo['parent_id'])->save(array('active'=>1));//当前页面及其父级将被标记为活动状态
					}else{
						$_POST['active'] = 1;
					}
                    if (IS_POST) {
                        $_POST['alias'] = strtolower(trim($_POST['alias']));
                        foreach ($_POST as $key => $val) {
                            //处理POST数据
                            if (is_array($val)) {
                                $_POST[$key] = implode(",", $val);
                            }
                            if (@$this->tca[$key]['required'] && !$val) {
                                $this->error($this->tca[$key]['label'] . '是必填项!');
                            }
                        }

                        if ($id) {
                            $ids = $this->getPathAndClass(I('get.id'), 'menu', true);
                            foreach ($ids as $v) {
                                if (I('post.parent_id') == $v['id']) {
                                    $this->error('所选择的上级分类不能是当前分类或者当前分类的下级分类！');
                                    exit;
                                }
                            }
                        }

                        //删除文件 begin **********************************
                        $path = $this->Config['path_upload'] . 'Menu/';
                        if ($info && $info['image'] && $_POST['delete_image'] == '1') {
                            $this->delInfoFile($path, $info['image']);
                        } else {
                            unset($_POST['image']);
                        }
                        //删除文件 end **********************************
                        //文件上传 begin **********************************
                        if (count($_FILES) && isset($_FILES['image'])) {
                            $upload = $this->uploadFile('image', $path);

                            if (is_array($upload) && $upload['upload']) {
                                $_POST['image'] = $upload['info'];
                            } else {
                                $this->error($upload['info']);
                            }
                        }
                        //文件上传 end **********************************

                        $result = $Menu->create();
                        if (!$result) {
                            $this->error($Menu->getError());
                        } elseif ($Menu->type == 0 && trim($Menu->alias) == '') { //常规页面必须要填写别名
                            $this->error('请填写别名！');
                        } elseif ($Menu->alias && $Menu->where("deleted=0 and id <> " . I('get.id') . " and alias='" . $Menu->alias . "'")->count()) {
                            $this->error('这个别名已经存在了！');
                        } else {
                            $Menu->app = I('request.app');
                            if (!isset($_POST['hidden'])) {
                                $Menu->hidden = 1;
                            }

                            $Menu->position = $_POST['position'] === 0 ? '0' : $_POST['position']; //处理显示位置的数据，要以逗号，存库
                            //$Menu->position = implode(",", $_POST['position']); //处理显示位置的数据，要以逗号，存库
                            //print_r($Menu->position);

                            if ($id) {
                                $Menu->tstamp = time();
                                $Menu->where(array('id' => $id))->save();
                                $message = '编辑成功!';
                            } else {
                                $Menu->crdate = time();
                                $Menu->tstamp = time();
                                $id = $Menu->add();
                                if (trim($_POST['sorting'] == '')) {
                                    $Menu->where(array('id' => $id))->save(array('sorting' => $id));
                                }

                                $message = '添加成功!';
                            }
                            $this->success($message, U('Index/menu', array('app' => I('request.app'))));
                            exit;
                        }
                    }

                    
                    //category tree
                    $news_root = M('news_category')->where('parent_id=0')->select();
                    foreach($news_root as $k => $v){
                        $news_tree[] = array($v['id'],$v['id'].' . '.$v['name']);
                        $news_root2 = M('news_category')->where('parent_id='.$v['id'])->select();
                        foreach($news_root2 as $k2 => $v2){
                            $news_tree[] = array($v2['id'],'&nbsp;&nbsp;├&nbsp;&nbsp;'.$v2['id'].' . '.$v2['name']);
                        }
                    }
                    $goods_root = M('goods_category')->where('parent_id=0')->select();
                    foreach($goods_root as $k => $v){
                        $goods_tree[] = array($v['id'],$v['id'].' . '.$v['name']);
                        $goods_root2 = M('goods_category')->where('parent_id='.$v['id'])->select();
                        foreach($goods_root2 as $k2 => $v2){
                            $goods_tree[] = array($v2['id'],'&nbsp;&nbsp;├&nbsp;&nbsp;'.$v2['id'].' . '.$v2['name']);
                        }
                    }
                    $this->assign('news_tree',$news_tree);
                    $this->assign('goods_tree',$goods_tree);

                    //MenuTree
                    $array = $Menu->where("deleted=0 and app='" . I('request.app') . "'")->select();
                    foreach ($array as $key => $val) {
                        $array[$key]['selected'] = $val['id'] == $info['parent_id'] ? 'selected="selected"' : '';
                    }
                    $tree->init($array);
                    $this->assign('menu_tree', $tree->get_tree(0, "<option value='\$id' \$selected>\$id. \$spacer \$title</option>", $id));
                    $this->assign('menu_info', $info);
                    $position = trim($info['position']) === '' ? array() : explode(",", $info['position']);
                    $this->assign('position', $position);
                    $this->assign('icons', self::getIconList());
                    $this->display('menu_info');
                    break;
                case 'menu_del':
                    $Menu = M('Menu');
                    $id = I('get.id');
                    //删除选定菜单，同时删除其子菜单以及子菜单的子菜单
                    $id_arr = array();
                    $id_child = $Menu->where('parent_id = ' . $id)->field('id')->select();
                    foreach($id_child as $k => $v){
                        $tmp_arr = $Menu->where('parent_id = ' . $v['id'])->field('id')->select();
                        $id_arr[] = $v['id'];
                        foreach($tmp_arr as $k1 => $v1){
                            $id_arr[] = $v1['id'];
                        }
                    }
                    array_unshift($id_arr,$id);
                    $is_del = $this->Config['is_del'];
                    $rs = false;
                    
                    foreach($id_arr as $k => $v){
                        if ($is_del == 0) {
                            $info = $Menu->where('id = ' . $v)->find();
                            if ($info['image']) {
                                $this->delFile($info, 'Menu');
                            }

                            if ($Menu->where('id = ' . $v)->delete()) {
                                $rs = true;
                            }
                        } else {
                            if ($Menu->where('id = ' . $v)->save(array('deleted' => 1))) {
                                $rs = true;
                            }
                        }
                    }

                    if ($rs) {
                        $this->success('删除成功!', U('index/menu', 'app=' . $info['app']));
                    } else {
                        $this->error('删除失败，请稍后再试！');
                    }
                    break;
                case 'menu_sort':
                    if (isset($_POST['sort'])) {
                        $this->sortMenuTree(0, $_POST['sort']);
                    }
                    echo 1;
                    break;
                default:
                    break;
            }
            exit;
        }
        //处理ajax传输数据并返回
        if(I('cateInfo')){
            list($cate,$cate_id) = explode('_',I('cateInfo'));
            $Obj = M($cate.'Category')->field('name,alias,parent_id')->find($cate_id);
            if($cate=='news'){
                $Obj['type'] =  M($cate.'Category')->where(array('id'=>$cate_id))->getField('type');
            }
            $Obj['model'] = $cate;
            $Obj['action'] = 'lists';
            $Obj['param'] = 'id='.$cate_id;
            $this->ajaxReturn(array('data'=>$Obj,'status'=>1));
        }

        //显示菜单列表
		$this->assign('adminMenuTree', $this->getMenuTree(0, 'Admin',$filter));
		$this->assign('homeMenuTree', $this->getMenuTree(0, 'Home',$filter));
        $this->display();
    }
    
    /**
     * 站点配置保存操作
     */
    public function config() {
        if (IS_AJAX) {
            switch (I('request.ajax')) {
                case 'email_template_show':
                    $this->assign('info', getEmailConfig(I('request.key')));
                    $this->display('email');
                    break;
                case 'email_template_save':
                    $Email = M('Email');
                    $Email->create();
                    $Email->where(array('key' => I('request.key')))->save();
                    break;
                case 'testSms':
                    //测试短信功能是否能正常使用
                    $phone = $this->_post('phone');
                    $content = $this->_post('content');
                    if($phone&&$content){
                        $res = $this->sendSMS(array($phone), $content);
                        if($res){
                            $this->ajaxReturn(array('status'=>1),'json');
                        }else{
                            $this->ajaxReturn(array('status'=>0),'json');
                        }
                    }
                    break;
                case 'testEmail':
                    //测试邮件功能是否能正常使用
                    $email = $this->_post('email');
                    $title = "恭喜您，邮件服务器配置正确！";
                    $content = $this->_post('content') ? $this->_post('content') : "当您收到这个邮件，已经证明您的邮件服务可以正常使用！";
                    if($email&&$title){
                        $res = sendMail($title, $content, $email);
                        if($res){
                            $this->ajaxReturn(array('status'=>1),'json');
                        }else{
                            $this->ajaxReturn(array('status'=>0),'json');
                        }
                    }
                    break;
                default:
                    $Config = M('Config');
                    //处理上传文件, 包括logo 和网站二维码图片
                    //$path = 'Uploads/';
                    //统一上传至根目录Uploads
                    $path = $this->Config['path_upload'];
					$be_path = 'Uploads/';
					
                    if ($_POST['delete_be_logo'] == '1' && ($be_logo = $this->Config['be_logo'])) {
                        $this->delInfoFile($be_path, $be_logo);
                    } else {
                        unset($_POST['be_logo']);
                    }

                    if (count($_FILES) && isset($_FILES['be_logo'])) {
                        $upload = $this->uploadFile('be_logo', $be_path);

                        if (is_array($upload) && $upload['upload']) {
                            $_POST['be_logo'] = $upload['info'];
                        } else {
                            $this->error($upload['info']);
                        }
                    }
                    $_POST['cookie_prefix'] = $_POST['order_prefix'];
                    //微信支付logo
                    if ($_POST['delete_be_wx_logo'] == '1' && ($be_logo = $this->Config['be_wx_logo'])) {
                    	$this->delInfoFile($be_path, $be_logo);
                    } else {
                    	unset($_POST['be_wx_logo']);
                    }
                    
                    if (count($_FILES) && isset($_FILES['be_wx_logo'])) {
                    	$upload = $this->uploadFile('be_wx_logo', $be_path);
                    
                    	if (is_array($upload) && $upload['upload']) {
                    		$_POST['be_wx_logo'] = $upload['info'];
                    	} else {
                    		$this->error($upload['info']);
                    	}
                    }
                    //支付宝支付logo
                    if ($_POST['delete_be_apply_logo'] == '1' && ($be_logo = $this->Config['be_apply_logo'])) {
                    	$this->delInfoFile($be_path, $be_logo);
                    } else {
                    	unset($_POST['be_apply_logo']);
                    }
                    
                    if (count($_FILES) && isset($_FILES['be_apply_logo'])) {
                    	$upload = $this->uploadFile('be_apply_logo', $be_path);
                    
                    	if (is_array($upload) && $upload['upload']) {
                    		$_POST['be_apply_logo'] = $upload['info'];
                    	} else {
                    		$this->error($upload['info']);
                    	}
                    }
                    if ($_POST['delete_code_img'] == '1' && ($code_img = $this->Config['code_img'])) {
                        $this->delInfoFile($path, $code_img);
                    } else {
                        unset($_POST['code_img']);
                    }

                    if (count($_FILES) && isset($_FILES['code_img'])) {
                        $upload = $this->uploadFile('code_img', $path);

                        if (is_array($upload) && $upload['upload']) {
                            $_POST['code_img'] = $upload['info'];
                        } else {
                            $this->error($upload['info']);
                        }
                    }

                    //处理配置数据
                    $toggleFieldArray = array('email_forgot_be', 'email_forgot_fe', 'email_register', 'email_message', 'is_del', 'mod_rewrite', 'is_notice', 'comment_condition', 'is_duoshuo','enable_alipay','enable_alipay_quick','enable_wx','is_check');
                    foreach ($toggleFieldArray as $field) {
                        if (!isset($_POST[$field])) {
                            $_POST[$field] = 0;
                        }
                    }
					
					if ($_POST['notice'] && ini_get("magic_quotes_gpc") == "1") {
                        $_POST['notice'] = stripslashes($_POST['notice']);
                    }
					
					if ($_POST['agb'] && ini_get("magic_quotes_gpc") == "1") {
                        $_POST['agb'] = stripslashes($_POST['agb']);
                    }
					
                    if ($_POST['copyright'] && ini_get("magic_quotes_gpc") == "1") {
                        $_POST['copyright'] = stripslashes($_POST['copyright']);
                    }

                    if ($_POST['stat'] && ini_get("magic_quotes_gpc") == "1") {
                        $_POST['stat'] = stripslashes($_POST['stat']);
                    }
					
					if ($_POST['statement'] && ini_get("magic_quotes_gpc") == "1") {
                        $_POST['statement'] = stripslashes($_POST['statement']);
                    }

                    foreach ($_POST as $key => $val) {

                        $num = $Config->where(array('key' => $key))->count(); //根据KEY来判断数据库是否有这条记录！
                        if ($num != 0) {
                            $Config->where(array('key' => $key))->save(array('value' => $val));
                        } else {
                            $Config->add(array('key' => $key, 'value' => $val));
                        }
                    }
                    //echo $Config->getLastSql();
                    $this->success('保存成功!', U('Index/config'));
                    break;
            }
            exit;
        }

        $theme_list = array();
        $theme_path = './Application/Mobile/View/';

        if ($handle = opendir($theme_path)) {
            while (false !== ($file = readdir($handle))) {
                if (strpos($file, '.') === false) {
                    $theme_list[] = $file;
                }
            }
        }
        closedir($handler);

        $this->assign('theme_list', $theme_list);
        //支付宝接口类型
        $apply_types = array(array(alipaydirect, '支付宝即时到账'), array(alipayescow, '支付宝担保交易'), array(alipaydualfun, '支付宝双接口'));
        $this->assign('apply_types',$apply_types);
        $this->display();
    }

    /**
     * 生成nestable类型可手动树状菜单列表
     */
    private function getMenuTree($parent_id, $app, $filter='') {
        $tree = '';
		$where = "deleted=0 and parent_id={$parent_id} and app='{$app}'";
		if($filter!=''){
			$where .=$filter;
		}
        $result = D('Menu')->where($where)->order("sorting asc")->select();
			
        if ($result) {
            $tree .= '<ol class="dd-list">';
            foreach ($result as $row) {
                $tree .= '<li class="dd-item dd3-item'.($row['active']?' active':'').'" data-id="' . $row['id'] . '"><div class="dd-handle dd3-handle popovers" data-trigger="hover" data-content="拖动排序"></div>';
                $tree .= '<div class="dd3-content' . ($row['active']?' active':'').'">' . ($row['icon'] ? '<i class="' . $row['icon'] . '"></i> ' : '') . '[' . $row['id'] . ']' . '<a href="' . U('Index/menu', array('app' => $app, 'cmd' => 'menu_save', 'id' => $row['id'])) . '">' . $row['title'] . '</a><div class="pull-right">';
                $tree .= '<i class="icon-eye-' . ($row['hidden'] ? 'close' : 'open').'"></i>'; //显示隐藏，是否为活动状态
                if (!in_array($row['id'], $this->fixMenu)) {
                    if($row['app']=='Home'&&($row['type']==0||$row['type']==1)){//如果开启伪静态，后台Url模式不能为2，否则解析'Backend/'出错，以下url失效
                        $param_arr = explode('=',$row['param']);
                        $param = $row['app']=='/'.$param_arr[0].'/'.$param_arr[1];
                        $param = count($param_arr)==2?$param:'';
                        //index.php?m=content&a=index&id=205
						//$href_0 = str_replace('Backend/','',U('Content/index', array('id' => $row['id'])));
                        $href_0 = $this->Config['baseurl'].'index.php?m=content&a=index&id='.$row['id'];
						$href_1 = $this->Config['baseurl'].strtolower($row['model']).'/'.$row['action'].$param;
                        $href = $row['type']?$href_1:$href_0;
                        if(!strpos($href,'del')){
                            $tree .= '<a href="'.$href.'" class="btn green-stripe mini" target="_blank">查看</a>';
                        }
                    }elseif($row['type']==2){//只有标题菜单不予显示"查看"功能
                        $tree .= '<a href="'.$row['url'].'" class="btn green-stripe mini" target="_blank">查看</a>';
                    }
                    $tree .= '<a href="' . U('Index/menu', array('app' => $app, 'cmd' => 'menu_save', 'id' => $row['id'])) . '" class="btn green-stripe mini">编辑</a>';
                    $tree .= '<a href="' . U('Index/menu', array('app' => $app, 'cmd' => 'menu_del', 'id' => $row['id'])) . '" class="btn red-stripe mini del">删除</a>';
                } //编辑按钮, 前5个固定不能编辑
                $tree .= '</div></div>';
                $tree .= $this->getMenuTree($row['id'], $app); //子菜单
                $tree .= '</li>';
            }
            $tree .= '</ol>';
        }
        return $tree;
    }

    /**
     * nestable post 过来的sort对menu进行排序
     */
    private function sortMenuTree($parent_id, $children) {
        if (!empty($children)) {
            $Menu = M('Menu');
            foreach ($children as $array) {
                if (isset($array['id'])) {
                    $Menu->where(array('id' => $array['id']))->save(array(
                        'parent_id' => $parent_id,
                        'sorting' => $this->sorting
                    ));
                    $this->sorting++;
                }
                if (isset($array['children'])) {
                    self::sortMenuTree($array['id'], $array['children']);
                }
            }
        }
    }

    /**
     * 取图标列表
     */
    private function getIconList() {
        $iconArray[] = array(
            'label' => 'Basic Icons',
            'value' => array('icon-compass', 'icon-eur', 'icon-dollar', 'icon-yen', 'icon-won', 'icon-file-text', 'icon-sort-by-attributes-alt', 'icon-thumbs-down', 'icon-xing-sign', 'icon-instagram', 'icon-bitbucket-sign', 'icon-long-arrow-up', 'icon-windows', 'icon-skype', 'icon-male', 'icon-archive', 'icon-renren', 'icon-collapse', 'icon-euro', 'icon-inr', 'icon-cny', 'icon-btc', 'icon-sort-by-alphabet', 'icon-sort-by-order', 'icon-youtube-sign', 'icon-youtube-play', 'icon-flickr', 'icon-tumblr', 'icon-long-arrow-left', 'icon-android', 'icon-foursquare', 'icon-gittip', 'icon-bug', 'icon-collapse-top', 'icon-gbp', 'icon-rupee', 'icon-renminbi', 'icon-bitcoin', 'icon-sort-by-alphabet-alt', 'icon-sort-by-order-alt', 'icon-youtube', 'icon-dropbox', 'icon-adn', 'icon-tumblr-sign', 'icon-long-arrow-right', 'icon-linux', 'icon-trello', 'icon-sun', 'icon-vk', 'icon-expand', 'icon-usd', 'icon-jpy', 'icon-krw', 'icon-file', 'icon-sort-by-attributes', 'icon-thumbs-up', 'icon-xing', 'icon-stackexchange', 'icon-bitbucket', 'icon-long-arrow-down', 'icon-apple', 'icon-dribble', 'icon-female', 'icon-moon', 'icon-weibo')
        );
        $iconArray[] = array(
            'label' => 'Web Application Icons',
            'value' => array('icon-adjust', 'icon-asterisk', 'icon-ban-circle', 'icon-bar-chart', 'icon-barcode', 'icon-beaker', 'icon-bell', 'icon-bolt', 'icon-book', 'icon-bookmark', 'icon-bookmark-empty', 'icon-briefcase', 'icon-bullhorn', 'icon-calendar', 'icon-camera', 'icon-camera-retro', 'icon-certificate', 'icon-check', 'icon-check-empty', 'icon-cloud', 'icon-cog', 'icon-cogs', 'icon-comment', 'icon-comment-alt', 'icon-comments', 'icon-comments-alt', 'icon-credit-card', 'icon-dashboard', 'icon-download', 'icon-download-alt', 'icon-edit', 'icon-envelope', 'icon-envelope-alt', 'icon-exclamation-sign', 'icon-external-link', 'icon-eye-close', 'icon-eye-open', 'icon-facetime-video', 'icon-film', 'icon-filter', 'icon-fire', 'icon-flag', 'icon-folder-close', 'icon-folder-open', 'icon-gift', 'icon-glass', 'icon-globe', 'icon-group', 'icon-hdd', 'icon-headphones', 'icon-heart', 'icon-heart-empty', 'icon-home', 'icon-inbox', 'icon-info-sign', 'icon-key', 'icon-leaf', 'icon-legal', 'icon-lemon', 'icon-lock', 'icon-unlock', 'icon-magic', 'icon-magnet', 'icon-map-marker', 'icon-minus', 'icon-minus-sign', 'icon-money', 'icon-move', 'icon-music', 'icon-off', 'icon-ok', 'icon-ok-circle', 'icon-ok-sign', 'icon-pencil', 'icon-picture', 'icon-plane', 'icon-plus', 'icon-plus-sign', 'icon-print', 'icon-pushpin', 'icon-qrcode', 'icon-question-sign', 'icon-random', 'icon-refresh', 'icon-remove', 'icon-remove-circle', 'icon-remove-sign', 'icon-reorder', 'icon-resize-horizontal', 'icon-resize-vertical', 'icon-retweet', 'icon-road', 'icon-rss', 'icon-screenshot', 'icon-search', 'icon-share', 'icon-share-alt', 'icon-shopping-cart', 'icon-signal', 'icon-signin', 'icon-signout', 'icon-sitemap', 'icon-sort', 'icon-sort-down', 'icon-sort-up', 'icon-star', 'icon-star-empty', 'icon-star-half', 'icon-tag', 'icon-tags', 'icon-tasks', 'icon-thumbs-down', 'icon-thumbs-up', 'icon-time', 'icon-tint', 'icon-trash', 'icon-trophy', 'icon-truck', 'icon-umbrella', 'icon-upload', 'icon-upload-alt', 'icon-user', 'icon-user-md', 'icon-volume-off', 'icon-volume-down', 'icon-volume-up', 'icon-warning-sign', 'icon-wrench', 'icon-zoom-in', 'icon-zoom-out')
        );
        $iconArray[] = array(
            'label' => 'Currency Icons',
            'value' => array('icon-bitcoin', 'icon-eur', 'icon-jpy', 'icon-usd', 'icon-btc', 'icon-euro', 'icon-krw', 'icon-won', 'icon-cny', 'icon-gbp', 'icon-renminbi', 'icon-yen', 'icon-dollar', 'icon-inr', 'icon-rupee')
        );
        $iconArray[] = array(
            'label' => 'Text Editor Icons',
            'value' => array('icon-file', 'icon-cut', 'icon-copy', 'icon-paste', 'icon-save', 'icon-undo', 'icon-repeat', 'icon-paper-clip', 'icon-text-height', 'icon-text-width', 'icon-align-left', 'icon-align-center', 'icon-align-right', 'icon-align-justify', 'icon-indent-left', 'icon-indent-right', 'icon-font', 'icon-bold', 'icon-italic', 'icon-strikethrough', 'icon-underline', 'icon-link', 'icon-columns', 'icon-table', 'icon-th-large', 'icon-th', 'icon-th-list', 'icon-list', 'icon-list-ol', 'icon-list-ul', 'icon-list-alt')
        );
        $iconArray[] = array(
            'label' => 'Directional Icons',
            'value' => array('icon-arrow-down', 'icon-arrow-left', 'icon-arrow-right', 'icon-arrow-up', 'icon-chevron-down', 'icon-circle-arrow-down', 'icon-circle-arrow-left', 'icon-circle-arrow-right', 'icon-circle-arrow-up', 'icon-chevron-left', 'icon-caret-down', 'icon-caret-left', 'icon-caret-right', 'icon-caret-up', 'icon-chevron-right', 'icon-hand-down', 'icon-hand-left', 'icon-hand-right', 'icon-hand-up', 'icon-chevron-up')
        );
        $iconArray[] = array(
            'label' => 'Video Player Icons',
            'value' => array('icon-play-circle', 'icon-play', 'icon-pause', 'icon-stop', 'icon-step-backward', 'icon-fast-backward', 'icon-backward', 'icon-forward', 'icon-fast-forward', 'icon-step-forward', 'icon-eject', 'icon-fullscreen', 'icon-resize-full', 'icon-resize-small')
        );
        $iconArray[] = array(
            'label' => 'Brand Icons',
            'value' => array('icon-adn', 'icon-bitbucket-sign', 'icon-dribble', 'icon-flickr', 'icon-github-sign', 'icon-html5', 'icon-linux', 'icon-renren', 'icon-tumblr', 'icon-vk', 'icon-xing-sign', 'icon-android', 'icon-bitcoin', 'icon-dropbox', 'icon-foursquare', 'icon-gittip', 'icon-instagram', 'icon-maxcdn', 'icon-skype', 'icon-tumblr-sign', 'icon-weibo', 'icon-youtube', 'icon-apple', 'icon-facebook', 'icon-github', 'icon-google-plus', 'icon-linkedin', 'icon-pinterest', 'icon-stackexchange', 'icon-twitter', 'icon-windows', 'icon-youtube-play', 'icon-bitbucket', 'icon-css3', 'icon-facebook-sign', 'icon-github-alt', 'icon-google-plus-sign', 'icon-linkedin-sign', 'icon-pinterest-sign', 'icon-trello', 'icon-twitter-sign', 'icon-xing', 'icon-youtube-sign')
        );
        $iconArray[] = array(
            'label' => 'Medical Icons',
            'value' => array('icon-ambulance', 'icon-plus-sign-alt', 'icon-h-sign', 'icon-stethoscope', 'icon-hospital', 'icon-user-md', 'icon-medkit')
        );
        return $iconArray;
    }

}

?>