<?php
namespace HQ\Controller;


class LogsController extends BaseController{
	//模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = "Log";  //MODULE_NAME
    
	public function init(){
		$be_users = M("be_users")->where(array("hidden"=>0,"deleted"=>0))->field("id as '0',username as '1'")->select();
		$this->assign("be_users",$be_users);
		
		$this->tca = array(
			'id' => array(
				'label' => 'id',
				'type' => 'text',
			),
			'Name' => array(
				'label' => '姓名',
				'type' => 'text',
			),
			'Operation' => array(
				'label' => '日志记录',
				'type' => 'text',
			),
			'Ip' => array(
				'label' => '访问IP',
				'type' => 'text',
			),
			'time' => array(
				'libel' => '操作时间',
				'type' => 'datetime',
				'mode' => 'datetime',
				'required' => true
			)
		);
	}
	
	/**
	 * 列表
	 */
	public function index()
	{
		$this->init(); //加载初始化数据
		import('@.Util.Page'); // 导入分页类
		$Obj = M($this->tbName.' as l'); //实例化对象
		$Where = "l.deleted=0";
		if (I('request.keyword')!='') {
			$I_keyword = trim(I('request.keyword'));
			$Where .= ' and(l.id LIKE "%' . $I_keyword . '%" OR l.Name LIKE "%' . $I_keyword . '%" OR l.Operation LIKE "%' . $I_keyword . '%" OR l.Ip LIKE "%' . $I_keyword . '%"  OR c.name LIKE "%' . $I_keyword . '%") ';
		}
		//操作人
		if (I('request.be_users_id')) {
			$Where .=' and l.be_users_id = '.I('request.be_users_id');
		}
		// 时间搜索
		if($starttime = I('get.starttime')){
			$Where .=' and (l.crdate >= ' . strtotime($starttime) . ')';
		}
		if($endtime = I('get.endtime')){
			$Where .=' and (l.crdate <= ' . strtotime($endtime . ' 23:59:59') . ')';
		}
		
		//控制分页显示条数
		if(I('post.limit_num')!=''){
			session('page_limit_num', I('post.limit_num'));
		}
		$limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;
		
		//控制列表排序
		$sorting = I('get.sorting') ? I('get.sorting') : 'id';
		$order = I('get.order') ? I('get.order') : 'desc';
		
		//列表查询
		$Page = new \HQ\Util\Page($Obj->join(C('DB_PREFIX').'be_users as u on u.id=l.be_users_id')->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
		$list = $Obj->join(C('DB_PREFIX').'be_users as u on u.id=l.be_users_id')->where($Where)->order('l.'.$sorting.' '.$order)->field('l.*')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		//echo $Obj->getlastsql();dump($list);exit;
		$this->assign('list', $list); // 赋值数据集
		$this->assign('page', $Page->show()); // 分页显示输出
		$this->display();
	}
}