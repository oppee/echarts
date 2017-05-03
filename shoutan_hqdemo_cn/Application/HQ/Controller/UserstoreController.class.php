<?php
namespace HQ\Controller;
/**
 * @author HeQi
 * @Email info@heqi.hk
 */
class UserstoreController extends BaseController
{

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = 'User_store';		//MODULE_NAME
    //高级筛选
    public $status_all = array(
        array('status'=>'hidden', 'value'=>0, 'title'=>'显示', 'class'=>'success'),
        array('status'=>'hidden', 'value'=>1, 'title'=>'隐藏', 'class'=>'inverse'),
        //array('status'=>'lock', 'value'=>1, 'title'=>'锁定', 'class'=>'success'),
        //array('status'=>'lock', 'value'=>0, 'title'=>'未锁', 'class'=>'inverse')
    );

    protected function init()
    {
        //高级筛选
        //$this->assign('status_all', $this->status_all);
		
		$id = I('request.id') ? I('request.id') : null;
		
		$users = M('User')->where(array('deleted' => 0, 'hidden' => 0))->order('id desc')->field('id as "0",concat_ws("-",mobile,openid) as "1"')->select();
       	$this->assign('users', $users);
			
       	$access = M("user_access")->field("id as '0',title as '1'")->select();
			
        $this->tca = array(
            /* 'hidden' => array(
                'label' => '是否显示',
                'type' => 'toggle_button',
                'data' => '0' //checkbox value
            ),
		 	'lock' => array(
                'label' => '是否绑定',
                'type' => 'radio',
				'default' => 0,
				'data' => array(array(0,'解绑'),array(1,'绑定'))
            ),			 
            'users' => array(
                'label' => '会员账户',
                'type' => 'select',
                'data' => $users
            ),
			'store' => array(
                'label' => '商户号码',
                'type' => 'text',
				'required' => true
            ) */

        	'access' => array(
        		'label' => '操作权限',
        		'type' => 'checkbox',
        		'data' => $access
        	),
           
			
/*            'remark' => array(
                'label' => '信息备注',
                'type' => 'textarea'
            ),*/
        );
    }

    /*
     * 列表页面
     */
    public function index()
    {
        $this->init();	//加载初始化数据
        $Obj = D($this->tbName . " as us"); //实例化对象
        $Where = "us.deleted=0";
        
        //高级搜索 数据状态
        $Where_status = ' and ( ';
        $Where_status_info = '';
        foreach($this->status_all as $k=>$v){
            if(I('request.'.$v['status'])!=''){
                $I_status = I('request.'.$v['status']);
                if(in_array($v['value'], $I_status)){
                    $Where_status_info .= " or us.`".$v['status']."`='".$v['value']."' ";
                }
            }
        }
        if($Where_status_info==''){
            unset($Where_status);
        }else{
            $Where_status .= substr($Where_status_info, 3).' ) ';
            $Where .= $Where_status;
        }
        
        //用户名
        if (I('request.username')!='') {
        	$I_username = trim(I('request.username'));
        	$Where .= " and u.username = '" . $I_username . "'";
        }
        //手机号
        if (I('request.mobile')!='') {
        	$I_mobile = trim(I('request.mobile'));
        	$Where .= " and u.mobile = " . $I_mobile;
        }
            
        //商户ID
        if (I('request.crm_kh_mdbh')!='') {
        	$I_crm_kh_mdbh = trim(I('request.crm_kh_mdbh'));
        	$Where .= " and kh.crm_kh_mdbh = '" . $I_crm_kh_mdbh . "'";
        }
        //商户号
        if (I('request.crm_kh_mdmc')!='') {
        	$I_crm_kh_mdmc = trim(I('request.crm_kh_mdmc'));
        	$Where .= " and kh.crm_kh_mdmc like '%" . $I_crm_kh_mdmc . "%'";
        }
        
        /* if (I('request.keyword')!='') {
            $I_keyword = trim(I('request.keyword'));
            $Where .= " and (kh.crm_kh_dpmc like '%" . $I_keyword . "%' or kh.crm_kh_khjlID = ". $I_keyword . " or u.username = ". $I_keyword . " or u.mobile = ". $I_keyword . ") ";
        } */
		
		$Where .= " and us.lock=1";
		
		
        //控制分页显示条数
        if(I('post.limit_num')!=''){
            session('page_limit_num', I('post.limit_num'));
        }
        //控制列表排序
        $sorting = I('get.sorting') ? I('get.sorting') : 'id';
        $order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;//客户列表默认20条
        
        $Page = new \HQ\Util\Page($Obj->join(C("DB_PREFIX")."user as u on u.id = us.users")->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_mdbh = us.store")->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
        $list = $Obj->join(C("DB_PREFIX")."user as u on u.id = us.users")
        			->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_mdbh = us.store")
        			->where($Where)->order($sorting.' '.$order)
        			->field("us.*,u.username,u.mobile,super_userId,kh.crm_kh_mdbh,kh.crm_kh_mdmc")
        			->limit($Page->firstRow . ',' . $Page->listRows)->select();
					//p($list);
		//echo "<br /><br /><br /><br />";
		//echo $Obj->getLastSql();
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
        $info['access'] = explode(",", $info['access']);

        if (IS_POST) {//dump($_POST);exit;
            foreach ($_POST as $key => $val) {
                //处理POST数据
                if (is_array($val)) {
                    $_POST[$key] = implode(",", $val);
                }
				
                if (@$this->tca[$key]['required'] && !$val) {
                    $this->error($this->tca[$key]['label'] . '是必填项!');
                }
            }
            if(!$_POST){
            	$_POST['access'] = '';
            }
            
            $Obj = D($this->tbName);
            $result = $Obj->create();
            if (!$result) {
                $this->error($Obj->getError());
            } else {
                //if (!isset($_POST['hidden'])) {$Obj->hidden = 1;}
                //if (!isset($_POST['lock'])) {$Obj->lock = 0;}
                $Obj->tstamp = time();
                if ($id) {
					
					/* //根据lock来解绑，处理相关逻辑
					if($Obj->lock!=1){
						M('Smscode')->where(array("users"=>$_POST['users'],'store'=>$_POST['store']))->save(array("lock"=>0,"is_use"=>0,"users"=>'','error_number'=>0));
						$Obj->hidden = 1;
					} */
					
					$Obj->where(array('id' => $id))->save();
					
                    $message = '编辑成功!';
                } else {
                    $Obj->crdate = time();

                    $id = $Obj->add();
                    if (trim($_POST['sorting'] == '')) {
                        $Obj->where(array('id' => $id))->save(array('sorting' => $id));
                    }

                    $message = '添加成功!';
                }
                $this->success($message, U(CONTROLLER_NAME . '/index'));
                exit;
            }
        }
        
        //自动渲染字段
        parent::autoFields($this->tca, $info);
        $this->display('info');
    }
    
    //查看
    public function view(){
    	$id = I("id");
    	$info = M("user_store as us")->join(C("DB_PREFIX")."user as u on u.id = us.users")
        							 ->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_mdbh = us.store")
        							 ->where(array("us.id"=>$id))
        							 ->field("us.*,u.username,u.mobile,kh.crm_kh_mdbh,kh.crm_kh_mdmc")->find();
    	$access = explode(",", $info['access']);
    	foreach ($access as $k=>$v){
    		$list[] = getTitle($v,'user_access','title');
    	}
    	$info["access"] = $list;
    	$this->assign("info",$info);
    	
    	$this->display();
    }
    
    //解绑
    public function dismiss(){
    	$id = I("id");
    	$info = M("user_store")->where(array("id"=>$id))->find();
    	if(empty($info)){
    		$this->ajaxReturn(array("status"=>0,"info"=>'参数错误'),json);
    	}else{
    		//店主不可以解绑
    		//获取用户信息
    		$where_u = array("id"=>$info['users'],"deleted"=>0);
    		$user= M('User')->field('id,super_userId')->where($where_u)->find();
    		if(empty($user)){
    			$rd['status']=-4;
    			$rd['info']="获取数据失败！";
    			$this->ajaxReturn($rd);
    		}
    		$userid=$user['id'];
    		$suserId=$user['super_userId'];
    		if(empty($suserId)){
    			$rd['status']=-20;
    			$rd['info']="不允许解绑店主哦！";
    			$this->ajaxReturn($rd);
    		}
    	}
    	M('Smscode')->where(array("users"=>$info['users'],'store'=>$info['store']))->save(array("lock"=>0,"is_use"=>0,"users"=>'','error_number'=>0));
    	M("user_store")->where(array("id"=>$id))->save(array("lock"=>0,"deleted"=>1));
    	$this->ajaxReturn(array("status"=>1),json);
    }

//删除逻辑写
	public function del(){
	
		$id = I('request.id') ? I('request.id') : null;

		$is_del = $this->Config['is_del'];
		$Obj = D($this->tbName);
		$rs = true;
		if ($is_del) {
			$tstamp = time();
			$idrs = $Obj->where(array("id"=>$id))->save(array('tstamp' =>$tstamp,'deleted' => 1));
			 
			$userStore = M('user_store')->where(array('id'=>$id))->find();
			M('Smscode')->where(array("users"=>$userStore['users'],'store'=>$userStore['store']))->save(array("lock"=>0,"is_use"=>0,"users"=>'','error_number'=>0));
			
			if(!$idrs){
				$rs = false;
			}
		} else {
			$userStore = M('user_store')->where(array('id'=>$id))->find();
			M('Smscode')->where(array("users"=>$userStore['users'],'store'=>$userStore['store']))->save(array("lock"=>0,"is_use"=>0,"users"=>'','error_number'=>0));
			
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