<?php
namespace HQ\Controller;
use Think\Controller;
/**
 * 后台基础Action
 */
class InitController extends Controller {

    public $mid = 0; //页面menu id
    public $Config = array(); //全局站点配置
    public $BeUser = array(); //后台用户数据

    /**
     * 注销登录
     */
    protected function logout() {
        session('BEUSER', NULL);
        cookie($this->Config['cookie_prefix'] . 'loginname', null);
        cookie($this->Config['cookie_prefix'] . 'password', null);
        $this->redirect(U('Login/index'));
    }

    /**
     * 初始化站点配置
     */
    protected function initConfig() {
        $this->Config = C('Config'); //数据来自行为扩展赋值
        $this->Config['path_upload'] = ""; // C('PATH_UPLOAD') ? C('PATH_UPLOAD') : './Uploads/';
        $this->Config['cookie_prefix'] = C('COOKIE_PREFIX') ? C('COOKIE_PREFIX') : 'HQ_';
        $this->assign('Config', $this->Config);
    }

    /**
     * 初始化菜单
     */
    protected function initMenu() {
        //解析菜单ID begin
        if (I('request.mid')) { //指定menu id
            $mid = I('request.mid');
        } elseif (I('request.alias')) { //别名
            $mid = M('Menu')->where(array('deleted' => 0, 'app' => 'Admin', 'alias' => I('request.alias')))->getField('id');
        } else { //内部页面
            $mid = M('Menu')->where(array('deleted' => 0, 'app' => 'Admin', 'model' => CONTROLLER_NAME, 'action' => ACTION_NAME))->getField('id');
        }
        //end
        //检查用户权限
        if ((isset($this->BeUser['id']) && $mid > 1 && !M('Access_user')->where(array('user_id' => $this->BeUser['id'], 'menu_id' => $mid))->count()) && (isset($this->BeUser['usergroup']) && $this->BeUser['usergroup'] > 1 && $mid > 1 && !M('Access')->where(array('group_id' => $this->BeUser['usergroup'], 'menu_id' => $mid))->count())) {
            $this->error('无操作权限!');
        }
        //检查用户商品操作权限

        if (ACTION_NAME == "save") {
            if (I('request.id') != "") {
                $this->assign('PageName', '编辑');
            } else {
                $this->assign('PageName', '添加');
            }
        } else {
            $menuInfo = M('Menu')->where(array('id' => $mid))->find();
            $this->assign('PageName', $menuInfo["title"]);
        }



        $this->setMid($mid);

        $pid=0;
        self::makeMenu2($pid,0,0,CONTROLLER_NAME.'-'.ACTION_NAME);
        $menuContent = self::makeMenu(0,0,CONTROLLER_NAME.'-'.ACTION_NAME,$pid);
        $this->assign('menu', $menuContent);
    }

    /**
     * 解析菜单id
     */
    private function setMid($mid) {
        $menuInfo = M('Menu')->where(array('id' => $mid))->find();
        $this->assign('ParentPageName', $menuInfo["title"]);
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
     * 无限级菜单生成
     */
    private function makeMenu($pid = 0, $level = 0, $curId = '',$parentPid = 0) {
        $output = '';
        //菜单权限 begin
        $where = '';
        if (isset($this->BeUser['usergroup']) && $this->BeUser['usergroup'] > 1) {
            $where = " and (id=1 or id in(select menu_id from " . C('DB_PREFIX') . "access where group_id=" . $this->BeUser['usergroup'] . "))";
        }
        //end
        $result = M('Menu')->where("deleted=0 and hidden=0 and app='Admin' and parent_id='{$pid}'" . $where)->order('sorting asc')->select();
        if ($result) {
            foreach ($result as $info) {
                $cur=explode('-',$curId);
                $isCur=false;
                if($info['model']==$cur[0] && $info['action']==$cur[1]){$isCur=true;}
                //menu target
                $target = $info['target'] ? 'target="' . $info['target'] . '"' : '';
                //menu href
                switch ($info['type']) {
                    case 1: //1.内部链接
                        $href = U($info['model'] . '/' . $info['action'], $info['param']);
                        break;
                    case 2: //2.外部链接
                        $href = $info['url'];
                        break;
                    case 3: //3.常规页面
                        if ($info['app'] == 'Admin') {
                            $href = U('Index/index', array('alias' => $info['alias']));
                        }
                        if ($info['app'] == 'Home') {
                            $href = $info['alias'] . C('TMPL_TEMPLATE_SUFFIX');
                        }
                        break;
                    default: //0.普通菜单
                        $href = 'javascript:void(0);';
                        $target = '';
                        break;
                }
                //parent menu
                $isParent=($info['id']==$parentPid?true:false);
                $submenu = self::makeMenu($info['id'], $level + 1,$curId,$parentPid); //子菜单
                $output .= ($this->mid == $info['id'] ? '<li class="level' . $level . ' active'.($isParent?' active ':'').'" '.($isCur?'style="display:block;"':'').'>' : '<li class="level' . $level . ($isParent?' active':'').'">'); //选中menu
                $output .= '<a href="' . $href . '" ' . $target . '>'; //菜单target
                $output .= $info['icon'] ? '<i class="' . $info['icon'] . '"></i>' : ''; //图标
                $output .= '<span class="title">' . $info['title'] . '</span>'; //标题
                $output .= $submenu ? '<span class="arrow '.($isParent?'open':'').'"></span>' : ''; //子菜单箭头
                $output .= '</a>';
                $output .= $submenu;
                $output .= '</li>';
            }
            //子菜单
            if ($level > 0) {
                $output = '<ul class="sub-menu" '.($isCur?'style="display:block;"':'').'>' . $output . '</ul>';
            }
        }
        return $output;
    }
    //获取一级菜单，添加选中状态
    private function makeMenu2(&$r=0,$pid = 0, $level = 0,$curId='') {
        $where = '';
        if (isset($this->BeUser['usergroup']) && $this->BeUser['usergroup'] > 1) {
            $where = " and (id=1 or id in(select menu_id from " . C('DB_PREFIX') . "access where group_id=" . $this->BeUser['usergroup'] . "))";
        }

        $result = M('Menu')->where("deleted=0 and hidden=0 and app='Admin' and parent_id='{$pid}'" . $where)->order('sorting asc')->select();
        if ($result) {
            foreach ($result as $info) {
                $cur=explode('-',$curId);
                if($info['model']==$cur[0] && $info['action']==$cur[1]){
                    $r=$pid;
                    return;
                }
                $submenu = self::makeMenu2($r,$info['id'], $level + 1,$curId); //子菜单
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
    //后台台用户加密
    protected function encryption_backend($text) {
        return md5(md5($text) . C('AUTHCODE_BACKEND'));
    }

    /**
     * 无限分类
     */
    protected function getCategoryTree($pid = 0, $current = 0, $tb_name = 'News_category') {
        import("@.Util.Category");
        $tree = array();
        $where = "deleted = 0 AND hidden = 0";
        if ($pid) {
            $where = "parent_id = $pid AND deleted = 0 AND hidden = 0";
        }

        if ($rs = M($tb_name)->where($where)->select()) {
            $params = array(
                'data' => $rs,
                'title' => 'name',
                'id' => 'id',
                'currentId' => $current,
                'parent_category' => 'parent_id'
            );

            $category = new \UnlimitCategory($params);
            $tree = $category->create_tree_select();
        }

        return $tree;
    }

    /**
     * 获取当前无限级别路径
     */
    protected function getPathAndClass($id, $model_name = "Menu", $down = false) {
        $list = $item = array();
        $where = "deleted = 0 AND hidden = 0 AND id = " . $id;

        if ($rs = M($model_name)->where($where)->find()) {
            $item = array(
                'id' => $rs['id'],
                'alias' => $rs['alias'],
                'type' => $rs['type'],
            );

//            if ($model_name == 'Menu') {
//                $item['title'] = $rs['title'];
//                $item['image'] = $rs['image'];
//                $item['url'] = $this->getMenuLink($rs);
//            } elseif ($model_name == 'Goods_category') {
//                $item['title'] = $rs['name'];
//                $item['url'] = '<a href="' . U('Goods/lists/', 'id=' . $item['id']) . '">' . $rs['name'] . '</a>';
//            } elseif ($model_name == 'News_category') {
//                $item['title'] = $rs['name'];
//                $item['url'] = '<a href="' . U('News/lists/', 'id=' . $item['id']) . '">' . $rs['name'] . '</a>';
//            }

            if (!in_array($item, $list)) {
                $list[] = $item;
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

    /**
     * 文件上传 
     */
    public function uploadfiles() {
        $key = 'files';
        if (CONTROLLER_NAME == 'Musiccategory') {
            $path = '../MusicData/down/' . date('Ym') . '/';
        } else {
            $path = $this->Config['path_upload'] . CONTROLLER_NAME . '/attach/' . date('Ym') . '/';
        }

        $upload = $this->uploadMultipleFiles($key, $path);

        $result = array();
        if ($upload['upload']) {
            $result['files'][] = array(
                'name' => $upload['info'][0]['savename'],
                'size' => $upload['info'][0]['size'],
                'type' => $upload['info'][0]['type'],
                'title' => $upload['info'][0]['name']
            );
        }

        echo json_encode($result);
    }

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

//                if (is_file('..' . $path[1] . $info['play_filename'])) {
//                    unlink('..' . $path[1] . $info['play_filename']);
//                }

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
     * 文件上传
     * @param $key           上传字段名
     * @param $path          上传主路径('/'结尾)
     * @param $save_path     主路径下的文件夹('/'结尾)
     * @param $extent_path   扩展文件夹('/'结尾)
     * @param $thumb_width   缩略图宽度(逗号隔开的字符串数字)
     * @param $thumb_height  缩略图高度(逗号隔开的字符串数字)
     * @param $type         文件类型限制
     */
    public function uploadFile($key, $path, $save_path = '', $extent_path = '', $thumb_width = '', $thumb_height = '', $type = array('jpg', 'gif', 'png', 'jpeg', 'mp3','xls','xlsx','doc','docx','ppt')) {
    	if(C('IS_SFTP')){
    		//开启了sftp上传
    		$ssh=returnSsh();
    		$remote = date("Ymd").'/';
    		if(empty($thumb_width)){
    			$thumb_width='file';
    		}
    		$_filesource = $_FILES[$key]["tmp_name"];
    		$extension = end(explode('.', $_FILES[$key]['name']));
    		$data = file_get_contents($_filesource,"rb");
    		if (empty($data)) {
    			$rd['status']=-10;
    			$rd['info']="请选择头像";
    			$this->ajaxReturn($rd);
    		}
    		$ShareImg = $ssh->put_content($remote, $data , '', $extension);
    		if($ShareImg){
    			//$res[0]['name'] = $_FILES['file']['name'];
    			//$res[0]['savename'] = C('SFTP_DOMAIN').$ShareImg;
    			$result = array(
    					'upload' => true,
    					'is_sftp' => true,
    					'info' => C('SFTP_DOMAIN').$ShareImg
    			);
    		} else { // 上传成功 获取上传文件信息
    			$result = array(
    					'upload' => false,
    					'is_sftp' => true,
    					'info' => "上传失败"
    			);
    		}
    	}else{
	        import('Org.Net.UploadFile');
	        $upload = new \UploadFile(); // 实例化上传类
	        /* import('Org.Net.UploadFile');// 导入转化拼音类
	        $upload = new \Org\Net\UploadFile(); // 实例化上传类 */
	        $upload->allowExts = $type; //设置附件上传类型
	        $upload->maxSize = 10240000; // 设置附件上传大小
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
    	}
        return $result;
    }

    /**
     * 文件上传
     * @param $key           上传字段名
     * @param $path          上传主路径('/'结尾)
     * @param $save_path     主路径下的文件夹('/'结尾)
     * @param $extent_path   扩展文件夹('/'结尾)
     * @param $type         文件类型限制
     */
    public function uploadMultipleFiles($key, $path, $save_path = '', $extent_path = '', $type = array('jpg', 'gif', 'png', 'jpeg', 'mp3')) {
        if (count($_FILES[$key]['name']) > 10) {
            return array(
                'upload' => false,
                'info' => '单次上传的数量不能超过10个！'
            );
        }
        import('Org.Net.UploadFile');// 导入类
        //$upload = new \Org\Net\UploadFile(); // 实例化上传类
        $upload = new \UploadFile(); // 实例化上传类
        $upload->allowExts = $type; //设置附件上传类型
        $upload->maxSize = 1024*1000*1000*20; // 设置附件上传大小20M
        $upload->savePath = $path . $save_path . $extent_path; // 设置附件上传目录
        $upload->uploadReplace = false; //存在同名是否覆盖
        $upload->saveRule = 'uniqid'; //上传文件命名规则, 没有定义命名规则，则保持文件名不变

        $result = array();
        if ($upload->upload($key)) { // 上传错误提示错误信息
            $rs = $upload->getUploadFileInfo();
            $result = array(
                'upload' => true,
                'info' => $rs
            );
        } else { // 上传成功 获取上传文件信息
            $result = array(
                'upload' => false,
                'info' => $upload->getErrorMsg()
            );
        }
        return $result;
    }

    protected function delInfoFile($path, $file_name) {
        $file_name = $path . $file_name;
        if (is_file($file_name)) {
            unlink($file_name);
        }
    }

    /**
     * 获取分类至顶级目录
     * @param $currentId
     * @param mixed|string $tbName
     * @param string $dirField
     * @return string
     */
    protected function getCateDir($currentId, $tbName = CONTROLLER_NAME, $dirField = 'alias', $parentFiled = 'parent_id') {
        $currentInfo = M($tbName)->where(array('id' => $currentId))->find();
        if (!$currentInfo[$dirField] || $currentInfo[$dirField] == '') {
            $currentInfo[$dirField] = 'default';
        }
        if (!$currentInfo['parent_id'] || $currentInfo['parent_id'] == 0) {
            return $currentInfo[$dirField] . '/';
        } else {
            return $this->getCateDir($currentInfo[$parentFiled], $tbName, $dirField, $parentFiled) . $currentInfo[$dirField] . '/';
        }
    }

    public function clearCache() {
        $result = 0;

        if (is_dir(RUNTIME_PATH) && $this->rmdirr(RUNTIME_PATH)) {
            $result = 1;
        }
        echo $result;
    }

    /**
     * 删除文件夹
     * @param $dirname 文件目录
     */
    protected function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if ($dir) {
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
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
	
	//删除文件	
	public function del(){
	 	$path = trim(I('get.p'));
	 	$this->delFile($path);
		echo 'ok';
	}
	
	/*
	 * 删除文件
	 * @param $path  
	 * @return $info
	 */
	protected function delFile($path) {
		$flag="";
		if(!is_dir($path)){
			$pathInfo = pathinfo($path);	 
			$flag = unlink($pathInfo['dirname'].'/'.$pathInfo['basename']);
			
		}else {//否则就是目录，调用方法进行删除该目录下的所有文件
			$flag = $this->deldir($path);
		}
		return $flag;
	}

	function deldir($dir) {
		//先删除目录下的文件：
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if (rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
}