<?php
namespace Mobile\Controller;
use Think\Page;
// use Mobile\Controller\BaseController;
/**
 * 商户圈
 * @author zhaojie
 */
class BusinessController extends BaseController{
	private $appid;
	private $secret;
	public $image = array('jpg','png','jpeg','gif');
	
	public function _initialize(){
		$this->appid = C('WECHAT_APPID');
		$this->secret = C('WECHAT_SECRET');
		parent::_initialize();
	}
	
	/**
	 * 商圈首页
	 */
	public function index(){
		$this->assign('title',"我的圈子");
		//授权获得用户信息
		$openid = session('openid');
		if(empty($openid)){
			$openid = I('request.openid');
		}
		if(empty($openid) || $openid == '-1'){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
				$user = M('user')->where(array('openid'=>$openid))->find();
				if(empty($user)){
					//$this->redirect('Index/login');
					//未登录，跳转至登陆页
					cookie('return_url','Business/index',3600);
					$this->redirect('Index/login');
					die;
				}
				session('userid',$user['id'],3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Business/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		if(session('access_token')){
			$access_token = session("access_token");
		}
		$wechat_info = $this->getWechatUserinfo($openid,$access_token);
        $user = M('User')->where(array('openid'=>$openid,'deleted'=>0))->find();
		if(empty($user)){
			//未登录，跳转至登陆页
			cookie('return_url','Business/index',3600);
			$this->redirect('Index/login');
			die;
		}
		$session_images = session('IMAGES');
		$session_images[$user['id']] = $wechat_info['headimgurl'];
		session('IMAGES',$session_images);
		if(empty($user['image'])){
			M('User')->save(array('id'=>$user['id'],'image'=>$wechat_info['headimgurl'],'nickname'=>$wechat_info['nickname']));
		}

		$user_id = I('requet.userid');
		
		//判断用户是否有进入POS页面的权限
		if($this->checkUserAccess($user['id'],4)===false){
			$this->redirect('Index/onAccess', array('pkey' => 4));
			die;
		}

		//查询论坛一级分类
		$where_p = array('parent_id' => 0,'deleted' => 0,'hidden' => 0);
		$P_Obj = M('topic_category');
		$business_category = $P_Obj->where($where_p)->order('sorting asc')->select();
		 
		//$this->assign('business_category',$business_category);
		//获取二级分类并获取最新帖子时间
		foreach($business_category as $key => $val){
			$c_where = array("parent_id"=>$val['id'],"hidden"=>0,"deleted"=>0);
			$t_tmp = $P_Obj->where($c_where)->order('sorting asc')->select();
			if($t_tmp){
				foreach ($t_tmp as $k => $v){
					$where = array("category" => $v['id'],'deleted' => 0,'hidden' => 0);
					$v['newdata'] = M('topic')->where($where)->field('title,user_id,crdate,category')->order('crdate desc')->find();
					$t_business[] = $v;
				}
			}else{
				unset($business_category[$key]);
			}
		}
		$this->assign('business_category',$business_category);
		$this->assign('t_business',$t_business);
		$this->display();
	}
	
	/**
	 * 商圈列表
	 */
	public function lists(){
		$category = I('request.category');
		$P_Obj = M('topic_category');
		$where_c = array("id" => $category,'deleted' => 0,'hidden' => 0);
		//查询分类
		$category_info = $P_Obj->where($where_c)->find();
		$this->assign('category',$category_info);

		$t_obj = M('topic');
		//统计数量
		$count_num = $t_obj->alias('a')->where(array('a.category'=>$category,'a.hidden'=>0,'a.deleted'=>0))->count();
		$this->assign('count_num',$count_num);
		
		//今日帖子
		$today_where = array("a.category"=>$category,'a.crdate'=>array('gt',strtotime(date('Y-m-d'))),'a.hidden'=>0,'a.deleted'=>0);
		$today_num = $t_obj->alias('a')->where($today_where)->count();
		$this->assign('today_num',$today_num);
		
		//查询列表
		$pagesize = 4;
		$page = I('post.page')?I('post.page'):1;
		$field = "c.*,u.id as u,u.name,u.image,u.nickname,u.username";
		$lists = $t_obj->alias('c')->join("left join hq_user as u on u.id = c.user_id")->where(array('c.deleted' => 0, 'c.hidden' => 0,"c.category"=>$category))->field($field)->order("c.top desc,c.hot desc,c.crdate desc")->page($page,$pagesize)->select();
		foreach ($lists as $key => $val){
			$lists[$key]['datestr'] = graydate($val['crdate']);
//			$session_images = session('IMAGES');
//			$lists[$key]['image'] = $session_images[$val['u']];
			$lists[$key]['comments'] = M('topic_reply')->where(array('topic_id'=>$val['id'],'hidden'=>0,'deleted'=>0))->count();
			$count = session('TOPIC_FAVORITE')[$val['id']];
			if(array_key_exists(session('userid'),$count)){
				$lists[$key]['is_favorite'] = true;
			}
		}
		$is_ajax = 0;
		if(count($lists)<$pagesize){
			$is_ajax = 1;
		}
		$this->assign("lists",$lists);
		$this->assign("is_ajax",$is_ajax);
		$this->assign("pagesize",$pagesize);
		$this->assign('title',$category_info['name']);
		$this->assign("category",$category_info);
		//分类点击数+1
		$this->addHit("topic_category", $category);
		$this->display();
	}
	
	/**
	 * 商圈加载更多
	 */
	public function getListPage(){
		$category = I('request.category');
		$P_Obj = M('topic_category');
		$where_c = array("id" => $category,'deleted' => 0,'hidden' => 0);
		//查询分类
		$category_info = $P_Obj->where($where_c)->find();
		$this->assign('category',$category_info);
		$t_obj = M('topic');
		//查询列表
		$pagesize = I('post.pagesize')?I('post.pagesize'):4;
		$page = I('post.page')?I('post.page'):1;
		$action = I('post.action')?I('post.action'):'topic';
		if($action=='topic'){
			$field = "c.*,u.id as u,u.name,u.image,u.nickname,u.username";
			$lists = $t_obj->alias('c')->join("left join hq_user as u on u.id = c.user_id")->where(array('c.deleted' => 0, 'c.hidden' => 0,"c.category"=>$category))->field($field)->order("c.top desc,c.hot desc,c.crdate desc")->page($page,$pagesize)->select();
			foreach ($lists as $key => $val){
				$lists[$key]['datestr'] = graydate($val['crdate']);
//				$session_images = session('IMAGES');
//				$lists[$key]['image'] = $session_images[$val['u']];
				$lists[$key]['comments'] = M('topic_reply')->where(array('topic_id'=>$val['id'],'deleted'=>0,'hidden'=>0))->count();
				$count = session('TOPIC_FAVORITE')[$val['id']];
				if(array_key_exists(session('userid'),$count)){
					$lists[$key]['is_favorite'] = true;
				}
			}
		}else{
			$infoid = I('post.infoid');
			$topic_list = M('topic_reply')->where("topic_id={$infoid} and hidden=0 and deleted=0")->page($page,$pagesize)->order("crdate asc")->select();
			foreach ($topic_list as $key => $val){
				$topic_list[$key]['datestr'] = graydate($val['crdate']);
				$topic_list[$key]['comments'] = M('topic_reply')->where(array('reply_id'=>$val['id'],'deleted'=>0,'hidden'=>0))->count();
				$topic_list[$key]['username'] = M('user')->where(array('id'=>$val['user_id']))->getField('username');
                $topic_list[$key]['image'] = M('user')->where(array('id'=>$val['user_id']))->getField('image');
//				$session_images = session('IMAGES');
//				$lists[$key]['image'] = $session_images[$val['user_id']];
				if(!empty($val['reply_id'])){
					$reply = M('topic_reply')->where(array('id'=>$val['reply_id'],'deleted'=>0,'hidden'=>0))->find();
					$topic_list[$key]['reply_username'] = M('user')->where(array('id'=>$reply['user_id']))->getField('username');
					$topic_list[$key]['reply_remark'] = $reply['remark'];
				}
			}
			$lists = $topic_list;
		}
		if(!empty($lists)){
			$this->assign('lists',$lists);
			$this->assign('action',$action);
			$this->assign('pagelimit',($page-1)*$pagesize);
			$this->display('page');
		}else{
			echo false;
		}
		exit;
	}

	// 帖子回复
	public function reply(){
		if(IS_POST){
			$user_id = session("userid");
			$data = I('request.');
			$data['crdate'] = time();
			$data['tstamp'] = time();
			$data['user_id'] = $user_id;
			$data = array_filter($data);
			$res = M('topic_reply')->add($data);
			if($res){
				$this->success("发表成功！",U('info',array('id'=>$data['topic_id'])));
			}else{
				$this->error("发表失败！");
			}
		}else{
			$this->error("系统错误！");
		}
	}
	
	//商圈帖子详情
	public function info() {
		$this->assign('title',"话题");
		$id = I('request.id');
		
		//访问次数
		$this->addHit("topic", $id);
		
		$info = M('topic')->where("id=$id")->find();//帖子详情
		$info['comments'] = M('topic_reply')->where(array('topic_id'=>$id,'deleted'=>0,'hidden'=>0))->count();
		$info['attach'] = M('attach')->where(array('info_id'=>$id,'info_tab'=>'Topic'))->select();
		$count = session('TOPIC_FAVORITE')[$id];
		if(array_key_exists(session('userid'),$count)){
			$info['is_favorite'] = true;
		}
		$userinfo = M('user')->where("id=".$info['user_id'])->find();
		$page = I('request.page')?I('request.page'):1;
		$pagesize = 3;
		$topic_list = M('topic_reply')->where("topic_id={$id} and hidden=0 and deleted=0")->page($page,$pagesize)->order("crdate asc")->select();
		foreach ($topic_list as $key => $val){
			$topic_list[$key]['datestr'] = graydate($val['crdate']);
			$topic_list[$key]['comments'] = M('topic_reply')->where(array('reply_id'=>$val['id'],'deleted'=>0,'hidden'=>0))->count();
			$topic_list[$key]['username'] = M('user')->where(array('id'=>$val['user_id']))->getField('username');
			$topic_list[$key]['image'] = M('user')->where(array('id'=>$val['user_id']))->getField('image');
//			$session_images = session('IMAGES');
//			$lists[$key]['image'] = $session_images[$val['user_id']];
			if(!empty($val['reply_id'])){
				$reply = M('topic_reply')->where(array('id'=>$val['reply_id'],'deleted'=>0,'hidden'=>0))->find();
				$topic_list[$key]['reply_username'] = M('user')->where(array('id'=>$reply['user_id']))->getField('username');
				$topic_list[$key]['reply_remark'] = $reply['remark'];
			}
		}
		if(I('request.page')){
			$return_data = array(
				"page" => $page,
				"data" => $topic_list
			);
			$this->ajaxReturn($return_data,JSON);
		}
		$is_ajax = 0;
		if(count($topic_list)<$pagesize){
			$is_ajax = 1;
		}
		$path =  '/Uploads/Topic/attach/'. date('Ym',$info['crdate']) . '/';
		$this->assign("is_ajax",$is_ajax);
		$this->assign("pagesize",$pagesize);
		$this->assign("userinfo",$userinfo);
		$this->assign('info',$info);
		$this->assign('path',$path);
		$this->assign('topic_list',$topic_list);
		$this->display();
	}

	//发表帖子
	public function fabiao(){
		if (! empty ($_FILES)){
			$extension = end(explode('.', $_FILES['file']['name']));
			if(!in_array($extension, $this->image)){
				$this->error('文件无效');
			}
			import("Org.Net.UploadFile");
			$savePath = $this->Config['path_upload'] .'Topic/attach/'. date('Ym') . '/';
			$upload_obj = new \UploadFile();
			$res = $upload_obj->uploadOne($_FILES ['file'], $savePath);

			if(!$res){
				die('文件上传失败！');
			}else{
				$data['title'] = $res[0]['name'];
				$data['path'] = $res[0]['savename'];
			}
			$data['info_tab'] = "Topic";
			$data['user_id'] = session("userid");
			$data['crdate'] = $data['tstamp'] = time();
			$result = M("attach")->add($data);
			if($result){
				$attach_ids = session('ATTACHIDS');
				$attach_ids[count($attach_ids)] = $result;
				session('ATTACHIDS',$attach_ids);
				$this->ajaxReturn(array('url'=>U('Business/delbc'),'id'=>$result),json);die;
			}else{
				$this->ajaxReturn(array('error'=>'上传失败！'), 'json');die;
			}
		}
		if(IS_POST){
			$user_id = session("userid");
			if(empty($user_id)){
				$openid = session('openid');
				$user = M('User')->where(array('openid'=>$openid))->find();
				$user_id = $user['id'];
			}
			if(empty($user_id)){
				$this->ajaxReturn(array('status'=>2,'info'=>'亲，您当前处于非登录状态哦，<br/>点击确定，将会回到商圈首页完成登录。','url'=>U('index')));die();
			}
			$data = I('request.');
			$data['crdate'] = time();
			$data['tstamp'] = time();
			$data['hidden'] = $this->Config['is_check'];
			$data['user_id'] = $user_id;
			$res = M('topic')->add($data);
			if($res){
				$attach_ids = session('ATTACHIDS');
				foreach($attach_ids as $attach_id){
					M("attach")->save(array('id'=>$attach_id,'info_id'=>$res));
				}
				session('ATTACHIDS',null);
				if($this->Config['is_check']){
					$this->success("发表成功,等待审核！",U('lists',array('category'=>$data['category'])));
				}else{
					$this->success("发表成功！",U('lists',array('category'=>$data['category'])));
				}

			}else{
				$this->error("发表失败！");
			}
		}else{
			$category = I('request.category');
			$this->assign("category",$category);
			$this->display();
		}
	}

	//删除
	public function delbc(){
		$id = I('post.id');
		$News = M("attach")->where('deleted=0 and hidden=0 and id='.$id)->find();
		$savename = $News['path'];
		$savePath = $this->Config['path_upload'] .'Topic/attach/'. date('Ym',$News['crdate']) . '/';
		delFile($savePath.$savename);
		M("attach")->where('id='.$id)->delete();
	}
	
	public function topiclinks(){
		$id = I('request.id');
		$tbName = "topic";
		$where = array(
			'deleted' => 0,
			'hidden' => 0,
			'id' => $id,
		);
		if (is_numeric($id)) {
			if(!in_array(session('userid'),session('TOPIC_LIKES')[$id])){
				$Info = M($tbName); // 实例化tbName对象
				$reg = $Info->where($where)->setInc('likes'); // like字段加1
				if($reg){
					$likes = M($tbName)->where(array('id'=>$id))->getField('likes');
					$count = session('TOPIC_LIKES');
					$count[$id][count($count[$id])+1] = session('userid');
					session('TOPIC_LIKES',$count);
					$data = array(
						"code" => "1",
						"info" => "success",
						"likes" => $likes,
					);
					$this->ajaxReturn($data);
				}
			}else{
				$this->ajaxReturn(array('code'=>0,'info'=>'您已点过赞'));
			}
		} else {
			$this->error('非法操作！', U('/'));
		}
	}

	public function replylinks(){
		$id = I('request.id');
		$tbName = "topic_reply";
		$where = array(
			'deleted' => 0,
			'hidden' => 0,
			'id' => $id,
		);
		if (is_numeric($id)) {
			if(!in_array(session('userid'),session('REPLY_LIKES')[$id])){
				$Info = M($tbName); // 实例化tbName对象
				$reg = $Info->where($where)->setInc('likes'); // like字段加1
				if($reg){
					$likes = M($tbName)->where(array('id'=>$id))->getField('likes');
					$count = session('REPLY_LIKES');
					$count[$id][count($count[$id])+1] = session('userid');
					session('REPLY_LIKES',$count);
					$data = array(
						"code" => "1",
						"info" => "success",
						"likes" => $likes,
					);
					$this->ajaxReturn($data);
				}
			}else{
				$this->ajaxReturn(array('code'=>0,'info'=>'您已点过赞'));
			}
		} else {
			$this->error('非法操作！', U('/'));
		}
	}

	public function topicfavorite(){
		$id = I('request.id');
		$action = I('request.action');
		$tbName = "topic";
		$where = array(
			'deleted' => 0,
			'hidden' => 0,
			'id' => $id,
		);
		if (is_numeric($id)) {
			$Info = M($tbName); // 实例化tbName对象
			$count = session('TOPIC_FAVORITE')[$id];
			if($action=='inc'){
				$reg = $Info->where($where)->setInc('favorite'); // like字段加1
				$count[$id][session('userid')] = count($count[$id]);
			}else{
				$reg = $Info->where($where)->setDec('favorite'); // like字段加1
				unset($count[$id][session('userid')]);
			}
			session('TOPIC_FAVORITE',$count);
			if($reg){
				$favorite = M($tbName)->where(array('id'=>$id))->getField('favorite');
				$data = array(
					"code" => "1",
					"info" => "success",
					"favorite" => $favorite,
				);
				$this->ajaxReturn($data);
			}
		} else {
			$this->error('非法操作！', U('/'));
		}
	}
}