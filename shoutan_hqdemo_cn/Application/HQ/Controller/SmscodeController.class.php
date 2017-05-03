<?php
namespace HQ\Controller;
/**
 * @author HeQi
 * @Email info@heqi.hk
 */
class SmscodeController extends BaseController
{

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = CONTROLLER_NAME;		//MODULE_NAME
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
        $this->assign('status_all', $this->status_all);
        $this->tca = array(
            'hidden' => array(
                'label' => '是否显示',
                'type' => 'toggle_button',
                'data' => '0' //checkbox value
            ),
			'is_use' => array(
                'label' => '是否绑定',
                'type' => 'toggle_button',
                'data' => '1' //checkbox value
            ),
            'mobile' => array(
                'label' => '登记手机',
                'type' => 'text',
				'required' => true
            ),
			'store' => array(
                'label' => '商户号码',
                'type' => 'text',
				'required' => true
            ),			
            'code' => array(
                'label' => '激活码',
                'type' => 'text',
				'required' => true
            ),
			
			'error_number' => array(
                'label' => '错误次数',
                'type' => 'text'
            ),
			'codetime' => array(
                'label' => '更新时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '激活码更新的时间'
            ),
			'users' => array(
                'label' => '所属账户',
                'type' => 'text',
				'readonly' => true,
				'help' => '绑定的账户ID'
            ),
			'error_time' => array(
                'label' => '错误时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '最后一次错误时间'
            )			
			
			
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
        $Obj = D($this->tbName); //实例化对象
        $Where = "deleted=0";
        
        //高级搜索 数据状态
        $Where_status = ' and ( ';
        $Where_status_info = '';
        foreach($this->status_all as $k=>$v){
            if(I('request.'.$v['status'])!=''){
                $I_status = I('request.'.$v['status']);
                if(in_array($v['value'], $I_status)){
                    $Where_status_info .= " or `".$v['status']."`='".$v['value']."' ";
                }
            }
        }
        if($Where_status_info==''){
            unset($Where_status);
        }else{
            $Where_status .= substr($Where_status_info, 3).' ) ';
            $Where .= $Where_status;
        }
            
        if (I('request.keyword')!='') {
            $I_keyword = trim(I('request.keyword'));
            $Where .= " and (name like '%" . $I_keyword . "%' or id like '%". $I_keyword . "%') ";
        }
        //控制分页显示条数
        if(I('post.limit_num')!=''){
            session('page_limit_num', I('post.limit_num'));
        }
        //控制列表排序
        $sorting = I('get.sorting') ? I('get.sorting') : 'id';
        $order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;//客户列表默认20条
        
        $Page = new \HQ\Util\Page($Obj->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
        $objList = $Obj->where($Where)->order($sorting.' '.$order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		/*foreach ($objList as $k => $v) {
			if(time()-$v['codetime'] <= 60*60 || $v['is_use']==1){
				$objList[$k]['is_use'] = 1;
			} 
		}*/
        $this->assign('objList', $objList); // 赋值数据集
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
		
		if($info["error_time"]>0){
			$info["error_time"]= date("Y-m-d H:i:s",$info["error_time"]);
		}
		if($info["codetime"]>0){
			$info["codetime"]= date("Y-m-d H:i:s",$info["codetime"]);
		}

        if (IS_POST) {
            foreach ($_POST as $key => $val) {
                //处理POST数据
                if (is_array($val)) {
                    $_POST[$key] = implode(",", $val);
                }
                if (@$this->tca[$key]['required'] && !$val) {
                    $this->error($this->tca[$key]['label'] . '是必填项!');
                }
            }
            
            
            $Obj = D($this->tbName);
            $result = $Obj->create();
            if (!$result) {
                $this->error($Obj->getError());
            } else {
                if (!isset($_POST['hidden'])) {$Obj->hidden = 1;}
				if (!isset($_POST['is_use'])) {$Obj->is_use = 0;}
				
                if (!isset($_POST['lock'])) {$Obj->lock = 0;}
				
                $Obj->tstamp = time();
                if ($id) {
                    $Obj->where(array('id' => $id))->save();
                    $message = '编辑成功!';
                } else {
                    $Obj->crdate = time();
					$Obj->codetime = time();
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
    
    //重新生成
    public function rebuild(){
    	$id = I("id");
    	$phone = M("smscode")->where(array("hidden"=>0,"deleted"=>0,'id'=>$id))->getField("mobile");
    	
    	$code = mt_rand(100000, 999999);
    	//发送商户激活码
    	$sms_code = C('SMS_DXTZ_JHM');  //"SMS_13190848";
    	$SmsParam = "{\"code\":\"{$code}\"}";
    	
    	$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
    	$res_array = object_to_array($res_obj);
    	if($res_array['result']['success'] == true){
    		//cookie('code',$code,180);
    		$data = array(
    				"code" => $code,
    				"codetime" => time(),
    				"lock" => 0,
    				"error_number" => 0,
    		);
    		M("smscode")->where(array("id"=>$id))->save($data);
    		$this->ajaxReturn(array("status"=>1,"info"=>"重新生成成功","url"=>U(CONTROLLER_NAME . '/index')),json);
    	}else{
    		$this->ajaxReturn(array("status"=>0,"info"=>"重新生成失败","url"=>U(CONTROLLER_NAME . '/index')),json);
    	}
    	
    }
  
}
?>