<?php
namespace HQ\Controller;
/**
 * 
 * @author HeQi, info@heqi.hk
 */
class MstdictController extends BaseController
{

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = 'mst_dict';
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
       // $this->assign('status_all', $this->status_all);
		
		//交易状态 数据字典：TS
		//$transaction_status = M('mst_dict')->where(array('deleted' => 0, 'hidden' => 0))->order('id desc')->field('id as "0",concat_ws(".",id,name,company) as "1"')->select();
       	//$this->assign('transaction_status', $transaction_status);
			
        
        //$user=M('User')->order("id DESC")->select();
        $this->tca = array(
		
			/*'types' => array(
                'label' => 'code类型',
                'type' => 'text'
            ),*/
			'code' => array(
                'label' => 'CODE代码',
                'type' => 'text'
            ),
			'desc' => array(
                'label' => 'CODE描述',
                'type' => 'text'
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
        $sorting = I('get.sorting') ? I('get.sorting') : 'id';
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
        $info = M($this->tbName)->where("id ='$id'")->find();
				
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
                 
								
				$Obj->upddate = date("Y-m-d H:i:s");
                if ($id) {
                    $Obj->where(array('id' => $id))->save();
                    $message = '编辑成功!';
                } else {
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
			$id = $Obj->where(array("id"=>$id))->save(array('del_flag' => 1,'upddate' => $upddate));
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