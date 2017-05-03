<?php
namespace HQ\Controller;
/**
 * 后台基础Action
 */
class BaseController extends InitController {

    protected function _initialize() {
        //初始化配置
        $this->initConfig();
		//初始化列表页url参数
		$this->initListPage();
        //初始化当前登录用户信息
        $this->initAdminUser();
        //初始化菜单
        $this->initMenu();
        //初始化公共程序
        $this->initPublic();
    }

    /**
     *  初始化列表页url参数
     */
    final protected function initListPage() {

		$M = MODULE_NAME;
		$C = CONTROLLER_NAME;
		$a = ACTION_NAME;

		//以下情况下执行
		if($a!='index')return true;
		$param = $_SERVER['QUERY_STRING'];
		// $c = strtolower($C);
		$c = $C;
		if($param == 'm='.$M.'&c='.$c.'&a=index&kept=1'){
			$tail = $_SESSION['LIST_PAGE_'.$c];
			$this->redirect($C.'/index', $tail);
		}else{
			$tail = substr($param, strlen('m='.$M.'&c='.$c.'&a=index'));
			session('LIST_PAGE_'.$c, $tail);
		}
		return true;
	}
    /**
     *  初始化当前登录用户信息
     */
    final protected function initAdminUser() {
        switch (I('request.logintype')) {
            case 'login': //登录验证
            	if(md5(I('post.code')) == $_SESSION['verify']){
	                if (I('post.username')) {
	                    $userInfo = D('BeUsers')->where(array('deleted' => 0, 'hidden' => 0, 'username' => I('post.username')))->find();
	                    if ($userInfo) {
	                        $password = self::encryption_backend(I('post.password'));
	                        //验证密码 md5(md5(PASS).AUTHCODE)
	                        if ($password == $userInfo['password']) {
	                            if(I('post.remember')==1){
									cookie('username',$userInfo['username'],3600*24*7);
									cookie('password',I('post.password'),3600*24*7);
									cookie('remember',I('post.remember'),3600*24*7);
									
	                                //如果用户选择了记住密码，记录登录状态就把用户名和加了密的密码放到cookie里面
	                                cookie($this->Config['cookie_prefix'] . "loginname", $userInfo['username'], 3600 * 24 * 7);
	                                cookie($this->Config['cookie_prefix'] . "password", $password, 3600 * 24 * 7);
	                            }else{
									cookie('username',null);
									cookie('password',null);
									cookie('remember',null);
								}
								
	                            session('BEUSER', $userInfo); //登录成功
	                            D('BeUsers')->where(array('id' => $userInfo['id']))->save(array(
	                                'lastlogin' => time(),
	                                'lastloginip' => get_client_ip()
	                            )); //最后登录IP与时间更新

	                            redirect(U('Index/index')); //成功后跳转到控制面板
	                        } else {
	                            $this->error('用户名或者密码错误!');
	                        }
	                    } else {
	                        $this->error('用户不存在!');
	                    }
	                }
            	}else{
            		$this->error('验证码错误!');
            	}
                break;
            case 'logout': //退出登录
                self::logout();
                break;
            default:
                break;
        }

        // 自动登录
        if(isset($_COOKIE[$this->Config['cookie_prefix'] . 'loginname']) && isset($_COOKIE[$this->Config['cookie_prefix'] . 'password'])){
			$loginname = $_COOKIE[$this->Config['cookie_prefix'] . 'loginname'];
			$password = $_COOKIE[$this->Config['cookie_prefix'] . 'password'];
            $Obj = D('BeUsers');
            $userInfo = $Obj->where(array('deleted' => 0, 'hidden' => 0, 'username' => $loginname))->find();
            if($userInfo['id']){
                if($userInfo['password'] == $password){
                    session('BEUSER', $userInfo); //登录成功
                    $Obj->where(array('id' => $userInfo['id']))->save(array(
                        'lastlogin' => time(),
                        'lastloginip' => get_client_ip()
                    )); //最后登录IP与时间更新
                }
            }
        }

        //未登录跳转到登录页面
        if (!session('BEUSER')) {
            if (CONTROLLER_NAME != 'Login') {
				if(IS_AJAX){
					$this->error('登录超时！请重新登录', U('Login/index'));
				}else{
					$this->redirect('Login/index');
				}
            }
        } else {
            $this->BeUser = session('BEUSER');
            $this->assign("BEUSER", session('BEUSER')); //登录成功赋值模板
            if (CONTROLLER_NAME == 'Login' && ACTION_NAME == 'index') {
                redirect(U('Index/index'));
            }
        }
    }
    /**
     * 初始化公共程序
     */
    protected function initPublic() {
        //分页默认显示条数
        $page_limit_num = array(10, 20, 30, 50, 100, 200);
        $this->assign('page_limit_num', $page_limit_num);
        //cookie控制分页显示条数
        if(I('post.limit_num')!=''){
            setcookie($this->Config['cookie_prefix'] . "page_limit_num", I('post.limit_num'), time()+3600 * 24 * 7,'/');
        }elseif(!isset($_SESSION['page_limit_num']) && isset($_COOKIE[$this->Config['cookie_prefix'] . 'page_limit_num'])){
            session('page_limit_num', $_COOKIE[$this->Config['cookie_prefix'] . 'page_limit_num']);
        }
		
        if (ACTION_NAME=='save' && IS_POST) {
			$id = I('request.id') ? I('request.id') : 0;
			//全局判断tca字段是否符合要求
            foreach ($_POST as $key => $val) {
                //处理POST数据
                if ($this->unique[$key]) {
					$exist = M($this->tbName)->where("deleted=0 and hidden=0 and `".$key."`='".$val."' and id!=".$id)->find();
					if($exist){
						$this->error('所填'.$this->unique[$key] . '已存在!');
					}
                }
            }
		}
		//头部显示登录用户头像
		if(isset($_SESSION['BEUSER']['user_id']) && $_SESSION['BEUSER']['user_id']){
			$user_info = M('Employee')->where('id='.$_SESSION['BEUSER']['user_id'])->field('crdate, logo')->find();
			if($user_info['logo']){
				$user_logo = __ROOT__.'/../Uploads/Employee/source/'.date('Ym', $user_info['crdate']).'/'.$user_info['logo'];
				$this->assign('user_logo', $user_logo);
			}
		}
    }

    /**
     * 字段自动渲染, 基于bootstrap
     * @param $tca 字段配置
     * @param $info 数据
     * @param $module 表名
     */
    protected function autoFields($tca, $info = array(), $tableName = CONTROLLER_NAME) {
        $content = '';
        //设置默认为选择
        if(!$info){
            $info['hidden'] = 0; 
            $info['show'] = 1;
        }
        $this->assign('info', $info); //数据数组, 值的调用：$info[$name]
        $tableName = $this->tbName != '' ? $this->tbName : CONTROLLER_NAME;
        if (!empty($tca)) {
            $fieldArray = array();
            $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
            $list = $Model->query("show columns from " . C('DB_PREFIX') . strtolower($tableName));
            foreach ($list as $row) {
                $fieldArray[] = $row['Field'];
            }
            foreach ($tca as $field => $config) {
                //检查字段是否在数据库中
                if (!in_array($field, $fieldArray)) {
                    $config['help'] = '该字段不存在!';
                }

                $this->assign('tca', $config); //tca配置
                $this->assign('name', $field); //字段名
                switch ($config['type']) {
                    case 'text':
                    case 'textarea':
                    case 'radio':
                    case 'checkbox':
                    case 'select':
                    case 'select2':
                    case 'select_tree':
                    case 'select_cascade':
                    case 'select_cascade2':
                    case 'select_chosen':
                    case 'select_toggle':
                    case 'multiselect':
                    case 'toggle_button':
                    case 'rte':
                    case 'datetime':
                    case 'password':
                    case 'files':
                    case 'multiple':
                    case 'multiprice':
                        $content .= $this->fetch('Field:' . $config['type']);
                        break;
                    case 'image':
                        //$url = $this->image_path ? $this->image_path . $info[$field] : CONTROLLER_NAME . '/' . $info[$field];
                        //$thumb_url = $this->thumb_path ? $this->thumb_path . $info[$field] : $this->image_path ? $this->image_path . $info[$field] : CONTROLLER_NAME . '/' . $info[$field];

                        $url = $info[$field];
                        $thumb_url = $info[$field];
                        echo $thumb_url;
                        
                        $this->assign('thumb_url', $thumb_url);
                        $this->assign('url', $url);
                        $content .= $this->fetch('Field:' . $config['type']);
                        break;
                    case 'image1':
                    	$arrImg = explode("/", $info[$field]);
                    	$info[$field] = $arrImg[count($arrImg)-1];
                        //$url = $this->image_path ? $this->image_path . $info[$field] : CONTROLLER_NAME . '/' . $info[$field];
                        //$thumb_url = $this->thumb_path ? $this->thumb_path . $info[$field] : $this->image_path ? $this->image_path . $info[$field] : CONTROLLER_NAME . '/' . $info[$field];

                        $url = $info[$field];
                        $thumb_url = $info[$field];
                        
                        $this->assign('thumb_url', $thumb_url);
                        $this->assign('url', $url);
                        $content .= $this->fetch('Field:' . $config['type']);
                        break;
                    case 'attachment':
                        if ($tableName == "Music") {
                            if ($field == 'play_filename') {
                                $dir = 'play';
                            } else {
                                $dir = 'down';
                            }

                            $url = '../MusicData/' . $dir . '/' . date('Ym', $info['crdate']) . '/' . $info[$field];
                        } else {
                            $url = $this->Config['path_upload'] . CONTROLLER_NAME . "/attach/" . $info[$field];
                        }
                        $this->assign('url', $url);
                        $content .= $this->fetch('Field:' . $config['type']);
                        break;
                    default:
                        break;
                }
            }
        }
        $this->assign('autoFields', $content);
    }
	
    /**
     * 全局删除方法
     * @param null $id 需要删除的记录id
     */
    public function del($id = NULL) {
        $tbName = $this->tbName != '' ? $this->tbName : CONTROLLER_NAME;

        if (CONTROLLER_NAME == 'Beusers' && I('get.type')) {
            $tbName = 'Be_' . I('get.type');
        }

        $id = $id == NULL ? I('get.id') : $id;
        $is_del = $this->Config['is_del'];
        $rs = false;

        if ($is_del == 0) {
            $info = M($tbName)->where('id = ' . $id)->find();
            if ($info['image'] || $info['flash_file']) {
                $this->delFile($info);
            }
            if ($info['category'] && M($tbName.'_category')) {
                $this->updateCategorySum($info['category'], $tbName, 'del', true);
            }
            
            if (M($tbName)->where('id = ' . $id)->delete()) {
                $rs = true;
            }
        } else {
            if (M($tbName)->where('id = ' . $id)->save(array('deleted' => 1,'tstamp' => time()))) {
                $rs = true;
            }
        }

        if ($rs) {
            $this->success('删除成功!', U(CONTROLLER_NAME . '/index', 'kept=1'));
        } else {
            $this->error('删除失败，请稍后再试！');
        }
    }
	//删除附件
    public function deleteAttach() {
        $result = 0;
        if ($id = $this->_get('id')) {
            $model = $this->_get('model');
            if ($model == 'Musiccategory') {
                $MusicCategory = M('MusicCategory');
                $MusicServer = M('MusicServer');
                $info = M('Music')->where('id = ' . $id)->find();
                $play_server = $MusicCategory->where('id ='.$info['category'])->getField('play_server');
                $down_server = $MusicCategory->where('id ='.$info['category'])->getField('down_server');
                $play_folder = $MusicServer->where('id ='.$play_server)->getField('folder');
                $down_folder = $MusicServer->where('id ='.$down_server)->getField('folder');
                $path_down = '../MusicData/'. $play_folder . date('Ym', $info['crdate']) . '/' . $info['down_filename'];
                $path_play = '../MusicData/'. $down_folder . date('Ym', $info['crdate']) . '/' . $info['play_filename'];

                if (is_file($path_down)) {
                    unlink($path_down);
                }
                if (is_file($path_play)) {
                    unlink($path_play);
                }
                M('Music')->where('id = ' . $id)->delete();
            }else{
                $Attach = M('Attach');
                $Obj = M($model);
                $file_info = $Attach->where('id ='.$id)->find();
                $folder_crdate = $Obj->where('id ='.$file_info['info_id'])->getField('crdate');
                $file_path = $this->Config['path_upload'] . $model . '/attach/'.date('Ym', $file_info['crdate']).'/'.$file_info['path'];

                if (is_file($file_path)) {
                    unlink($file_path);
                    $Attach->where('id ='.$id)->delete();
                }
            }
            $result = 1;
        }

        echo $result;
    }

    /**
     * 删除文件方法
     * @param array $info 需要删除的记录
     */
    protected function delFile($info, $model_name = '') {
        $model_name = $model_name ? $model_name : CONTROLLER_NAME;
        //删除图片
        if ($info['image']) {
            $path = $this->Config['path_upload'] . $model_name . '/';
            if ($model_name == 'News' || $model_name == 'Music') {
                foreach (explode(',', $this->Config['thumb_width']) as $k => $v) {
                    $thumb = $path . 'thumb_' . $k . '/' . date('Ym', $info['crdate']) . '/' . $info['image'];
                    if (is_file($thumb)) {
                        unlink($thumb);
                    }
                }

                $source = $path . 'source/' . date('Ym', $info['crdate']) . '/' . $info['image'];
                if (is_file($source)) {
                    unlink($source);
                }
            } else {
                $image = $path . '/' . $info['image'];
                if (is_file($image)) {
                    unlink($image);
                }
            }
        }

        //删除flash
        if ($info['flash_file']) {
            $flash = $path . '/' . $info['flash_file'];
            if (is_file($flash)) {
                unlink($flash);
            }
        }
    }

    /**
     * 全局操作方法 即时更改数据库字段的值
     * @param  $id 需要更改的记录id
     * @param  $field 需要更改的字段
     * @param  $value 需要重写的值
     */
    public function quickedit() {
        $id = I('id');
        $field = I('field');
        $value = I('value');
        //这里不加额外的限制条件 因为列表的数据可能包括已隐藏的项目
        $res = M($this->tbName)->where('id='.$id)->setField($field,$value);
        if($res){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }
	
    /**
     * 全局操作方法
     * @param null $id 需要删除的记录id
     */
    public function op() {
        $ids = $this->_post('ids');
        $op = $this->_post('op');
        $is_del = $this->Config['is_del'];

        $where = array('id' => array('in', $ids));
        //$tbName = $this->tbName != '' ? $this->tbName : CONTROLLER_NAME;
		$tbName = $this->tbName;
        $obj = M($tbName)->where($where);

        switch ($op) {
            case 'hide':	//显示
                $obj->save(array('hidden' => 1));
                break;
            case 'not_hide':	//不显示
                $obj->save(array('hidden' => 0));
                break;
            case 'hot':		//推荐
                $obj->save(array('hot' => 1));
                break;
            case 'not_hot':		//不推荐
                $obj->save(array('hot' => 0));
                break;
            case 'top':		//置顶
                $obj->save(array('top' => 1));
                break;
            case 'not_top':		//不置顶
                $obj->save(array('top' => 0));
                break;
            case 'lock':	//锁定
                $obj->save(array('lock' => 1));
                break;
            case 'not_lock':	//不锁定
                $obj->save(array('lock' => 0));
                break;				
            case 'is_show':		//展示
                $obj->save(array('is_show' => 1));
                break;
            case 'not_is_show':	//不展示
                $obj->save(array('is_show' => 0));
                break;

            case 'type_1':
                $obj->save(array('type' => 1));
                break;
            case 'type_2':
                $obj->save(array('type' => 2));
                break;
            case 'type_3':
                $obj->save(array('type' => 3));
                break;
            case 'start':
                $obj->save(array('start' => 0));
                break;
            case 'not_start':
                $obj->save(array('start' => 1));
                break;
            case 'code':
                $obj->save(array('status' => 1));
                break;
            case 'not_code':
                $obj->save(array('status' => 0));
                break;
            case 'plus':
                $obj->save(array('plus' => 1));
                break;
            case 'not_plus':
                $obj->save(array('plus' => 0));
                break;
            case 'order_state_0':
                $obj->save(array('state' => 0));
                break;
            case 'order_state_1':
                $obj->save(array('state' => 1));
                break;
            case 'order_state_2':
                $obj->save(array('state' => 2));
                break;
            case 'order_state_3':
                $obj->save(array('state' => 3));
                break;
            case 'order_state_4':
                $obj->save(array('state' => 4));
                break;
            case 'order_state_5':
                $obj->save(array('state' => 5));
                break;
            case 'del':
                if ($is_del) {
                    $obj->save(array('deleted' => 1));
                } else {
                    if ($list = $obj->select()) {
                        foreach ($list as $v) {
                            $this->delFile($v);
                        }
                    }
                    M($tbName)->where($where)->delete();
                }
                break;
        }
		//echo M()->getLastSql();
		//die();
        $this->success('操作成功!', U(CONTROLLER_NAME . '/index', 'kept=1'));
    }

    
    /**
     * 按指定分类id查询所有分类记录数(包含所有子分类)
     * @param $category_id 分类id
     * @param $tb_name 表名(与分类表表名关联)
     * @param $recurse 是否递归
     * @param $related 是否关联表(不关联则统计自身表)
     */
    protected function getCategorySum($category_id = 0, $tb_name = 'News', $recurse = true, $related=true, $field='category', $cate_tb_name='') {
    
        $Obj = M($tb_name);
        if($related){
            $sum += $Obj->where('deleted=0 and hidden=0 and `'.$field.'`='.$category_id.' OR FIND_IN_SET('.$category_id.',`'.$field.'`)')->count();
            if($recurse){
				$_Obj = M($cate_tb_name ? $cate_tb_name : $tb_name.'_'.$field);
                $cidArr = $_Obj->where('deleted=0 and hidden=0 and parent_id='.$category_id)->field('id')->select();
                if($cidArr){
                    foreach($cidArr as $k => $v){
                        $sum += $this->getCategorySum($v['id'], $tb_name, $recurse, $related, $field, $cate_tb_name);
                    }
                }
            }
        }else{
            $children = $Obj->where('deleted=0 and hidden=0 and parent_id='.$category_id)->field('id')->select();
            $sum += count($children);
            if($recurse){
                foreach($children as $v){
                    $sum += $this->getCategorySum($v['id'], $tb_name, $recurse, $related, $field, $cate_tb_name);
                }
            }
        }
        return $sum;
        
    }

    /**
     * 按指定分类id更新分类记录数(包含所有父级分类)
     * @param $category_id 分类id
     * @param $tb_name 表名(与分类表表名关联)
     * @param $addOrDel 是新增一条数据还是删除一条数据,可为Boolean
     * @param $recurse 是否递归
     */
    protected function updateCategorySum($category_id=0, $tb_name='News', $addOrDel='add', $recurse=true, $related=true, $field='category', $cate_tb_name='') {
        // $Obj = M($tb_name.'_category');
        $Obj = $related ? M($cate_tb_name ? $cate_tb_name : $tb_name.'_'.$field) : M($tb_name);
        $sum = $this->getCategorySum($category_id, $tb_name, $recurse, $related, $field, $cate_tb_name);
        $sum = ($addOrDel == 'del'||$addOrDel === false)?$sum - 1:$sum + 1;
        $res = $Obj->where('deleted=0 and hidden=0 and id='.$category_id)->save(array('total'=>$sum));
        if($recurse){
            $pid = $Obj->where('deleted=0 and hidden=0 and id='.$category_id)->getField('parent_id');
            if($pid){
                $res = $this->updateCategorySum($pid, $tb_name, $addOrDel, $recurse, $related, $field, $cate_tb_name);
            }
        }
        return $res;
        
    }
    
    //更新所有该分类下的记录总数(是否无限递归,是否关联表.不关联则查询自身.news_category.banner_position...)
    public function updateFieldTotal($tb_name='News', $recurse=true, $related=true, $field='category', $cate_tb_name=''){
        $Obj = $related ? M($cate_tb_name ? $cate_tb_name : $tb_name.'_'.$field) : M($tb_name);
        $id_arr = $Obj->field('id')->select();
        $update_num = 0;
        foreach($id_arr as $v){
            $tmp_num = $this->getCategorySum($v['id'], $tb_name, $recurse, $related, $field, $cate_tb_name);
            $res = $Obj->where('deleted=0 and hidden=0 and id='.$v['id'])->setField(array('total'=>$tmp_num));
            if($res)$update_num++;
        }
        return $update_num;//循环操作更新的分类数
    }

    /**
     * 发送短信
     * @param mobiles   电话号码（数组）
     * @param content   短信内容
     */
    protected function sendSMS($mobiles, $cotent) {
        if (!is_array($mobiles) || count($mobiles) == 0 || empty($cotent)) {
            return false;
        }

        foreach ($mobiles as $v) {
            if (!is_numeric($v)) {
                return false;
            }
        }

        $cotent = '【零购网】' . $cotent;

        set_time_limit(0);
        import('Org.Net.SMSClient');

        //网关地址
        $gwUrl = $this->Config['sms_url'];

        //序列号,请通过亿美销售人员获取
        $serialNumber = $this->Config['sms_serial'];

        //密码,请通过亿美销售人员获取
        $password = $this->Config['sms_pwd'];

        //登录后所持有的SESSION KEY，即可通过login方法时创建
        $sessionKey = 'SMS';

        //连接超时时间，单位为秒
        $connectTimeOut = 2;

        //远程信息读取超时时间，单位为秒
        $readTimeOut = 10;

        /**
        $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
        $proxyport		可选，代理服务器端口，默认为 false
        $proxyusername	可选，代理服务器用户名，默认为 false
        $proxypassword	可选，代理服务器密码，默认为 false
         */
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $client = new Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $client->setOutgoingEncoding("UTF-8");

        if ($client->login() != 0 || $client->sendSMS($mobiles, $cotent) != 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 发送多组短信
     * @param content   短信内容（数组） array('mobiles' => array(), 'content' => '')
     */
    protected function sendSMSMutil($content) {
        if (!is_array($content)) {
            return false;
        }

        set_time_limit(0);
        import('Org.Net.SMSClient');

        //网关地址
        $gwUrl = $this->Config['sms_url'];

        //序列号,请通过亿美销售人员获取
        $serialNumber = $this->Config['sms_serial'];

        //密码,请通过亿美销售人员获取
        $password = $this->Config['sms_pwd'];

        //登录后所持有的SESSION KEY，即可通过login方法时创建
        $sessionKey = 'SMS';

        //连接超时时间，单位为秒
        $connectTimeOut = 2;

        //远程信息读取超时时间，单位为秒
        $readTimeOut = 10;

        /**
        $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
        $proxyport		可选，代理服务器端口，默认为 false
        $proxyusername	可选，代理服务器用户名，默认为 false
        $proxypassword	可选，代理服务器密码，默认为 false
         */
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $client = new Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $client->setOutgoingEncoding("UTF-8");

        if ($client->login() != 0) {
            return false;
        }

        foreach ($content as $v) {
            if (!is_array($v) || !isset($v['mobiles']) || !isset($v['content'])) {
                continue;
            }

            $cotent = '【零购网】' . $v['content'];
            $client->sendSMS($v['mobiles'], $cotent);
        }

        return true;
    }

    /**
     * 获取多条短信
     *@param $mobile       手机号码
     *@param $send_mes  模板参数数组
     * @param $cont          模板参数数组键名
     *@param $type           短信模板名称
     */
    public function sendManyMes($send_mes, $cont, $type){
        //从数据库中取出相应短信模板内容
        $sms_info=M('Sms')->where(array('key' => $type))->find();
        if ($type && (!$this->Config[$type] || !$sms_info)) {
            return false;
        }
        //$send_mes必须为固定格式的数组
        if (!is_array($send_mes)) {
            return false;
        }

        foreach($send_mes as $key => $val){

            $sms_cont = $sms_info['content'];
            foreach($val[$cont] as $k => $v){
                $sms_cont = str_replace('{###' . $k . '###}', $v, $sms_cont);
            }
            $send_mes[$key][$cont]=$sms_cont;
        }

        //调用发短信的函数
        return $this->sendSMSMutil($send_mes);
    }


    /*
     * 数字转换
     * @param $value 数值
     * @param $precision 小数点后保留几位
     */
    function numConversion($value, $precision = 2){
        // 从大到小
        $unit_arr  = array('100000000' => '亿', '10000' => '万');
        $value = sprintf('%.' . $precision . 'f', intval($value));
        $state = false;
        if($value < 0){
            $value = abs($value);
            $state = true;
        }
        foreach ($unit_arr as $k => $v) {
            $k = intval($k);
            if($value >= $k){
                $value = sprintf('%.' . $precision . 'f', $value/$k) . $v;
                break;
            }
        }

        if($state){
            $value = '-' . $value;
        }

        return $value;
    }
    /**
     * 获取子分类ID
     * @param $cid 文章类别ID
     */
    protected function getSubId($cid, $tabName = 'News_category') {
        $where = array('deleted' => 0,'hidden' => 0,'parent_id' => $cid);
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
	 * 获取登录用户对应的员工所在的职位权限组，并获取对应的权限组所能查看的项目id或者员工id
	 * @param $id 后台用户ID(默认为当前登录用户ID)
	 */
	public function getBeuserPowerOfProject($id=0){
		if($beuser_id = $id){
			$employee_id = M('BeUsers')->where('deleted=0 and hidden=0 and id='.$beuser_id)->getField('user_id');
		}else{
			$employee_id = $_SESSION['BEUSER']['user_id'];
		}
		if(!$employee_id)return false;
		$position_id = M('Employee')->where('deleted=0 and hidden=0 and id='.$employee_id)->getField('position');
		if(!$position_id)return false;
		$type = M('PositionCategory')->where('deleted=0 and hidden=0 and id='.$position_id)->getField('type');
		if(!$type)return false;
		
		$power = array(
			'powertype'=>'null',
			'powerfield'=>'0'
		);
		if($type==3){
			//管理层 全员可见
			$power['powertype'] = 'all';
		}elseif($type==1){
			//基层 仅自己可见
			$power['powertype'] = 'employee';
			$power['powerfield'] = $employee_id;
		}elseif($type==2){
			//PM 可见所负责的项目
			$power['powertype'] = 'project';
			$power['powerfield'] = M('Project')->where('deleted=0 and hidden=0 and leader='.$employee_id)->getField('id',true);
		}
		return $power;
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
				'status' => $orders_info['state']
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
     * 处理城市的特殊情况，特殊情况存相应的省份
     * @param         string         $province
     * @param         string         $city
     */
    public function doCity($province,$city){
    	$special = array("市辖区","县","省直辖行政单位","省直辖县级行政单位","市");
    	if(in_array($city, $special)){
    		$city = $province;
    	}
    	return $city;
    }

    /**
     * PHP将阿拉伯数字转换成汉字大写，支持小数点
     * @param $num
     * @param bool $mode
     * @return string
     */
    public function ch_num($num,$mode=true) {
        $char = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
        $dw = array("","拾","佰","仟","","萬","億","兆");
        $dec = "點";
        $retval = "";
        if($mode)
            preg_match_all("/^0*(\d*)\.?(\d*)/",$num, $ar);
        else
            preg_match_all("/(\d*)\.?(\d*)/",$num, $ar);
        if($ar[2][0] != "")
            $retval = $dec . $this->ch_num($ar[2][0],false); //如果有小数，则用递归处理小数
        if($ar[1][0] != "") {
            $str = strrev($ar[1][0]);
            for($i=0;$i<strlen($str);$i++) {
                $out[$i] = $char[$str[$i]];
                if($mode) {
                    $out[$i] .= $str[$i] != "0"? $dw[$i%4] : "";
                    if($str[$i]+$str[$i-1] == 0)
                        $out[$i] = "";
                    if($i%4 == 0)
                        $out[$i] .= $dw[4+floor($i/4)];
                }
            }
            $retval = join("",array_reverse($out)) . $retval;
        }
        return $retval;
    }
	
}