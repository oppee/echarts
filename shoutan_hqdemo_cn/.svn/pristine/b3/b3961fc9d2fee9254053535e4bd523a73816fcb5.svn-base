<?php
/**
 * 前台基础Controller
 */
namespace Mobile\Controller;
use Think\Cache;
use Think\Controller;
class BaseController extends Controller {

    public $mid = 0; //页面menu id
    public $Config = array(); //全局站点配置
    public $User = array(); //登录用户数据
    public static $ids;
    public static $good_tree;
    private $appid;
    private $searct;
    
    protected function _initialize() {
        //初始化配置
        $this->initConfig();
        //初始化当前登录用户信息
        //$this->initMember();
        //初始化菜单
        //$this->initMenu();
        //初始化meta信息
        //$this->initMeta();
        //初始化公共代码
        $this->initPublic();
    }
	
    /**
     *  初始化当前登录用户信息
     */
    final protected function initMember() {
        if (!session('FEUSER')) {
            $username = cookie($this->Config['cookie_prefix'].'username');
            $password = cookie($this->Config['cookie_prefix'].'password');
            //记住密码
            if (!empty($username) && !empty($password)) {
                $User = D("User");
                $Where = "deleted=0 and hidden=0 and (username='".$username."' or "."email='".$username."' or "."mobile='".$username."') and password='".$password."'";
                $login_info = $User->where($Where)->find();

                if($login_info){
                    //更新会员信息 写入session
                    $this->updateUserInfo($login_info['id']);
                    $this->updateUserCart($login_info['id']);
                    $this->updateUserGold($login_info['id']);
                }else{
                    //清除无效的用户cookie和session
                    $this->logout(false);
                }
            }
        }else{
            //已登录送积分
            $this->updateUserGold();
        }
    }
	
    //获得中文的月份
    public function get_month($month){
    	switch ($month) {
    		case '01':
    			$str='一月';
    			break;
    		case '02':
    			$str='二月';
    			break;
    		case '03':
    			$str='三月';
    			break;
    		case '04':
    			$str='四月';
    			break;
    		case '05':
    			$str='五月';
    			break;
    		case '06':
    			$str='六月';
    			break;
    		case '07':
    			$str='七月';
    			break;
    		case '08':
    			$str='八月';
    			break;
    		case '09':
    			$str='九月';
    			break;
    		case '10':
    			$str='十月';
    			break;
    		case '11':
    			$str='十一月';
    			break;
    		case '12':
    			$str='十二月';
    			break;
    	}
    	 
    	return $str;
    }
    
    //删除
    protected function delInfoFile($path, $file_name) {
    	$file_name = $path . $file_name;
    	if (is_file($file_name)) {
    		unlink($file_name);
    	}
    }
    
    
    /**
     * 注销登录
     */
    public function logout($redirect = true) {
        session('FEUSER', NULL);
        cookie($this->Config['cookie_prefix'].'username', NULL);
        cookie($this->Config['cookie_prefix'].'password', NULL);
        if($redirect){
            $this->redirect('Index/index');
        }
    }

    /**
     * 初始化站点配置
     */
    protected function initConfig() {
        $config = C('Config');
        $config['cookie_prefix'] = $config['order_prefix'];
        $this->Config = $config; //数据来自行为扩展赋值
		$this->assign('Config', $this->Config); //变量赋值模板
		$this->appid = C('WECHAT_APPID');
		$this->searct = C('WECHAT_SECRET');
		
        if ($this->Config['theme'] != C('DEFAULT_THEME')) {
            C('DEFAULT_THEME', $this->Config['theme']);
            C('TMPL_PARSE_STRING', array('__TMPL__' => __ROOT__ . trim(APP_PATH, '.') . MODULE_NAME .'/View/' . $this->Config['theme'] . '/'));
        }
		
		if ($this->Config['mod_rewrite']!=1) {
            C('URL_MODEL', 0);
        }
		 
    }

    /**
     * 初始化主导航菜单
     */
    protected function initMenu() {
        $where = array('deleted' => 0, 'hidden' => 0, 'app' => 'Home');
        $menuContent = M('Menu')->where($where)->group('position,id')->select();
        $personMenu = array();
        $companyMenu = array();
        foreach ($menuContent as $item){
            $item['url'] = strtolower(U($item['model'].'/'.$item['action']));
            if($item['position']==4){
                $companyMenu[] = $item;
            }elseif ($item['position']==5){
                $personMenu[] = $item;
            }
        }
//        var_dump($personMenu);
//        var_dump($companyMenu);
//        exit;
        $this->assign('personMenu', $personMenu); //赋值模板变量
        $this->assign('companyMenu', $companyMenu); //赋值模板变量
    }

    /**
     * 初始化公共代码
     */
    protected function initPublic() {
        Load('extend');

        //热门搜索
        /*$hot_search = $this->getHotSearch();
        $this->assign('hot_search', $hot_search);*/
        
        //网站名字
        $sitename = $this->Config['sitename'];
        $this->assign('sitename',$sitename);
        
        //页头导航
       /* $head_menu = $this->getMenu(1);
        $this->assign('head_menu', $head_menu);*/
        
        //页脚导航
        /*$foot_menu = $this->getMenu(2);
        $this->assign('foot_menu', $foot_menu);*/
        
        //将注册协议读出来
        $agb = $this->Config['agb'];
        $this->assign("agb",$agb);

        
       /* //全局版块显示设置
        $this->assign('Share', true);
        $this->assign('Category', true);*/
         

        //商品分类树
    	/*$categoryTree = $this->getCategoryTree();
    	$this->assign('categoryTree', $categoryTree);
    	$this->assign('state', 'hide');*/
    	
        //页头横幅广告
       /* $head_ad = $this->getBanner(2, 1);
        $this->assign('head_ad',$head_ad);*/
        
        //侧边广告
        /*$sideBanner = $this->getBanner(2);
        $this->assign('sideBanner', $sideBanner);*/
		
 
        //BCN
        //$bcn = $this->getBcn();
		
        /* //用户是否在线
        $isOnline = $this->updateUserInfo();
        $this->assign('isOnline', $isOnline); */
        
     
      
        
    }

    /**
     * 解析菜单id
     */
    private function setMid($mid) {
        $menuInfo = M('Menu')->where(array('id' => $mid))->find();
        if ($menuInfo) {
            if ($menuInfo['hidden'] || $menuInfo['deleted']) {
                if ($menuInfo['parent_id']) {
                    self::setMid($menuInfo['parent_id']);
                }
            } else {
                $this->mid = $menuInfo['id'];
            }
        }
    }

    /**
     * 加密算法
     * AUTHCODE 来自于config配置
     */
    protected function encryption($text) {
        return md5(md5($text) . C('AUTHCODE'));
    }

    protected function initMeta($_title='') {
        $title = $keywords = $description = '';
        if($this->mid){
            $menu = M('Menu')->where('id = ' . $this->mid)->field('title, meta_title, meta_keywords, meta_description')->find();
            $title = $menu['meta_title'] ? $menu['meta_title'] : $menu['title'];
            $keywords = $menu['meta_keywords'];
            $description = $menu['meta_description'];
        } else {
            $title = $this->Config['home_title'];
        }

        if ((COTROLLER_NAME == 'News' || COTROLLER_NAME == 'Goods') && ACTION_NAME != 'index') {
            $id = I('get.id') ? I('get.id') : I('get.cid');
            if (ACTION_NAME == 'info') {
                $page_meta = M(COTROLLER_NAME)->where('id = ' . $id)->field('title, meta_title, meta_keywords, meta_description')->find();
            } elseif (ACTION_NAME == 'lists') {
                $page_meta = M(COTROLLER_NAME.'_category')->where('id = ' . $id)->field('name as title, meta_title, meta_keywords, meta_description')->find();
            }
            $title = $page_meta['meta_title'] ? $page_meta['meta_title'] : $page_meta['title'];
            $keywords = $page_meta['meta_keywords'];
            $description = $page_meta['meta_description'];
            
        } elseif (COTROLLER_NAME == 'Index' && ACTION_NAME == 'index') {
            $title = $this->Config['home_title'];
        }
        
        $title = $_title ? $_title : $title;
        $keywords = $keywords ? $keywords : $this->Config['keywords'];
        $description = $description ? $description : $this->Config['description'];


        $meta = array(
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
        );

        $this->assign('meta', $meta);
    }

    /**
     * 获取菜单
     * @param $p 菜单位置
     * @param $pid 父级菜单ID
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     */
    protected function getMenu($p = "", $pid = 0, $sort = "sorting", $order = "ASC") {
        $menu = array(); //定义空数组

        $where = "deleted = 0 AND hidden = 0 AND app = 'Home' AND parent_id = " . $pid; //查询条件

        if ($p !== "") {//如果 菜单位置 不等于空字符串
            $where .= " AND FIND_IN_SET('" . $p . "', position)"; //附加查询条件
        }

        $result = M('Menu')->where($where)->order($sort . ' ' . $order)->select(); //查询menu表满足条件的数据
//        echo M()->getLastSql();
        if ($result) {//数据存在就进来
            foreach ($result as $v) {//对数据遍历
                $v = array_merge($v, $this->getMenuLink($v)); //设置url
                //parent menu
                $submenu = self::getMenu('', $v['id']); //子菜单

                if ($submenu) {
                    $v['sub'] = $submenu; //在$v中添加submenu元素，并将$submenu数据集赋给它
                }

                $menu[] = $v; //接收数组
            }
        }
        return $menu;
    }

    /**
     * 获取菜单
     * @param $p 菜单位置
     * @param $pid 父级菜单ID
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     */
    protected function getMenuById($ids = array(), $pid = 0, $sort = "sorting", $order = "ASC") {
        $menu = array(); //定义空数组

        $where = "deleted = 0 AND hidden = 0 AND app = 'Home' AND parent_id = " . $pid; //定义查询条件

        if (count($ids)) {//判断ids数组是否有数量
            $ids = implode(',', $ids); //用逗号连成字符串
            $where .= " AND id IN (" . $ids . ")"; //附加到查询条件
        }

        $result = M('Menu')->where($where)->order($sort . ' ' . $order)->select(); //查询menu表满足条件的数据
        //echo M()->getLastSql();
        if ($result) {//如果有数据就进来
            foreach ($result as $v) {
                //parent menu
                $submenu = self::getMenu('', $v['id']); //子菜单

                if ($submenu) {//如果子菜单数据存在
                    $v['sub'] = $submenu; //在$result中添加submenu元素并赋值
                }

                $menu[] = $v; //接收$v
            }
        }
        return $menu;
    }

    /**
     * 获取用户菜单
     */
    protected function getUserMenu() {
        //用户菜单导航
        $userMenu = array();
		$userMenu[] = array('mode' => 'index', 'title' => '会员中心', 'url' => '' . U('Member/index','') . '', 'class' => 'icon-das','color'=>'icon-re');
        $userMenu[] = array('mode' => 'useraddress', 'title' => '我的地址', 'url' => '' . U('Member/index', 'step=useraddress') . '', 'class' => 'icon-truck');
        $userMenu[] = array('mode' => 'userinfo', 'title' => '个人资料', 'url' => '' . U('Member/index', 'step=userinfo') . '', 'class' => 'icon-psinfo');
        $userMenu[] = array('mode' => 'modpass', 'title' => '密码修改', 'url' => '' . U('Member/index', 'step=modpass') . '', 'class' => 'icon-pass');
        $userMenu[] = array('mode' => 'orders', 'title' => '我的订单', 'url' => '' . U('Member/index', 'step=orders') . '', 'class' => 'icon-orders');
        $userMenu[] = array('mode' => 'favorites', 'title' => '我的收藏', 'url' => '' . U('Member/index','step=favorites') .'', 'class' => 'icon-fav');
		$userMenu[] = array('mode' => 'rechargelog', 'title' => '支付记录', 'url' => '' . U('Member/index','step=rechargelog').'', 'class' => 'icon-re');
		$userMenu[] = array('mode' => 'goldlog', 'title' => '积分记录', 'url' => '' . U('Member/index','step=goldlog').'', 'class' => 'icon-gold');
		//$userMenu[] = array('mode' => 'mailbox', 'title' => '我的消息', 'url' => '' . U('Member/index') . 'step=mailbox', 'class' => 'icon-re');
        //$userMenu[] = array('mode' => 'gold', 'title' => '我的积分', 'url' => '' . U('Member/index') . 'step=gold', 'class' => 'icon-points');
        $userMenu[] = array('mode' => 'logout', 'title' => '安全退出', 'url' => '' . U('User/index', 'step=logout') . '', 'class' => 'icon-exit');
        foreach ($userMenu as $key => $val) {
            $m = $userMenu[$key]['mode'];
            $a = I('step');
            if ($m==$a||($m=='package'&&$a=='recharge')||($m=='mailbox'&&$a=='mailboxinfo')||($key==0&&$a=='')) {
                $userMenu[$key]['active'] = " active ";
            } else {
                $userMenu[$key]['active'] = " ";
            }
        }
        return $userMenu;
    }

    /**
     * 根据广告位子ID获取广告
     * @param $apid 广告位置ID
     * @param $limit 返回最大题目数
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     */
    protected function getBanner($apid = "", $limit = "", $sort = "sorting", $order = "ASC") {
    	
        $where = 'deleted = 0 AND hidden = 0 AND 
                 (start_time = 0 OR (start_time != 0 AND start_time <= ' . time() . ')) AND
                 (end_time = 0 OR (end_time != 0 AND end_time >= ' . time() . '))';

        if ($apid !== NULL) {
            $where .= ' AND position = ' . $apid;
        }

        $list = M('Banner')->where($where)->order('top DESC, ' . $sort . ' ' . $order);

        if ($limit) {
            $list = $list->limit($limit);
        }

        if ($list = $list->select()) {
            foreach ($list as $key => $info) {
                $str = '<a href="' . formatUrl($info['link']) . '" title="' . $info['title'] . '" target="' . $info['target'] . '">';

                switch ($info['type']) {
                    case 0: //0.图片
                        if (!empty($info['image'])) {
                            $str .= '<img src="'.__ROOT__.'/Uploads/Banner/' . $info['image'] . '" title="' . $info['title'] . '" alt="' . $info['title'] . '" />';
                        }
                        break;
                    case 1: //1.flash
                        if (!empty($info['flash_file'])) {
                            $str .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">';
                            $str .= '<param name="allowScriptAccess" value="sameDomain" />';
                            $str .= '<param name="movie" value="./Uploads/Banner/' . $info['flash_file'] . '" />';
                            $str .= '<param name="quality" value="high" />';
                            $str .= '<param name="bgcolor" value="#ffffff" />';
                            $str .= '<embed src="./Uploads/Banner/' . $info['flash_file'] . '" quality="high" bgcolor="#ffffff" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
                            $str .= '</object>';
                        }
                        break;
                    case 3: //2.文本
                        $str .= $info['text'];
                        break;
                    case 4: //3.代码
                        $str .= $info['code'];
                        break;
                }

                $str .= '</a>';

                $list[$key]['html'] = $str;
            }
        }
        return $list;
        // return $limit == 1 ? array_shift($list) : $list;
    }

    /**
     * BCN
     * @param $bcn   bcn数组，包括2个键值对，url链接，title链接名称
     */
    protected function getBcn($bcn = array(), $other = array()) {
        $site = array(
            'url' => trim($this->Config['baseurl']) ? $this->Config['baseurl'] : './',
            'title' => '首页',
            'target' => ''
        );
        array_unshift($bcn, $site); //将$site插入到$bcn数组的开头位置
        if (count($other)) {
            $bcn[] = $other;
        }
        return $bcn;
    }

    /**
     * 友情链接
     * @param $place 链接位置
     * @param $type 链接类型：1图片，0文字
     * @param $limit 返回最大题目数
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     * @param $width,$height 图片长和宽
     */
    protected function getLinks($place = "", $type = "", $limit = "", $sort = "sorting", $order = "ASC", $width = "", $height = "") {
        $where = "deleted = 0 AND hidden = 0";

        if ($place !== '') {
            $where .= $place === 0 ? " AND place = 0" : (" AND (place = 0 OR place = '$place')");
        }

        if ($type) {
            $where .= " AND type = '$type'";
        }

        $list = M('Links')->where($where)->order('top DESC, ' . $sort . ' ' . $order);

        if ($limit) {
            $list = $list->limit($limit);
        }

        $list = $list->select();

        $links = array();
        $type = array('txt', 'img');

        if (count($list)) {
            foreach ($list as $v) {
                $tmp = array(
                    'title' => $v['title'],
                    'url' => formatUrl($v['url']),
                );

                if ($v['type'] == 1 && !empty($v['image'])) {
                    $tmp['image'] = '<img src="'+__ROOT__+'/Uploads/Links/' . $v['image'] . '" alt="' . $v['title'] . '" title="' . $v['title'] . '" />';
                    ;
                }

                $links[$type[$v['type']]][] = $tmp;
            }
        }

        return $links;
    }

    protected function addHit($tbName, $id) {
        $where = array(
            'deleted' => 0,
            'hidden' => 0,
            'id' => $id,
        );
        if (is_numeric($id)) {
            $Info = M($tbName); // 实例化tbName对象

            $Info->where($where)->setInc('hit'); // hit字段加1
        } else {
            $this->error('非法操作！', U('/'));
        }
    }
	//点赞
    protected function likes($tbName, $id){
    	$where = array(
    			'deleted' => 0,
    			'hidden' => 0,
    			'id' => $id,
    	);
    	if (is_numeric($id)) {
    		$Info = M($tbName); // 实例化tbName对象
    	
    		$Info->where($where)->setInc('likes'); // hit字段加1
    	} else {
    		$this->error('非法操作！', U('/'));
    	}
    }
    /**
     * 获取多个分类文章
     * @param $tabName 数据库名
     * @param $limit 返回最大题目数
     * @param $hot 是否按热门排序
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     * @param $length 获取几个分类
     */
    protected function getHotClass($tabName = 'News', $limit = "10", $sort = "sorting", $order = "DESC",$length='2') {
        $where = array(
            'deleted' => 0,
            'hidden' => 0,
            'hot' => 1
        );
        if ($list = M($tabName . '_category')->where($where)->limit("0,$length")->order('top DESC,sorting desc')->select()) {
        	foreach ($list as $k => $v) {
                $list[$k]['url'] = U($tabName . '/lists/', 'id=' . $v['id']);
                $list[$k]['list'] = $this->getList($tabName, $v['id'], $limit, $hot, $sort, $order);
            }
            return $list;
        }
	}
       
    /**
     * 获取单一分类文章
     * @param $cid 分类ID
     * @param $limit 返回最大题目数
     * @param $hot 是否按热门排序
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     */
    protected function getList($tabName = 'News', $cid = "", $limit = "10", $hot = "", $sort = "sorting", $order = "desc") {
        $where = array(
            'deleted' => 0,
            'hidden' => 0
        );

        if ($cid) {
            $ids = $this->getSubId($cid);
            $ids[] = $cid;

            if (count($ids)) {
                $ids = implode(',', $ids);
                $where['category'] = array('in', $ids);
            }
        }

        if ($hot) {
            $where['hot'] = 1;
        }

        $objList = M($tabName)->where($where);

        $str = 'top DESC';
        if ($sort) {
            $str .= ',' . $sort . ' ' . $order;
        }
        $objList = $objList->order($str);

        if ($limit) {
            $objList = $objList->limit($limit);
        }
		//echo $objList->getLastSql()."<br />";
        if ($objList = $objList->select()) {
            foreach ($objList as $k => $v) {
                $objList[$k]['url'] = strtolower(U($tabName . '/info', array('id' => $v['id'])));

                if (!$v['remark']) {
                    $v['remark'] = strip_tags($v['content']);
                }
                $objList[$k]['remark'] = $v['remark'];

                if (!empty($v['image'])) {
                    $path = __ROOT__.'/Uploads/' . $tabName . '/';
                    foreach (explode(',', $this->Config['thumb_width']) as $x => $y) {
                        $objList[$k]['thumb_'.$x] = $path . 'thumb_' . $x . '/' . date('Ym', $v['crdate']) . '/' . $v['image'];
                    }
                    $objList[$k]['image'] = $path . 'source/' . date('Ym', $v['crdate']) . '/' . $v['image']; ;
                } else {
                    $objList[$k]['image'] = '';
                }
            }
        }

        return $objList;
    }

    /**
     * 获取多个分类文章
     * @param $cid 分类ID（数组）
     * @param $limit 返回最大题目数
     * @param $hot 是否按热门排序
     * @param $sort 排序字段
     * @param $order 排序方式（升序、降序）
     */
    protected function getNewsByClass($cid, $limit = "10", $hot = "", $sort = "sorting", $order = "DESC") {
        $list = array();

        if (is_array($cid) && count($cid)) {
            foreach ($cid as $id) {
                $where = array(
                    'deleted' => 0,
                    'hidden' => 0,
                    'id' => $id
                );

                if ($rs = M('News_category')->where($where)->find()) {
                    $info = array(
                        'name' => $rs['name'],
                        'id' => $id,
                        'list' => $this->getList('News', $id, $limit, $hot, $sort, $order)
                    );

                    $list[] = $info;
                }
            }
        }
        return $list;
    }

    /**
     * 获取子分类ID
     * @param $cid 文章类别ID
     */
    protected function getSubId($cid, $tabName = 'News_category') {
        $where = array(
            'deleted' => 0,
            'hidden' => 0,
            'parent_id' => $cid
        );

        $ids = array();

        if ($rs = M($tabName)->field('id')->where($where)->select()) {
            foreach ($rs as $v) {
                $ids[] = $v['id'];
                $ids = array_merge($ids, $this->getSubId($v['id'], $tabName));
            }
        }
        return array_unique($ids);
    }

    /**
     * 获取子分类ID
     * @param $type 评论类别（文章、商品）
     * @param $id 评论所属的文章或商品的ID
     */
    protected function getComment($limit=10, $type=0, $hot=0, $id=0) {
		 
		$tbName = $type==0 ? 'goods' : 'news';
        $Where = array(
            'c.deleted' => 0,
            'c.hidden' => 0,
            'c.info_type' => $type,
            'c.hot' => $hot
        );

		if($id){
			$Where = array('c.info_id' => $id);
		}
		
		$objList = M('Comment as c')->where($Where)->join(C('DB_PREFIX').$tbName.' as i ON i.id = c.info_id')->join(C('DB_PREFIX').'user as u ON u.id = c.user_id')->field('c.*, i.title, u.username, u.crdate as user_crdate, u.image')->order('c.id desc')->limit($limit)->select();
		//echo M('Comment as c')->getLastSql();exit;
		
        return $objList;
    }

    /**
     * 热门搜索
     * $url = 'Search/index'
     */
    protected function getHotSearch($url = "Goods/search") {
        $html = '';
		$kewords = str_replace('，',',',strip_tags($this->Config['hot_keyword'])); 
        $keys = split(',', $kewords); //切割关键词
        if (count($keys)) {
            foreach ($keys as $k) {
                $k = trim($k);

                if ($k != '') {
                    $html .= '<a title="' . $k . '" href="' . U($url, array('keyword' => $k)) . '">' . $k . '</a>' . chr(13);
                }
            }
        }

        return $html;
    }

    /**
     * 菜单链接方式
     * @param $info      查询数据库所得的数据
     * @param $type      0输出带<a>标签的url，1输出数组
     */
    protected function getMenuLink($info, $type = 0) {
        //echo $this->Config['url_model'];
        $target = $info['target']; //定义变量，三元判断赋值
        //menu href
        switch ($info['type']) {
            case 1: //1.内部链接
                $href = U($info['model'] . '/' . $info['action'].'/sell_num/sell_num_desc/', $info['param']);
                break;
            case 2: //2.外部链接
                $href = U('index/index');
                //$href = formatUrl($info['url']);
                break;
            case 3: //3.标题菜单
                $href = 'javascript:void(0);';
                $target = '';
                break;
            default: //0.内容页面
               $href = U('Content/index', 'id=' . $info['id']);
               break;
        }

        if ($url_tag) {
            $target = $target ? 'target="' . $target . '"' : '';
            $result = '<a href="' . $href . '" title="' . $info['title'] . '" ' . $target . '>' . $info['title'] . '</a>'; //定义字符串输出html标签内容
        } else {
            $result = array(
                'url' => $href,
                'title' => $info['title'],
                'target' => $target
            );
        }

        return $result;
    }

    /**
     * 获取当前无限级别路径
     */
    protected function getPathAndClass($id, $model_name = "Menu", $down = false) {
        $list = $item = array(); //定义空数组
        $where = "deleted = 0 AND hidden = 0 AND id = " . $id; //定义查询条件

        if ($rs = M($model_name)->where($where)->find()) {//查询数据表满足条件的数据
            $item = $rs; //接收$rs

            if ($model_name == 'Menu') {//如果数据表名为menu
                $item['image'] = $rs['image']; //定义image元素
                $item = array_merge($item, $this->getMenuLink($rs)); //定义url元素
            } elseif ($model_name == 'goods_category') {//如果数据表名为goods_category
                $item['title'] = $rs['name'];
				if($rs['type']==1){
					if($rs['parent_id']!=0){
						$item['url'] = U('Goods/lists/', 'id=' . $item['id']);
					}else{
						$item['url'] = U('Goods/index/', 'id=' . $item['id']);
					}
				}else{
					$item['url'] = U('Goods/lists/', 'id=' . $item['id']);
				}
                
            } elseif ($model_name == 'news_category') {//如果数据表名为news_category
                $item['title'] = $rs['name'];
                $item['url'] = U('News/lists/', 'id=' . $item['id']);
            }

            if (!in_array($item, $list)) {//判断$item是否在$list数组中，如果找不到就进来。
                $list[] = $item; //在$list数组中添加一个元素，并将$item赋值给它。
            }

            if ($down == true) {
                if ($sub = M($model_name)->where("deleted = 0 AND hidden = 0 AND parent_id = " . $id)->select()) {
                    foreach ($sub as $v) {
                        $list = array_merge($list, $this->getPathAndClass($v['id'], $model_name, $down));
                    }
                }
            } else {
                if ($rs['parent_id']) {
                    $list = array_merge($this->getPathAndClass($rs['parent_id'], $model_name, $down), $list);
                }
            }

            return $list;
        }
    }

    /*     * ******Tree begin*************** */

    /**
     * 获取分类树
     */
    protected function getCategoryTree($pid = 0, $tb_name = 'Goods_category') {
        $Cache = Cache::getInstance('File');
        $tree = $Cache->get($tb_name . '_tree_' . $pid);
        if (!is_array($tree)) {
            $tree = $this->getCategoryData($pid, $tb_name);
            $Cache->set($tb_name . '_tree_' . $pid, $tree);
        }

        return $tree;
    }

    /**
     * 获取分类数据
     */
    protected function getCategoryData($pid = 0, $tb_name = 'Goods_category') {
        $tree = array();
        $where = "deleted = 0 AND hidden = 0 AND parent_id = " . $pid;

        if ($rs = M($tb_name)->where($where)->select()) {
            foreach ($rs as $v) {
                $item = array(
                    'id' => $v['id'],
					'image' => $v['image'],
                    'name' => $v['name'],
                    'type' => $v['type'],
                    'pid'=> $v['parent_id'],
					'remark' => $v['remark'],
					'title' => $v['name'],
                );
				 if ($tb_name == 'goods_category'){
                   // $item['url'] = U('Goods/lists', 'id='. $v['id']);
                    $item['img_url'] =  $this->Config['baseurl']."Uploads/Goodscategory/".$v['image'];
                } elseif ($tb_name == 'news_category') {
                   // $item['url'] = U('News/lists', 'id='. $v['id']);
                    $item['img_url'] =  $this->Config['baseurl']."Uploads/Newscategory/".$v['image'];
                }
                if (M($tb_name)->where("deleted = 0 AND hidden = 0 AND parent_id = " . $v['id'])->count()) {
                    $item['sub'] = $this->getCategoryData($v['id'], $tb_name);
				
                }

                $tree[] = $item;
            }
        }

        return $tree;
    }
	

    /*     * ******Tree end*************** */

    /**
     * 获取上一条和下一条数据
     */
    protected function getPreAndNext($id = null, $tabName = 'News', $category = '') {
        $where = 'deleted = 0 AND hidden = 0';
        if ($category) {
            $where .= ' AND category = ' . $category;
        }

        if (!$id) {
            $id = $this->_get('id');
        }

        if ($info = M($tabName)->where($where . ' AND id < ' . $id)->order('id DESC')->find()) {
            $pre = array(
                'url' => U($tabName . '/info', 'id=' . $info['id']),
                'title' => $info['title']
            );
        } else {
            $pre = array(
                'url' => 'javascript:void(0)',
                'title' => '没有了'
            );
        }

        if ($info = M($tabName)->where($where . ' AND id > ' . $id)->order('id ASC')->find()) {
            $next = array(
                'url' => U($tabName . '/info', 'id=' . $info['id']),
                'title' => $info['title']
            );
        } else {
            $next = array(
                'url' => 'javascript:void(0)',
                'title' => '没有了'
            );
        }

        $result = array('pre' => $pre, 'next' => $next);
        //dump($result);
        return $result;
    }




    //判断是否是用户，如果不是就返回网站首页
    /*public function isCompetence($type) {
        if (!$_SESSION['FEUSER']['id'] || $_SESSION['FEUSER']['type']!=$type) {
            $this->redirect('user/index', array('step'=>'login'));
        }
    }*/
    public function isCompetence($type) {
        if (!$_SESSION['FEUSER']['id']) {
            $this->redirect('user/login');
        }
    }




    public function getLeftMenu() {
        //解析菜单ID begin
        if (COTROLLER_NAME == 'Content' && I('get.id')) { //如果当前模块名为content并且$_GET['id']存在
            $mid = I('get.id'); //指定menu id
        } elseif (I('get.alias')) { //别名
            $mid = M('Menu')->where(array('deleted' => 0, 'hidden' => 0, 'app' => 'Home', 'alias' => I('get.alias')))->getField('id');
        } else { //内部页面
            $where = array('deleted' => 0, 'hidden' => 0, 'app' => 'Home', 'model' => COTROLLER_NAME, 'action' => ACTION_NAME);

            if (COTROLLER_NAME == 'News' && ACTION_NAME == 'lists') {
                $where['param'] = 'id=' . I('get.id');
            } elseif (COTROLLER_NAME == 'News' && ACTION_NAME == 'info') {
                //$new_id = I('get.id');
                $category = M('News')->where(array('id' => I('get.id')))->getField('category');
                $where['param'] = 'id=' . $category;
                $where['action'] = 'lists';
            }
            $mid = M('Menu')->where(array($where))->getField('id');
        }

        $menu = array();

        if ($mid) {
            $menu_list = $this->getPathAndClass($mid);
            $menu = M('Menu')->where(array('id' => $menu_list[0]['id']))->find();
            $menu['sub'] = $this->getMenu('', $menu_list[0]['id']);
            $menu['current'] = $mid;
            $menu['parent'] = M('Menu')->where('id = ' . $mid)->getField('parent_id');
            $menu['type'] = 'menu';

            if (strpos($menu['position'], '3') === false) {
                $this->assign('LeftMenu', true);
            } else {
                $this->assign('Help', true);
            }
        } else {
            if (COTROLLER_NAME == 'News' && ACTION_NAME == 'lists') {
                $nid = I('get.id');
            } elseif (COTROLLER_NAME == 'News' && ACTION_NAME == 'info') {
                $nid = M('News')->where(array('id' => I('get.id')))->getField('category');
            }

            $menu_list = $this->getPathAndClass($nid, 'News_category');
            $menu = M('News_category')->field('*, name as title')->where(array('id' => $menu_list[0]['id']))->find();
            $menu['sub'] = $this->getCategoryData($menu_list[0]['id'], 'News_category');

            $menu['current'] = $nid;
            $menu['parent'] = M('News_category')->where('id = ' . $nid)->getField('parent_id');
            $menu['type'] = 'news';

            $this->assign('LeftMenu', true);
        }

        return $menu;
    }

    //测试DEBUG
    function debug($str, $Path) {
        $fp = fopen($Path, "a+");
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $tempstr = $key . "=>" . $val . "\n";
                fwrite($fp, $tempstr);
            }
        } else {
            fwrite($fp, $str . "\n");
        }
        fclose($fp);
    }
    /**
     * 刷新SESSION会员信息 以及本地购物车入库
     * @param $user_id 传入用户ID 默认为零则获取session里的用户ID
     * @param $additional 补充信息 合并或取代原始数据
     */
    function updateUserInfo($user_id = 0, $additional = array()){
        $user_id = $user_id ? $user_id : $_SESSION['FEUSER']['id'];
        if(!$user_id)return true;
        
        $user_info =M('User')->where(array('deleted' => 0, 'hidden' => 0, 'id' => $user_id))->find();
        if($user_info){
            //刷新SESSION会员信息
            $user_info = array_merge($user_info, $additional);
            session('FEUSER', $user_info); //重新写入session
        }
        return $user_info;
    }
    
  
   
    
    /*获取"今日更新"的分类列表(带更新数量,可指定日期)#v3.0#包含24小时内的更新
    *@param $tbName varchar 表名(关联分类表)
    *@param $limit int 显示条目数
    *@param $field varchar 需要的字段名
    *@param $where array 查找条件
    *@param $order varchar 排序规则
    *@param $date int 日期长度(以当前时间为基准。昨天写法：-1)
    */
    function getUpdateByDate($tbName='Music',$Where=array(),$field='id,name,type,parent_id',$order='id asc',$date = 0){
        $Obj = M($tbName);
        $Obj_C = M($tbName.'Category');
        $time_0 = time()-3600*24*(1-$date);
        $Where['deleted'] = 0;
        $Where['hidden'] = 0;
        $Where['crdate|tstamp'] = array('gt',$time_0);
        
        $list = $Obj->where($Where)->group('parent_id')->field('category as parent_id, count(*) as num')->select();
        $list_c_tmp = $Obj_C->where('deleted=0 and hidden=0 and parent_id=0')->field($field)->select();
        
        foreach($list_c_tmp as $k=>$v){
            $list_c[$v['id']] = $v;
        }
        foreach($list as $k=>$v){
            if($v['parent_id']>0){
                $tmp_arr = $Obj_C->where('deleted=0 and hidden=0 and id='.$v['parent_id'])->field($field)->find();
                if($tmp_arr){
                    $list_c[$v['parent_id']] = $tmp_arr;
                    $list_c[$v['parent_id']]['num'] = $v['num']*1;
                }
            }
        }
        foreach($list_c as $k=>$v){
            if($v['parent_id']>0){
                if(!$list_c[$v['parent_id']]){
                    $tmp_arr = $Obj_C->where('deleted=0 and hidden=0 and id='.$v['parent_id'])->field($field)->find();
                    if($tmp_arr){
                        $list_c[$v['parent_id']] = $tmp_arr;
                        $list_c[$v['parent_id']]['num'] = 0;
                    }
                }else{
                    $list_c[$v['parent_id']]['num'] += $v['num']*1;
                }
            }
        }
        foreach($list_c as $k=>$v){
            $list_c[$k]['num'] = $v['num'] ? $v['num'] : 0;
            if($v['parent_id']!=0){
                unset($list_c[$k]);
            }
        }
        return $list_c;
    }
    /**
     * 发送邮件
     * @param $email      邮箱地址
     * @param $paramters  模版参数数组
     * @param $type       邮件模版名称
     */
    public function sendMail($email, $paramters, $type) {
        if ($type && (!$this->Config[$type] || !$info = getEmailConfig($type))) {
            return false;
        }

        if (!is_array($paramters)) {
            return false;
        }

        $content = $info['content'];
        foreach ($paramters as $k => $v) {
            $content = str_ireplace('{###' . $k . '###}', $v, $content);
        }

        $subject = $info['subject'];
        $bcc = $info['bcc'];

        return sendMail($subject, $content, $email, '', $bcc);
    }

    /**
     * 发送站内信
     * @param $form         发信人name
     * @param $to           收信人id
     * @param $title        站内信标题    
     * @param $content      站内信内容 
     */
     public function sendSiteMsg($to,$title,$content,$from='系统消息'){
        $Mailbox = M('Mailbox');
        $addData = array();
        
        $addData['crdate'] = $addData['tstamp'] = time();
        $addData['sender'] = $from;
        $addData['recipient'] = $to;
        $addData['subject'] = $title;
        $addData['content'] = $content;
        
        $addData['is_view'] = $addData['hidden'] = 0;
        return $Mailbox->add($addData); 
     }
    /**
     * 发送短信
     * @param mobiles   电话号码（数组）
     * @param content   短信内容
     */
    protected function sendSMS($mobiles, $cotent) {
        if (!is_array($mobiles) || count($mobiles) == 0 || empty($cotent)){
            return false;
        }
        
        foreach($mobiles as $v){
            if (!is_numeric($v)){
                return false;
            }
        }
        set_time_limit(0);
        import('Org.Net.HTTP_SDK');

        //网关地址
        $gwUrl = $this->Config['sms_url'];

        //序列号,请通过亿美销售人员获取
        $serialNumber = $this->Config['sms_serial'];

        //密码,请通过亿美销售人员获取
        $password = $this->Config['sms_pwd'];

//        $cotent = iconv('UTF-8', 'GBK', $cotent);

        //登录后所持有的SESSION KEY，即可通过login方法时创建
        $sessionKey = 'SMS';

        //连接超时时间，单位为秒
        $connectTimeOut = 2;

        //远程信息读取超时时间，单位为秒
        $readTimeOut = 10;

        $engine = \HTTP_SDK::getInstance($serialNumber,$password,$gwUrl);
        $return_code = $engine->pushMt($mobiles,'1111111111', $cotent,  0);
        return iconv('GBK', 'UTF-8', $return_code);
    }
    /**
     * 获取购物车商品总数量
     */
    public function getCartGoodsNum(){
        $total_goods_num = 0;
        $user_id = $_SESSION['FEUSER']['id'];
        if($user_id){
            //已登录 读取cart表
            $Cart = M('Cart as c');
            //商品本身必须有效
            $objList = $Cart->join(C('DB_PREFIX').'goods as g on c.goods_id=g.id')
                            ->where("c.deleted=0 and c.hidden=0 and g.deleted=0 and g.hidden=0 and c.user_id='".$user_id."'")
                            ->field('c.goods_num as goods_num')->select();
            foreach($objList as $k=>$v){
                $total_goods_num += $v['goods_num'];
            }
        }else{
            //未登录 读取本地cookie
            //反转义特殊字符 方便序列化和反序列化
            $cookie_cart = cookie($this->Config['cookie_prefix'].'cart');
            if ($cookie_cart && ini_get("magic_quotes_gpc") == "1") {
                $cookie_cart = stripslashes($cookie_cart);
            }
            $cart_local = unserialize($cookie_cart);
            foreach($cart_local as $k=>$v){
                $total_goods_num += $v['goods_num'];
            }
        }
        return $total_goods_num;
    }

    /**
     * 文件上传
     * @param $key           上传字段名
     * @param $path          上传主路径('/'结尾)
     * @param $save_path     主路径下的文件夹('/'结尾)
     * @param $extent_path   扩展文件夹('/'结尾)
     * @param $thumb_width   缩略图宽度(逗号隔开的字符串数字)
     * @param $thumb_height  缩略图高度(逗号隔开的字符串数字)
     * @param $type         文件类型限制
     */
    public function uploadFile($key, $path, $save_path = '', $extent_path = '', $thumb_width = '', $thumb_height = '', $type = array('jpg', 'gif', 'png', 'jpeg')) {
        import('Org.Net.UploadFile');
        $upload = new \Org\Net\UploadFile(); // 实例化上传类
        $upload->allowExts = $type; //设置附件上传类型
        $upload->maxSize = 1024000; // 设置附件上传大小
        $upload->savePath = $path . $save_path . $extent_path; // 设置附件上传目录
        $upload->uploadReplace = false; //存在同名是否覆盖
        $upload->saveRule = 'uniqid'; //上传文件命名规则, 没有定义命名规则，则保持文件名不变
        //缩略图
        if ($thumb_width) {
            $upload->thumb = true;
            $upload->thumbPath = $path;
            $upload->thumbMaxWidth = $thumb_width; //缩略图宽度
            $upload->thumbMaxHeight = $thumb_height; //缩略图宽度

            $thumbDir = array();
            foreach (explode(',', $thumb_height) as $k => $v) {
                $thumbDir[$k] = 'thumb_' . $k . '/' . $extent_path;
            }
            $upload->thumbDir = implode(',', $thumbDir); //缩略图文件夹
        }


        $result = array();
        if ($rs = $upload->uploadOne($_FILES[$key])) { // 上传错误提示错误信息
            $rs = array_shift($rs);
            $result = array(
                'upload' => true,
                'info' => $rs['savename']
            );
        } else { // 上传成功 获取上传文件信息
            $result = array(
                'upload' => false,
                'info' => $upload->getErrorMsg()
            );
        }
        return $result;
    }
    //处理所有已过期的订单state=5更新为已失效state=4 并返还库存
    public function updateOrdersAndReturnStock(){
        $Orders = M('Orders');
        $orders_list = $Orders->where('deleted=0 and hidden=0 and state=5')->field('id')->select();
        
        if(!$orders_list)return true;
        foreach($orders_list as $v){
            $this->returnStock($v);
        }
        $Orders->where('deleted=0 and hidden=0 and state=5')->setField('state', 4);
    }
    //处理指定的订单 将订单商品返还库存
    public function returnStock($this_orders){
        $OrdersGoods = M('OrdersGoods');
        $Goods = M('Goods');
        $goods_list = $OrdersGoods->where('deleted=0 and hidden=0 and orders_id='.$this_orders['id'])->field('goods_id, quantity')->select();
        if(!$goods_list)return true;
        foreach($goods_list as $v){
            if(!$v['quantity']>0)continue;
            $Goods->where('deleted=0 and hidden=0 and id='.$v['goods_id'])->setInc('stock', $v['quantity']);
        }
    }
	
	//写入订单流水(读取指定订单状态，写入订单状态流水表)
	public function saveOrdersStatus($orders_id){
		if(!$orders_id)return false;
		$state = M('Orders')->where('deleted=0 and hidden=0 and id='.$orders_id)->getField('state');
		if($state===false)return false;
		$data_add = array(
			'crdate' => time(),
			'tstamp' => time(),
			'orders_id' => $orders_id,
			'status' => $state
		);
		M('OrdersStatus')->add($data_add);
		$this->emailOrdersStatus($orders_id);
	}
	//根据订单状态发送邮件(读取指定订单详细，向用户发送邮件)
	public function emailOrdersStatus($orders_id){
		if(!$orders_id)return false;
		$orders_info = M('Orders')->where('deleted=0 and hidden=0 and id='.$orders_id)->find();
		//订单状态为 0,1,2 的时候发送邮件
		if(!in_array($orders_info['state'], array(0,1,2)))return false;
		$data_add = array(
			'crdate' => time(),
			'tstamp' => time(),
			'orders_id' => $orders_id,
			'status' => $state
		);
		$user_info = M('User')->where('deleted=0 and hidden=0 and id='.$orders_info['user_id'])->find();
		//读取模版 发送邮件
		if($user_info['email']){
			//取出订单商品表的关键信息
			$goods_info = M('OrdersGoods')->where('deleted=0 and hidden=0 and orders_id='.$orders_info['id'])->field('title, quantity')->select();
			$goods_list = '';
			foreach($goods_info as $k=>$v){
				$goods_list .= $v['title'].' '.$v['quantity'].'份，';
			}
			$orders_info['goods_list'] = $goods_list ? mb_strcut($goods_list,0,-1,'utf-8') : '无';
			
			$paramters = array(
				'UserName' => $user_info['username'],
				'SiteTel' => $this->Config['tel'],
				'OrderNumber' => $orders_info['number'],
				'Money' => $orders_info['money'],
				'Gold' => $orders_info['gold'],
				'Time' => date('Y-m-d H:i', $orders_info['crdate']),
				'Name' => $orders_info['name'],
				'List' => $orders_info['goods_list'],
			);
			$this->sendMail($user_info['email'], $paramters, 'email_orders_state'.$orders_info['state']);
		}
		
		$user_info = M('User')->where('deleted=0 and hidden=0 and id='.$user_id)->find();
		$name = $user_info['name'] ? $user_info['name'] : $user_info['username'];
				
		//关联订单的表
		$orders_info = M('Orders')->where('deleted=0 and hidden=0 and id='.$orders_id)->find();
		
		//取出订单商品表的关键信息
		$goods_info = M('OrdersGoods')->where('deleted=0 and hidden=0 and orders_id='.$orders_info['id'])->field('title, quantity')->select();
		$goods_list = '';
		foreach($goods_info as $k=>$v){
			$goods_list .= $v['title'].' '.$v['quantity'].'份，';
		}
		$orders_info['goods_list'] = $goods_list ? mb_strcut($goods_list,0,-1,'utf-8') : '无';
		
		if($user_info['email']){
			$paramters = array(
				'UserName' => $user_info['username'],
				'SiteTel' => $this->Config['tel'],
				'OrderNumber' => $orders_info['number'],
				'Money' => $orders_info['money'],
				'Gold' => $orders_info['gold'],
				'Time' => date('Y-m-d H:i', $orders_info['crdate']),
				'Name' => $orders_info['name'],
				'List' => $orders_info['goods_list'],
			);
			$this->sendMail($user_info['email'], $paramters, 'email_orders_payed');
		}
	}
    /**
     * thinkphp 3.2 废弃_get方法重写
     * 重写$this->_get方法
     */
    public function _get($name){
        $name = I('request.' . $name);
        return $name;
    }

    /**
     * thinkphp 3.2 废弃_post方法重写
     * 重写$this->_get方法
     */
    public function _post($name){
        $name = I('request.' . $name);
        return $name;
    }

    /**
     * thinkphp 3.2 废弃_put方法重写
     * 重写$this->_get方法
     */
    public function _put($name){
        $name = I('request.' . $name);
        return $name;
    }

    /**
     * thinkphp 3.2 废弃_request方法重写
     * 重写$this->_get方法
     */
    public function _request($name){
        $name = I('request.' . $name);
        return $name;
    }

    /**
     * thinkphp 3.2 废弃_cookie方法重写
     * 重写$this->_get方法
     */
    public function _cookie($name){
        $name = I('request.' . $name);
        return $name;
    }

    /**
     * thinkphp 3.2 废弃_param方法重写
     * 重写$this->_get方法
     */
    public function _param($name){
        $name = I('request.' . $name);
        return $name;
    }
    
    /**
     * 获取openid
     * @param unknown $code
     */
    public function getOpenid($code){
    	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->searct."&code=".$code."&grant_type=authorization_code";
    	$weixin = https_request($url);
    	$array = json_decode($weixin,true);
    	return $array;
    }
    
    /**
     * autho jump link location
     * @author zhaojie
     * @param unknown $url
     * @param unknown $scope
     */
    public function autho_url($url,$scope){
    	$autho_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri=".urlencode($url)."&response_type=code&scope={$scope}&state=123#wechat_redirect";
    	header('location:'.$autho_url);
    }
    
    /**
     * 获取wechat user info 
     * @author zhaoie
     */
	public function getWechatUserinfo($openid,$access_token){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		$json = https_request($url);
		$wecaht_info = json_decode($json,true);
		return $wecaht_info;
	}
	
	/**
	 * 判断用户是否有进入系统的权限
	 * @param $uid 用户id
	 * @param $type 权限id 
	 * return bool true:有进入页面权限  false:无进入页面权限
	 * */
	public function checkUserAccess($uid,$type){
		$where['deleted']=0;
		$where['lock']=1;
		$where['users']=(int)$uid;
		$accessList=M('user_store')->field('access')->where($where)->select();
		$bool=false;
		foreach ($accessList as $key => $val){
			$access=$val['access'];
			if(!empty($access)){
				$accessArray=explode( ',',$access);
				if(in_array($type,$accessArray)){
					$bool=true;
					break;
				}
			}
		}
		return $bool;
	}
}

?>