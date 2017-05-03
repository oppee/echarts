<?php
namespace HQ\Controller;
/**
 * 
 * @author HeQi, info@heqi.hk
 */
class CrmkhController extends BaseController
{

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = 'tbl_crm_kh';
    //高级筛选
    public $status_all = array(
    /*    array('status'=>'hidden', 'value'=>0, 'title'=>'显示', 'class'=>'success'),
        array('status'=>'hidden', 'value'=>1, 'title'=>'隐藏', 'class'=>'inverse'),
        array('status'=>'is_pay', 'value'=>0, 'title'=>'中断', 'class'=>'inverse'),
        array('status'=>'is_pay', 'value'=>1, 'title'=>'完成', 'class'=>'success'),*/
    );
    protected function init()
    {
        //高级筛选
        $this->assign('status_all', $this->status_all);
		
		/*//结算状态 MT
		$merchant_type = M('mst_dict')->where(array('del_flag' => 0, 'types' => 'MT'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('merchant_type', $merchant_type);
		
		//风控状态 MS
		$merchant_status = M('mst_dict')->where(array('del_flag' => 0, 'types' => 'MS'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('merchant_status', $merchant_status);
		
		//审核状态 VS
		$verify_status = M('mst_dict')->where(array('del_flag' => 0, 'types' => 'VS'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('verify_status', $verify_status);
		*/
	 
	 
        $this->tca = array(
		
			'crm_kh_khjlID' => array(
                'label' => '客户记录ID',
                'type' => 'text'
            ),
			'crm_kh_qymc' => array(
                'label' => '企业名称',
                'type' => 'text'
            ),
			 
			'crm_kh_hykhID' => array(
                'label' => '瀚银客户ID',
                'type' => 'text',
				'help' => '对应POS商户表中字段：merchant_no',
            ),
			'crm_kh_zlxrID' => array(
                'label' => '主要联系人ID',
                'type' => 'text',
				'help' => 'crm_lxr_sj'
            ),
			'crm_kh_cjsj' => array(
                'label' => '创建时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '八百客提供2016/8/16 12:12:12'
            ),	
			'crm_kh_xgsj' => array(
                'label' => '修改时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '八百客提供2016/8/16 12:12:12'
            ),	
			'crm_kh_isdelete' => array(
                'label' => '删除标志',
                'type' => 'radio',
				'default' => 0,
				'data' => array(array(0,'有效'),array(1,'无效'))
            ),					
		
        );
    }

    /*
     * 列表页面
     */
    public function index()
    {
        $this->init();	//初始化数据
        import('@.Util.Page'); // 导入分页类
        $Obj = D($this->tbName.' as kh'); //实例化对象
        //$Where = "kh.crm_kh_isdelete=0 and us.deleted=0";
		  $Where = "kh.crm_kh_dellogic != 1";
        /*//高级搜索 数据状态
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
        }*/
		/*
        // 时间搜索
        if($starttime = I('get.starttime')){
            $Where .=' and (crdate >= ' . strtotime($starttime) . ')';
        }
        if($endtime = I('get.endtime')){
            $Where .=' and (crdate <= ' . strtotime($endtime . ' 23:59:59') . ')';
        }
        if (I('request.keyword')!='') {
            $I_keyword = trim(I('request.keyword'));
            $whereUser="username like '%".$I_keyword."%' ";
            $subSql=M('user')->field('id')->where($whereUser)->buildSql();
            $Where .= " and (id like '%".$I_keyword."%' or remark like '%" .$I_keyword."%' or user_id in ".$subSql." ) ";
        } */
        
        //商户ID
        if (I('request.crm_kh_khjlID')!='') {
        	$I_crm_kh_khjlID = trim(I('request.crm_kh_khjlID'));
        	$Where .= " and kh.crm_kh_mdbh = '" . $I_crm_kh_khjlID . "'";
        }
        //商户名称
        if (I('request.crm_kh_dpmc')!='') {
        	$I_crm_kh_dpmc = trim(I('request.crm_kh_dpmc'));
        	$Where .= " and kh.crm_kh_mdmc like '%" . $I_crm_kh_dpmc . "%'";
        }
        //绑定状态
        /*if (I('request.bind')!='') {
        	$I_bind = trim(I('request.bind'));
        	if($I_bind == '1'){
        		$Where .= " and us.id != ''";
        	}else{
        		$Where .= " and us.id = ''";
        	}
        }*/
		
		if (I('request.bind_1')!='' && I('request.bind_0')=='') {
        	$I_bind_1 = trim(I('request.bind_1'));
        	if($I_bind_1 == '1'){
        		$Where .= " and us.lock  = 1";
        	}
        }
		
		if (I('request.bind_0')!='' && I('request.bind_1')=='') {
        	$I_bind_0 = trim(I('request.bind_0'));
        	if($I_bind_0 == '1'){
        		$Where .= " and (us.lock is null or us.lock=0)";
        	}
        }
		
		
        
        //控制分页显示条数
        if(I('post.limit_num')!=''){
            session('page_limit_num', I('post.limit_num'));
        }
        //控制列表排序
        $sorting = I('get.sorting') ? I('get.sorting') : 'id';
        $order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;
        $count = $Obj->join('left join '.C("DB_PREFIX")."tbl_crm_lxr as lxr on lxr.crm_lxr_lxrbh = kh.crm_kh_zlxrbh")
					->join('left join '.C("DB_PREFIX")."user_store as us on kh.crm_kh_mdbh = us.store and  us.deleted=0 ")
					->group("kh.crm_kh_mdbh")
					->where($Where)->select();
        $Page = new \HQ\Util\Page(count($count), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
        
		$list = $Obj->join('left join '.C("DB_PREFIX")."tbl_crm_lxr as lxr on lxr.crm_lxr_lxrbh = kh.crm_kh_zlxrbh")
					->join('left join '.C("DB_PREFIX")."user_store as us on kh.crm_kh_mdbh = us.store and  us.deleted=0 ")
					->group("kh.crm_kh_mdbh")
					->where($Where)
					->field("kh.*,us.lock as bind,us.id as us_id,lxr.crm_lxr_xm,lxr.crm_lxr_sj")
					->order($sorting.' '.$order)
					->limit($Page->firstRow . ',' . $Page->listRows)->select();
		//echo "<br /><br /><br /><br />";
		//echo $Obj->getLastSql();
		foreach ($list as $k => $v){
		
			//判断是否该出现激活码，当前的商户的商户ID必须在smscode里面有，并且没有被使用过才出现
			 $code = M("smscode")->where(array("deleted"=>0,"store"=>$list[$k]["crm_kh_mdbh"]))->find();
				//echo M("smscode")->getLastSql()."C:".$code['is_use']."<br />";
			 if($code && $code['is_use']!=1){
			 	$list[$k]['is_show'] = 1;
			 }else{
			 	$list[$k]['is_show'] = 2;
			 }
			 
			 /* $bind = M('user_store')->where(array("hidden"=>0,"deleted"=>0,"store"=>$v["crm_kh_hykhID"]))->find();
			 if($bind){
			 	$list[$k]['bind'] = 1;
			 }else{
			 	$list[$k]['bind'] = 0;
			 } */
		}

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
				$Obj->crm_kh_xgsj = date("Y-m-d H:i:s");
				//$Obj->upddate = date("Y-m-d H:i:s");
                if ($id) {
                    $Obj->where(array('id' => $id))->save();
                    $message = '编辑成功!';
                } else {
                    //$Obj->create_datetime = date("Y-m-d H:i:s");
					//$Obj->update_datetime = date("Y-m-d H:i:s");
					$Obj->crm_kh_cjsj = date("Y-m-d H:i:s");
					//$Obj->upddate = date("Y-m-d H:i:s");
                    $id = $Obj->add();
                    
                    $message = '添加成功!';
                }

                $this->success($message, U(CONTROLLER_NAME . '/index', 'kept=1'));
                exit;
            }
        }
        //自动渲染字段
        parent::autoFields($this->tca, $info);
        $this->display('info');
    }
		
	//删除逻辑写
	public function del(){
	
		$id = I('request.id') ? I('request.id') : null;
		$is_del = $this->Config['is_del'];
		$Obj = D($this->tbName);
		$rs = true;
		if ($is_del) {
			$crm_kh_xgsj = date("Y-m-d H:i:s");
			$id = $Obj->where(array("id"=>$id))->save(array('crm_kh_isdelete' => 1,'crm_kh_xgsj' => $crm_kh_xgsj));
			if(!$id){
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
	
	//选框
	public function op() {
		$ids = $this->_post('ids');
		$op = $this->_post('op');
		$is_del = $this->Config['is_del'];
		 
		$where = array('id' => array('in', $ids));
		$tbName = $this->tbName;
		$obj = M($tbName)->where($where);
		 
		$rs = true;
		switch ($op) {
			case 'hide':
				$obj->save(array('hidden' => 1));
				break;
			case 'not_hide':
				$obj->save(array('hidden' => 0));
				break;
			case 'del':
				if ($is_del) {
					$crm_kh_xgsj = date("Y-m-d H:i:s");
					$id = $obj->save(array('crm_kh_isdelete' => 1,'crm_kh_xgsj' => $crm_kh_xgsj));
					if(!$id){
						$rs = false;
					}
				} else {
					$obj->delete();
				}
				break;
		}
		if ($rs) {
			$this->success('操作成功!', U(CONTROLLER_NAME . '/index', 'kept=1'));
		} else {
			$this->error('操作失败，请稍后再试！');
		}
	}
	
	public function smscode(){
		$id = I("id");
		$info = M($this->tbName." as c")->join(C("DB_PREFIX")."smscode as s on s.store=c.crm_kh_mdbh")->where(array("s.hidden"=>0,"s.deleted"=>0,'c.id'=>$id))->field("s.*")->find();
		
		if($info["codetime"]){
			$time_s = time() - (int)$info["codetime"];
		}else{
			$time_s = time() - (int)$info["crdate"];
		}
		
		if($time_s > 3600){
			//激活码过期，重新生成
			$code = mt_rand(100000, 999999);
			$data = array(
					"code" => $code,
					"codetime" => time(),
					"lock" => 0,
					"error_number" => 0,
			);
			$res = M("smscode")->where(array("id"=>$info['id']))->save($data);
			
			$info['code'] = $data['code'];
			$info['codetime'] = $data['codetime'];
			$info['lock'] = $data['lock'];
			$info['error_number'] = $data['error_number'];
			$info["remains"] = 3599;
		}else{
			//没有过期，看剩余秒数
			$info["remains"] = 3600 - $time_s;
		}
		$info["effective"] = 1;
		$info["minute"] = floor($info["remains"]/60);
		$info["second"] = $info["remains"] % 60;
		//dump($info);exit;
		$this->assign("info",$info);//dump($info);echo date('i:s',3600);exit;
		$this->display();
			
	}
	
	//重新生成激活码
	public function rebuild(){
		$id = I("id");
		//$info = M($this->tbName." as c")->join(C("DB_PREFIX")."smscode as s on s.store=c.crm_kh_khjlID")->where(array("s.hidden"=>0,"s.deleted"=>0,'c.id'=>$id))->field("s.mobile,s.id")->find();
	
		$code = mt_rand(100000, 999999);
		
		$data = array(
				"code" => $code,
				"codetime" => time(),
				"lock" => 0,
				"error_number" => 0,
		);
		$res = M("smscode")->where(array("id"=>$id))->save($data);
		if($res){
			$return_data = array(
						"code" => $data["code"],
						"codetime" => date('Y-m-d H:i:s',$data["codetime"]),
						"remains" => 3599,
					);
			$this->ajaxReturn(array("status"=>1,"info"=>"重新生成成功","data"=>$return_data),json);
		}else{
			$this->ajaxReturn(array("status"=>0,"info"=>"重新生成失败"),json);
		}
			
	}
	
	//发送激活码
	public function sendCode(){
		$id = I("id");
		//$info = M($this->tbName." as c")->join(C("DB_PREFIX")."smscode as s on s.store=c.crm_kh_khjlID")->where(array("s.hidden"=>0,"s.deleted"=>0,'c.id'=>$id))->field("s.mobile,s.id")->find();
		$info = M("smscode")->where(array("hidden"=>0,"deleted"=>0,'id'=>$id))->field("mobile")->find();
	
		$code = I("code");
		//发送商户激活码
		$sms_code = C('SMS_DXTZ_JHM');  //"SMS_13190848";
		$SmsParam = "{\"code\":\"{$code}\"}";
	
		$res_obj = sendSMS($info["mobile"], $code, $sms_code,$SmsParam);//返回对象
		$res_array = object_to_array($res_obj);
		if($res_array['result']['success'] == true){
			//cookie('code',$code,180);
			
			$this->ajaxReturn(array("status"=>1,"info"=>"发送成功","url"=>U(CONTROLLER_NAME . '/index')),json);
		}else{
			$this->ajaxReturn(array("status"=>0,"info"=>"发送失败","url"=>U(CONTROLLER_NAME . '/index')),json);
		}
			
	}
	
	//联系人
    public function contact(){
    	 
    	
    	$id = I("id");
    	$objList = M('tbl_crm_lxr')->where(array('crm_lxr_lxrbh'=>$id))->select();
    	//echo M('tbl_crm_lxr')->getLastSql();
		//exit;
    	$this->assign("objList",$objList);
    	$this->display();
    }
    
    //查看
    public function view(){
    	$id = I("id");
    	$info = M("tbl_crm_kh")->where(array("id"=>$id))->find();
		
		//法人证件类型
		$info['crm_kh_frzjlx'] = M("tbl_crm_zjlx")->where(array("crm_zjlx_jlID"=>$info['crm_kh_frzjlx'],"crm_zjlx_isdelete"=>0))->getField("crm_zjlx_mc");
		
    	//销售信息
    	//$xsInfo = M("tbl_crm_xs")->where(array("crm_xs_jlID"=>$info['crm_kh_ssxsID']))->find();
    	//$info['crm_xs_xm'] = $xsInfo['crm_xs_xm'];
    	
    	//客户状态名称
    	//$info['crm_khzt_mc'] = M("tbl_crm_khzt")->where(array("crm_khzt_jlID"=>$info["crm_kh_khztID"]))->getField("crm_khzt_mc");
    	
    	//客户合作服务项目
    	//$info['crm_fwxm_mc'] = M("tbl_crm_fwxm")->where(array("crm_fwxm_jlID"=>$info["crm_kh_khhzyxID"]))->getField("crm_fwxm_mc");
    	//合作项目列表
    	//$crm_kh_khhzyxID = explode(";", $info["crm_kh_khhzyxID"]);
    	//$fwxmList = M("tbl_crm_fwxm")->where(array("crm_fwxm_jlID"=>array("in",$crm_kh_khhzyxID)))->select();
    	//$this->assign("fwxmList",$fwxmList);
    	
    	//所属营业部名称
    	//$info['crm_yyb_yybmc'] = M("tbl_crm_yyb")->where(array("crm_yyb_jlID"=>$info["crm_kh_ssyybID"]))->getField("crm_yyb_yybmc");
    	
    	//所属区域名称
    	//$info['crm_qy_qymc'] = M("tbl_crm_qy")->where(array("crm_qy_jlID"=>$info["crm_kh_ssqyID"]))->getField("crm_qy_qymc");
    	
    	//所属大区名称
    	//$info['crm_dq_dqmc'] = M("tbl_crm_dq")->where(array("crm_dq_jlID"=>$info["crm_kh_ssdqID"]))->getField("crm_dq_dqmc");
    	
    	//客户分级
    	//$info['crm_khfj_mc'] = M("tbl_crm_khfj")->where(array("crm_khfj_jlID"=>$info["crm_kh_fjID"]))->getField("crm_khfj_mc");
    	
    	//客户类型
    	//$info['crm_khlx_mc'] = M("tbl_crm_khlx")->where(array("crm_khlx_jlID"=>$info["crm_kh_lxID"]))->getField("crm_khlx_mc");
    	
    	//客户行业
    	//$info['crm_khhy_mc'] = M("tbl_crm_khhy")->where(array("crm_khhy_jlID"=>$info["crm_kh_hyID"]))->getField("crm_khhy_mc");
    	
    	//翰银商户全称
		//$info['mcode'] = M("tbl_store_channels")->where(array("mdbh"=>$info["crm_kh_mdbh"]))->getField("mcode");
    	//$info['merchant_name'] = M("tbl_merchant_data")->where(array("merchant_no"=>$info["crm_kh_hykhID"]))->getField("merchant_name");
    	
		//类目信息
		$mccInfo = M("tbl_store_mcc")->where(array("mdbh"=>$info['crm_kh_mdbh'],"dellogic"=>0))->find();
		$info['crm_kh_mccyl'] = $mccInfo['crm_kh_mccyl'];
		$info['crm_kh_mccwx'] = $mccInfo['crm_kh_mccwx'];
		$info['crm_kh_mcczfb'] = $mccInfo['crm_kh_mcczfb'];
		
    	//联系人信息
    	$lxrInfo = M("tbl_crm_lxr")->where(array("crm_lxr_lxrbh"=>$info['crm_kh_zlxrbh'],"crm_lxr_dellogic"=>0))->find();
    	$info['crm_lxr_xm'] = $lxrInfo['crm_lxr_xm'];
    	$info['crm_lxr_sj'] = $lxrInfo['crm_lxr_sj'];
    	
    	//联系人列表
    	$lxrList = M('tbl_crm_lxr')->where(array('crm_lxr_mdbh'=>$info["crm_kh_mdbh"],"crm_lxr_dellogic"=>0))->select();
		foreach ($lxrList as $k=>&$v){
    		//性别
			if($v['crm_lxr_xb']!='' && $v['crm_lxr_xb'] != null)
			{
				if($v['crm_lxr_xb'] == '1')
				{
					$v['crm_lxr_xb'] = '男';
				}
				elseif($v['crm_lxr_xb'] == '2')
				{
					$v['crm_lxr_xb'] = '女';
				}
				else{
					$v['crm_lxr_xb'] = '';
				}
			}
			else{
				$v['crm_lxr_xb'] = '';
			}
    	}
    	$this->assign("lxrList",$lxrList);
    	
    	//合同列表
    	$htList = M("tbl_crm_ht")->where(array("crm_ht_mdbh"=>$info["crm_kh_mdbh"],"crm_ht_dellogic"=>0))->select();
    	foreach ($htList as $k=>&$v){
    		//商户类型
    		$v["crm_ht_shlx"] = M("tbl_crm_shlx")->where(array("crm_shlx_jlID"=>$v['crm_ht_shlx']))->getField("crm_shlx_mc");
    		//合同状态
    		$v["crm_ht_htzt"] = M('tbl_crm_htzt')->where(array("crm_htzt_jlID"=>$v['crm_ht_htzt']))->getField("crm_htzt_mc");
    		//客户合作服务项目
    		$v["crm_ht_fwxm"] = M('tbl_crm_fwxm')->where(array("crm_fwxm_jlID"=>$v['crm_ht_fwxm']))->getField("crm_fwxm_mc");
    	}
    	$this->assign("htList",$htList);
    	
    	$this->assign("info",$info);
    	$this->display();
    }
	
}

?>