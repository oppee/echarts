<?php
namespace HQ\Controller;
/**
 * 
 * @author HeQi, info@heqi.hk
 */
class TblmerchantdataController extends BaseController
{

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = 'Tbl_merchant_data';
    //高级筛选
    /*public $status_all = array(
        array('status'=>'hidden', 'value'=>0, 'title'=>'显示', 'class'=>'success'),
        array('status'=>'hidden', 'value'=>1, 'title'=>'隐藏', 'class'=>'inverse'),
        array('status'=>'is_pay', 'value'=>0, 'title'=>'中断', 'class'=>'inverse'),
        array('status'=>'is_pay', 'value'=>1, 'title'=>'完成', 'class'=>'success')
    );*/
    protected function init()
    {
        //高级筛选
        //$this->assign('status_all', $this->status_all);
		
		//结算状态 MT
		$merchant_type = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'MT'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('merchant_type', $merchant_type);
		
		//风控状态 MS
		$merchant_status = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'MS'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('merchant_status', $merchant_status);
		
		//审核状态 VS
		$verify_status = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'VS'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('verify_status', $verify_status);
		
	 
	 
        $this->tca = array(
		
			'seq' => array(
                'label' => '序号',
                'type' => 'text'
            ),
			'user_id' => array(
                'label' => '用户名',
                'type' => 'text'
            ),
			'merchant_no' => array(
                'label' => '商户号',
                'type' => 'text'
            ),
			'merchant_name' => array(
                'label' => '商户全称',
                'type' => 'text'
            ),
			'merchant_short_name' => array(
                'label' => '商户简称',
                'type' => 'text'
            ),				
			'device_no' => array(
                'label' => '机具号',
                'type' => 'text'
            ),						
			'contact_person_mail' => array(
                'label' => '业务联系人邮箱',
                'type' => 'text',
            ),	
			'contact_person_tel' => array(
                'label' => '业务联系人手机',
                'type' => 'text',
            ),	
			'juridical_person_name' => array(
                'label' => '法人姓名',
                'type' => 'text',
            ),				
			'juridical_person_tel' => array(
                'label' => '法人电话',
                'type' => 'text',
            ),	
			
			'juridical_person_certificate_no' => array(
                'label' => '法人证件号码',
                'type' => 'text',
            ),			
			'juridical_person_certificate_date_due' => array(
                'label' => '证件到期日',
                'type' => 'date',
				'model' => 'date',
				'help' => 'YYYYMMDD，00000000代表长期',
            ),						
			'merchant_address' => array(
                'label' => '商户地址',
                'type' => 'text',
            ),
			'unionpay_merchant_no' => array(
                'label' => '银联商户号',
                'type' => 'text',
            ),	
			'merchant_rate' => array(
                'label' => '商户费率',
                'type' => 'text',
            ),	
			
			'unionpay_merchant_rate' => array(
                'label' => '银联商户费率',
                'type' => 'text',
				'help' => '银联标准费率，选择其他时，取输入内容',
            ),						
			'sign_rate' => array(
                'label' => '实际签约费率',
                'type' => 'text',
				'help' => '实际签约费率',
				
            ),	
			'merchant_type' => array(
                'label' => '商户类型',
                'type' => 'select',
                'data' => $merchant_type
            ),	
			'business_license_no' => array(
                'label' => '营业执照注册号',
                'type' => 'text'
            ),				
			'business_license_date_due' => array(
                'label' => '营业执照到期日',
                'type' => 'date',
				'model' => 'date',
				'help' => '',
            ),							
 
 			'account_opening_license_no' => array(
                'label' => '开户许可证账号',
                'type' => 'text',
				'help' => '',
            ),						
			'tax_regist_certificate_no' => array(
                'label' => '税务登记证号',
                'type' => 'text',
            ),	
			'merchant_status' => array(
                'label' => '商户状态',
                'type' => 'radio',
                'data' => $merchant_status
            ),	 
 			'verify_status' => array(
                'label' => '审核状态',
                'type' => 'radio',
                'data' => $verify_status
            ),	
 			'verify_datetime' => array(
                'label' => '审核时间',
                'type' => 'text',
				'readonly' => true,
            ),
						
 			'create_datetime' => array(
                'label' => '创建时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '数据提供方跑批创建交易数据的时间'
            ),	
			'update_datetime' => array(
                'label' => '更新时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '数据接收方跑批创建交易数据的时间'
            ),	
			'indate' => array(
                'label' => '创建时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '数据接收方跑批创建交易数据的时间'
            ),	
			'upddate' => array(
                'label' => '修改时间',
                'type' => 'text',
				'readonly' => true,
				'help' => '数据接收方跑批更新交易数据的时间'
            ),	
			'del_flag' => array(
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
        $Obj = D($this->tbName); //实例化对象
        $Where = "del_flag=0";
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
        }
        //控制分页显示条数
        if(I('post.limit_num')!=''){
            session('page_limit_num', I('post.limit_num'));
        }
        //控制列表排序
        $sorting = I('get.sorting') ? I('get.sorting') : 'seq';
        $order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;
        $Page = new \HQ\Util\Page($Obj->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
		$list = $Obj->where($Where)->order($sorting.' '.$order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		//echo $Obj->getLastSql();
		//exit;

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
        $info = M($this->tbName)->where('seq = ' . $id)->find();
				
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
                 
								
				$Obj->update_datetime = date("Y-m-d H:i:s");
				$Obj->upddate = date("Y-m-d H:i:s");
                if ($id) {
                    $Obj->where(array('seq' => $id))->save();
                    $message = '编辑成功!';
                } else {
                    $Obj->create_datetime = date("Y-m-d H:i:s");
					//$Obj->update_datetime = date("Y-m-d H:i:s");
					$Obj->indate = date("Y-m-d H:i:s");
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
			$upddate = date("Y-m-d H:i:s");
			$id = $Obj->where(array("seq"=>$id))->save(array('del_flag' => 1,'upddate' => $upddate));
			if(!$id){
				$rs = false;
			}
		} else {
			$Obj->where(array("seq"=>$id))->delete();
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
		 
		$where = array('seq' => array('in', $ids));
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
					$upddate = date("Y-m-d H:i:s");
					$id = $obj->save(array('del_flag' => 1,'upddate' => $upddate));
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
	
}

?>