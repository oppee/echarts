<?php
namespace HQ\Controller;
/**
 * @author HeQi
 * @Email info@heqi.hk
 */
class ServiceController extends BaseController {

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = CONTROLLER_NAME;  //MODULE_NAME
    public $image_path = '';
    //高级筛选
    public $status_all = array(
        array('status'=>'hidden', 'value'=>0, 'title'=>'显示', 'class'=>'success'),
        array('status'=>'hidden', 'value'=>1, 'title'=>'隐藏', 'class'=>'inverse')
    );

    protected function init() {
        //高级筛选
        $this->assign('status_all', $this->status_all);
        
        $help = '';
        if (ACTION_NAME == 'edit') {
            $id = $this->_get('id');
            $crdate = M($this->tbName)->where('id = ' . $id)->getField('crdate');
            //图片路径
            $this->image_path = CONTROLLER_NAME . '/source/' . date('Ym', $crdate) . '/';
            $help = '如果不修改密码，此项不需修改';
        }else{
			 $help = '请输入6-16位数字、字母或常用符号！';
		}
        
        $level = M('user_level')->field('id as "0",name as "1"')->select();
		$this->assign('level',$level);
        //$recommend_id = M($this->tbName)->field('id as "0",concat_ws("-",user_number,username) as "1"')->select();
		//$this->assign('recommend_id',$recommend_id);
		
		
        //Demo状态下仅标题字段存在于数据库, 其它字段为示例
        $this->tca = array(
            /*'hidden' => array(
                'label' => '是否显示',
                'type' => 'toggle_button',
                'data' => '0' //checkbox value
            ),
            'is_approval' => array(
                'label' => '审核状态',
                'type' => 'radio',
                //'required' => true,
				'default' => 1,
				'data' => array(array(0,'待审'),array(1,'通过'),array(2,'失败'))
            ),		
			'is_agb' => array(
                'label' => '签约状态',
                'type' => 'radio',
                //'required' => true,
				'default' => 1,
				'data' => array(array(0,'未签'),array(1,'已签'))
            ),	*/
        	'image' => array(
        		'label' => '头像',
        		'type' => 'image1'
        	),
            'username' => array(
                'label' => '用户名',
                'type' => 'text',
                'required' => true
            ),
            'mobile' => array(
                'label' => '手机号',
                'type' => 'text',
				'required' => true
            ),
			
			
            'password' => array(
                'label' => '登录密码',
                'type' => 'password',
				'help' => $help,
				'required' => true
            ),
            'confirm_password' => array(
            	'label' => '确认密码',
            	'type' => 'password',
            	'help' => $help,
				'required' => true
            ),
            
			/*'level' => array(
				'label' => '会员等级',
				'type' => 'radio',
				'required' => true,
				'default' => 1,
				'data' => $level
			),*/
			
			/*'company' => array(
                'label' => '公司名称',
                'type' => 'text',
            ),
            'name' => array(
                'label' => '联系人',
                'type' => 'text'
            ),	
			'tel' => array(
                'label' => '联系电话',
                'type' => 'text',
            ),*/
            'nickname' => array(
                'label' => '昵称',
                'type' => 'text'
            ),
            'merchant_name' => array(
                'label' => '客户名',
                'type' => 'text'
            ),
            'gender' => array(
                'label' => '性别',
                'type' => 'radio',
                'default' => '0',
                'data' => array(array("0","未知"),array("1","男"),array("2","女"))
            ),						
        	 
            /*'openid' => array(
                'label' => '微信号',
                'type' => 'text',
            ),

            'email' => array(
                'label' => '电子邮箱',
                'type' => 'text',
            ),*/
/*            
			
            'remark' => array(
                'label' => '会员备注',
                'type' => 'textarea'
            )*/
        );
		
		if($id){
			unset($this->tca['password']); 
			unset($this->tca['confirm_password']); 
		}
		
    }

    /*
     * 列表页面
     */

    public function index() {
        $this->init(); //加载初始化数据
        $Where = "deleted=0";
        //公司名称
        if (I('request.name')!='') {
        	$I_username = trim(I('request.name'));
        	$Where .= " and company_name like '%" . $I_username . "%'";
        }
        //手机号
        if (I('request.mobile')!='') {
        	$I_mobile = trim(I('request.mobile'));
        	$Where .= " and contact_tel = '" . $I_mobile."'";
        }
        $Obj=M('user_fwxm');
        //控制分页显示条数
        if(I('post.limit_num')!=''){
            session('page_limit_num', I('post.limit_num'));
        }
        //控制列表排序
        $sorting = I('get.sorting') ? I('get.sorting') : 'id';
        $order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;
        $Page = new \HQ\Util\Page($Obj->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
        $list = $Obj->where($Where)->order($sorting.' '.$order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $Page->show()); // 分页显示输出
        $this->display(); // 输出模板
    }

    /**
     * 编辑数据
     */
    public function edit() {
        $this->save();
    }

    /**
     * 保存数据
     */
    public function save() {
        $this->init();
        //编辑数据
        $id = I('request.id') ? I('request.id') : null;
        $info = M($this->tbName)->where('id = ' . $id)->find();
		
       /* if(!empty($info['recommend_id'])){
        	$recommend_id = M($this->tbName)->where(array('id'=>$info['recommend_id']))->field('concat_ws("-",user_number,username) as "1"')->find();
        	$info['recommend_id'] = !empty($recommend_id) ? $recommend_id['1'] : '';
        }*/
		
        if (IS_POST) {
            if ($_POST['password'] != $info['password'] && !empty($_POST['password'])){
                //$_POST['password'] = $this->encryption($_POST['password']);
                if($_POST['password'] != $_POST['confirm_password']){
                	$this->error("密码不一致");
                }
				$_POST['password'] =md5(base64_encode($_POST['password']));
				
            } else {
                unset($_POST['password']);
            }
            unset($_POST['confirm_password']);
			//处理地区选择
        	$_POST['province']=$_POST['chinaprovinces_province'];
        	$_POST['city']=$_POST['chinaprovinces_city'];
			$_POST['area']=$_POST['chinaprovinces_area'];
            //日期处理
            $_POST['birthday']=!empty($_POST['birthday'])?strtotime($_POST['birthday']):'';
            foreach ($_POST as $key => $val) {
                //处理POST数据
                if (is_array($val)) {
                    $_POST[$key] = implode(",", $val);
                }
                if (@$this->tca[$key]['required'] && !$val) {
                    $this->error($this->tca[$key]['label'] . '是必填项!');
                }
            }
			
			$username = I('post.username');
			$mobile = I('post.mobile');
			
			//用户名和手机号不能重复
			if ($id) {
                
				if(M($this->tbName)->where(array('hidden'=>0,'username'=>$username,'deleted'=>0,'id'=>array('neq',$id)))->find()){
					$this->error("该用户名已存在！");
				}
				
				if(M($this->tbName)->where(array('hidden'=>0,'mobile'=>$mobile,'deleted'=>0,'id'=>array('neq',$id)))->find()){
					$this->error("该手机号码已存在！");
				}
                
            }else{
			
				if(M($this->tbName)->where(array('hidden'=>0,'username'=>$username,'deleted'=>0))->find()){
					$this->error("该用户名已存在！");
				}
				
				if(M($this->tbName)->where(array('hidden'=>0,'mobile'=>$mobile,'deleted'=>0))->find()){
					$this->error("该手机号码已存在！");
				}			
			}
			

            //删除文件 begin **********************************
            $path = $this->Config['path_upload'] . CONTROLLER_NAME . '/';
            if ($info && $info['image'] && $_POST['delete_image'] == '1') {
                $file_path = $path . 'source/' . date('Ym', $info['crdate']) . '/';
                
                //$this->delInfoFile($file_path, $info['image']);
                $arrImg = explode("/", $info['image']);
                $this->delInfoFile($file_path, $arrImg[count($arrImg)-1]);
            } else {
                unset($_POST['image']);
            }
            //删除文件 end **********************************
            //文件上传 begin **********************************
            if (count($_FILES) && isset($_FILES['image'])) {
                $save_path = 'source/'; // 设置附件上传目录
                $extent_path = ($info['crdate'] ? date('Ym', $info['crdate']) : date('Ym')) . '/';

                $upload = $this->uploadFile('image', $path, $save_path, $extent_path);

                if (is_array($upload) && $upload['upload']) {
                    //$_POST['image'] = $upload['info'];
                	$_POST['image'] = "http://".$_SERVER['HTTP_HOST'].$path.$save_path.$extent_path.$upload['info'];
                } else {
                    $this->error($upload['info']);
                }
            }
            //文件上传 end **********************************

            $Obj = D($this->tbName);
            $result = $Obj->create();
            if (!$result) {
                $this->error($Obj->getError());
            } else {
                if (!isset($_POST['hidden'])) {
                    $Obj->hidden = 0;
                }
                if (!isset($_POST['top'])) {
                    $Obj->top = 0;
                }

                $Obj->tstamp = time();
                if ($id) {
                    $Obj->where(array('id' => $id))->save();
                    $message = '编辑成功!';
                } else {
                    $Obj->crdate = time();
                    $id = $Obj->add();
                    if (trim($_POST['sorting']) == '') {
                        $number = format_num($id, 8, 'S');
                        $Obj->where(array('id' => $id))->save(array('sorting' => $id, 'number'=>$number));
                    }
                    $message = '添加成功!';
                }

                $this->success($message, U(CONTROLLER_NAME . '/index', 'kept=1'));
                exit;
            }
        }
		//地区赋值的几个变量
		$dq=array(
			'province'=>$info['province'],
			'city'=>$info['city'],
			'area'=>$info['area'],
		);
		$this->assign("dq",$dq);
        //自动渲染字段
        parent::autoFields($this->tca, $info);
        $this->display('info');
    }
    
	//查看
    public function view(){
    	$id = I("id");
    	$info = M("user")->where(array("id"=>$id))->find();
    	
    	$this->assign("info",$info);
    	
    	$this->display();
    }
    
    //重置密码
    public function resetPassword(){
    	$id = I("id");
    	$info = M("user")->where(array("id"=>$id))->find();
    	//$code = substr($info["mobile"], -6);
    	$code = mt_rand(100000, 999999);
    	
    	$sms_code = C('SMS_DXTZ_CZMMCG'); //"SMS_14235240";
    	$SmsParam = "{\"password\":\"{$code}\"}";
    	
    	$res_obj = sendSMS($info["mobile"], $code, $sms_code,$SmsParam);//返回对象
    	$res_array = object_to_array($res_obj);
    	if($res_array['result']['success'] == true){
    		//cookie('code',$code,180);
    		//$this->success("发送成功！");
    		//重置密码并发送给用户
			$password = md5(base64_encode($code));
    		M("user")->where(array("id"=>$id))->save(array("password"=>$password));
    		$this->ajaxReturn(array("status"=>1),json);
    	}else{
    		//$this->error("发送失败！");
    		$this->ajaxReturn(array("status"=>0),json);
    	}
    	 
    }
    

    //删除逻辑写
    public function del(){
    	$id = I('request.id') ? I('request.id') : null;
    
    	$is_del = $this->Config['is_del'];
    	$Obj = M('user_fwxm');
    	$rs = true;
    	if ($is_del) {
    		$idrs = $Obj->where(array("id"=>$id))->save(array('tstamp' =>date('Y-m-d H:i:s'),'deleted' => 1));
    		if(!$idrs){
    			$rs = false;
    		}
    	} else {
    		$Obj->where(array("id"=>$id))->delete();
    	}
    
    	if ($rs) {
    		$this->success('删除成功!', U(CONTROLLER_NAME . '/index', 'kept=1'));
    	} else {
    		$this->error('删除失败，请稍后再试！');
    	}
    }
}

?>