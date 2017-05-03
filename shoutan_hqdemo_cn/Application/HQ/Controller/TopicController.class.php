<?php
namespace HQ\Controller;
/**
 *
 * @author HeQi, info@heqi.hk
 */
class TopicController extends BaseController {

    //模型配置, 1. label, 2. tips 3. type, 4. data source, 5. special logic
    private $tca = array();
    public $tbName = CONTROLLER_NAME; // 'link';		//MODULE_NAME
    public $image_path = '';
    public $thumb_path = '';
    // 高级搜搜状态
    public $status_all = array(
        array('status'=>'hidden', 'value'=>0, 'title'=>'显示', 'class'=>'success'),
        array('status'=>'hidden', 'value'=>1, 'title'=>'隐藏', 'class'=>'inverse'),
        array('status'=>'hot', 'value'=>1, 'title'=>'推荐', 'class'=>'success'),
        array('status'=>'hot', 'value'=>0, 'title'=>'未推', 'class'=>'inverse'),
        array('status'=>'top', 'value'=>1, 'title'=>'置顶', 'class'=>'success'),
        array('status'=>'top', 'value'=>0, 'title'=>'未顶', 'class'=>'inverse'),
    );

    protected function init() {
		//高级筛选
		$this->assign('status_all', $this->status_all);

        //分类关联
		$categories = '';
		$current = '';
		$multiple = array();
		 $id = $this->_get('id');
			if ($id) {
				/*$user_id = M($this->tbName)->where(array('deleted' => 0, 'id' => $id))->getField('user_id');
				$users = M('User')->where(array('deleted' => 0,'hidden' => 0,'id' => $user_id))->order('id desc')->field('id as "0",concat_ws(".",id,username) as "1"')->select();
				$this->assign('users', $users);*/
				
				$users = M('User')->where(array('deleted' => 0, 'hidden' => 0))->order('id desc')->field('id as "0",concat_ws(".",id,username) as "1"')->select();
				$this->assign('users', $users);
			} else {
				$users = M('User')->where(array('deleted' => 0, 'hidden' => 0))->order('id desc')->field('id as "0",concat_ws(".",id,username) as "1"')->select();
				$this->assign('users', $users);
			}
			
			
        if (ACTION_NAME == 'edit') {
            $id = $this->_get('id');
            $info = M($this->tbName)->where('id = ' . $id)->find();
            $current = $info['category'];

            //图片路径
            $this->image_path = $this->thumb_path = CONTROLLER_NAME . '/source/' . date('Ym', $info['crdate']) . '/';
            if ($this->Config['thumb_width']){
                $this->thumb_path = CONTROLLER_NAME . '/thumb_0/' . date('Ym', $info['crdate']) . '/';
            }

            //批量图片
            $data = array(
                'info_tab' => CONTROLLER_NAME,
                'info_id' => $id
            );
            $multiple = M('Attach')->where($data)->select();
			//echo M('Attach')->getLastSql();die;
			 
            if (count($multiple)) {
                foreach ($multiple as $k => $v) {
                    $multiple[$k]['path'] = __ROOT__ . '/' . $this->Config['path_upload'] . 'Topic/attach/' . date('Ym', $v['crdate']) . '/' . $v['path'];
                }
            }
			
			
			
			
		
			
        } elseif (ACTION_NAME == 'index') {
            $current = $this->_get('category');
        }
        $categories = $this->getCategoryTree(0, $current,'Topic_category');
		 
        //配置字段
        $this->tca = array(
            'hidden' => array(
                'label' => '是否显示',
                'type' => 'toggle_button',
                'data' => '0', //checkbox value
				'help'=> '设置开，帖子就是审核通过',
            ),
            'hot' => array(
                'label' => '是否推荐',
                'type' => 'toggle_button',
                'data' => '1'
            ),
            'top' => array(
                'label' => '是否置顶',
                'type' => 'toggle_button',
                'data' => '1'
            ),
            /*'sorting' => array(
                'label' => '排列顺序',
                'type' => 'text',
                'data' => '0'
            ),*/
			'likes' => array(
                'label' => '点赞次数',
                'type' => 'text',
                'data' => '0'
            ),
            'hit' => array(
                'label' => '访问次数',
                'type' => 'text',
                'data' => '0'
            ),
			'user_id' => array(
                'label' => '发布会员',
                'type' => 'select',
                'data' => $users,
				'required' => true
            ),
            'title' => array(
                'label' => '帖子标题',
                'type' => 'text',
                'required' => true
            ),
            'category' => array(
                'label' => '帖子分类',
                'type' => 'select_tree',
                'data' => $categories,
                'required' => true
            ),
            /*'image' => array(
                'label' => '文章图片',
                'type' => 'image'
            ),*/
           /* 'author' => array(
                'label' => '文章作者',
                'type' => 'text'
            ),
            'from' => array(
                'label' => '文章来源',
                'type' => 'text',
                'help' => ''
            ),*/
            'remark' => array(
                'label' => '帖子内容',
                'type' => 'textarea'
            ),
			'multiple' => array(
                'label' => '多图片上传',
                'type' => 'multiple',
                'data' => $multiple,
				'helpinfo' => '请上传最佳尺寸宽度为750px尺寸,高度不限制的图片'
            ),
/*            'keywords' => array(
                'label' => '关键字',
                'type' => 'text',
                'help' => '如有多个，请在每个之间使用半角逗号（,）分隔',
              ),
            'content' => array(
                'label' => '文章内容',
                'type' => 'rte',
                'class' => 'all'
            )*/
        );

        $this->assign('category', $categories);
    }

    /**
     * 列表页面
     */
    public function index() {
        $this->init(); //加载初始化数据
        import('@.Util.Page'); // 导入分页类
        $Obj = D($this->tbName); //实例化对象


        $Where = "deleted=0";
		//高级搜索 数据状态
		$Where_status = ' and ( ';
		$Where_status_info = '';
		foreach($this->status_all as $k => $v){
			if(I('request.'.$v['status']) != ''){
				$I_status = I('request.'.$v['status']);
				if(in_array($v['value'], $I_status)){
					$Where_status_info .= " or `".$v['status']."`='".$v['value']."' ";
					//注意 这里列表页数据如果用了关联查询 就要写成 $Where_status_info .= " or tbName.".$v['status']."='".$v['value']."' ";
				}
			}
		}
		if($Where_status_info==''){
			unset($Where_status);
		}else{
			$Where_status .= substr($Where_status_info, 3).' ) ';
			$Where .= $Where_status;
		}

        $category = I('request.category');
        if ($category) {
            $ids = array();
            $rs = $this->getPathAndClass($category, 'Topic_category', true);
            if (count($rs)) {
                foreach ($rs as $v) {
                    $ids[] = $v['id'];
                }
                $ids = implode(',', $ids);
                $Where .= " and category in (" . $ids . ") ";
            }
        }

        if (I('request.keyword')) {
            //$Where .=' and (id LIKE "%' . I('request.keyword') . '%" OR title LIKE "%' . I('request.keyword') . '%")';
        	$keyword = preg_replace("/%/", "\%", I('request.keyword'));
        	$Where .=' and (title LIKE "%' . $keyword . '%")';
        }

        if(I('post.limit_num') != ''){
            session('page_limit_num', I('post.limit_num'));
        }
		$sorting = I('get.sorting') ? I('get.sorting') : 'id';
		$order = I('get.order') ? I('get.order') : 'desc';
        $limit_num = $_SESSION['page_limit_num'] ? $_SESSION['page_limit_num'] : 10;
        
        $Page = new \HQ\Util\Page($Obj->where($Where)->count(), $limit_num); // 实例化分页类 传入总记录数和每页显示的记录数
        $list = $Obj->where($Where)->order($sorting . ' ' . $order)->limit($Page->firstRow . ',' . $Page->listRows)->select();

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
                if ($this->tca[$key]['required'] && !$val) {
                    $this->error($this->tca[$key]['label'] . '是必填项!');
                }
            }

            //删除文件 begin **********************************
            $path = $this->Config['path_upload'] . CONTROLLER_NAME . '/';
            if ($info && $info['image'] && $_POST['delete_image'] == '1') {
                if (trim($this->Config['thumb_width']) != '') {
                    foreach (explode(',', $this->Config['thumb_width']) as $k => $v) {
                        $thumb = $path . 'thumb_' . $k . '/' . date('Ym', $info['crdate']) . '/' . $info['image'];
                        if (is_file($thumb)) {
                            unlink($thumb);
                        }
                    }
                }

                $file_path = $path . 'source/' . date('Ym', $info['crdate']) . '/';
                $this->delInfoFile($file_path, $info['image']);
            } else {
                unset($_POST['image']);
            }
            //删除文件 end **********************************
            //文件上传 begin **********************************
            if (count($_FILES) && isset($_FILES['image'])) {
                $save_path = 'source/'; // 设置附件上传目录
                $extent_path = ($info['crdate'] ? date('Ym', $info['crdate']) : date('Ym')) . '/';
                $thumb_width = $this->Config['thumb_width'];
                $thumb_height = $this->Config['thumb_height'];

                $upload = $this->uploadFile('image', $path, $save_path, $extent_path, $thumb_width, $thumb_height);

                if (is_array($upload) && $upload['upload']) {
                    $_POST['image'] = $upload['info'];
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
                    $Obj->hidden = 1;
                }
                if (!isset($_POST['top'])) {
                    $Obj->top = 0;
                }
                if (!isset($_POST['hot'])) {
                    $Obj->hot = 0;
                }           
                if ($id) {
                    $Obj->tstamp = time();
                    $Obj->where(array('id' => $id))->save();
                    $message = '编辑成功!';
                } else {
                    $this->updateCategorySum($_POST['category'], 'Topic', 'add', true);//所有分类记录数刷新
                    $Obj->crdate = time();
                    $Obj->tstamp = time();
                    $id = $Obj->add();
                    if (trim($_POST['sorting'] == '')) {
                        $Obj->where(array('id' => $id))->save(array('sorting' => $id));
                    }
                    $message = '添加成功!';
                }

                //多文件上传 begin
                if (isset($_POST['multiple'])) {
                    $multiple = explode(',', $_POST['multiple']);
                    foreach ($multiple as $file) {
                        if ($file) {
                            $file = explode('/', trim($file));
                            if (empty($file[0]) || empty($file[1])){
                                continue;
                            }

                            $file_info = array(
                                'tstamp' => time(),
                                'crdate' => time(),
                                'title' => substr($file[1], 0, strrpos($file[1], '.')),
                                'info_id' => $id,
                                'info_tab' => 'Topic',
                                'path' => $file[0],
                            );

                            M('Attach')->add($file_info);
                        }
                    }
                }


                $this->success($message, U(CONTROLLER_NAME . '/index', 'kept=1'));
                exit;
            }
        }
        //自动渲染字段
        parent::autoFields($this->tca, $info);
		$this->assign('Multiple', true);
        $this->display('info');
    }

}

?>