<?php
/**
 * Description of UnlimitCategory
 *
 * @author evan.chen tingwind@gmail.com QQ:23148479
 *
 */
class UnlimitCategory {

    protected $init_data;     // Inital data that catch from the database

    protected $data=array();
    protected $child=array();
    protected $parent=array();

    protected $top_level=array(); // When the parent_category is zero belongs to top levelk
    protected $id='id';
    protected $parent_category='parent_category';
    protected $ul_class="ul_class";
    protected $title='title';
    protected $base_url='';
    protected $param_str='';
    protected $class_str='level';
    protected $class_level=1;
	protected $currentId='';


    /**
    *  $params=array(
    *      'data'=>array(),
    *      'id'=>'',
    *      'parent_category'=>'',
    *      'ul_class'=>'',
    *      'title'='',
    *      'base_url'='',
    *      'param_str'='',
    *   )
    */
    public function __construct($in_params)
    {
		$params=array(
			'data'=>$in_params['data']?$in_params['data']:array(),
			'id'=>$in_params['id']?$in_params['id']:'id',
			'currentId'=>$in_params['currentId']?$in_params['currentId']:'currentId',
			'parent_category'=>$in_params['parent_category']?$in_params['parent_category']:'parent_category',
			'ul_class'=>isset($in_params['ul_class']) && $in_params['ul_class']?$in_params['ul_class']:'ul_class',
			'title'=>$in_params['title']?$in_params['title']:'title',
			'base_url'=>isset($in_params['base_url']) && $in_params['base_url']?$in_params['base_url']:'?',
			'param_str'=>isset($in_params['param_str']) && $in_params['param_str']?$in_params['param_str']:'param_str',
		);

		unset($in_params);

        $init_data=$params['data'];
        $this->id=$params['id'];
		$this->currentId=$params['currentId'];
        $this->parent_category=$params['parent_category'];
        $this->ul_class=$params['ul_class'];
        $this->title=$params['title'];
        $this->base_url=$params['base_url'];
        $this->param_str=$params['param_str'];

		foreach(array_keys($init_data) as $k)
        {
            $this->init_data[$init_data[$k][$this->id]]=$init_data[$k];
        }

        for($i=0;$i<count($init_data);$i++){

            // collect the top level
            if($init_data[$i][$this->parent_category]==0)
            {
                $this->top_level[]=$init_data[$i][$this->id];
            }

            // collect the child to ervery parent
            for($j=0;$j<count($init_data);$j++){

                if($init_data[$j][$this->parent_category]==$init_data[$i][$this->id])
                    $this->child[$init_data[$i][$this->id]][]=$init_data[$j][$this->id];
            }

        }
		unset($init_data);
    }
	
	
    /**
     * 创建第一级ul和li格式的分类列表
     *
     * @return $content Sub tree
     */
	public function create_tree_big_ul()
    {
        $this->class_level=1;
        $this->view_tree='<ul class="'.$this->class_str.'_'.$this->class_level.'">'.chr(13);
        foreach ($this->top_level as $zero_category)
        {
            $this->class_level=1;
			$currentClass = $zero_category==$this->currentId ? ' class="current"' : '';
            $this->view_tree.="<li ".$currentClass.">";
            $this->view_tree.='<a href="'.$this->createLink($zero_category).'" >'.$this->init_data[$zero_category][$this->title].'</a>'.chr(13);
            $this->view_tree.=$this->create_sub_tree_ul($zero_category,++$this->class_level);
            $this->view_tree.="</li>".chr(13);
        }
        $this->view_tree.="</ul>".chr(13);
        return $this->view_tree;
    }

    /**
     * 创建子级ul和li格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_sub_tree_ul($tree_id,$class_level)
    {
        $content='';
        $_class_level=$class_level;
        if($this->child[$tree_id]!='' && is_array($this->child[$tree_id]))
        {
            $content.='<ul class="'.$this->class_str.'_'.$class_level.'">'.chr(13);
            foreach($this->child[$tree_id] as $r)
            {
				$currentClass = $this->init_data[$r][$this->id]==$this->currentId ? ' class="current"' : '';
                $content.="<li ".$currentClass.">";
                $content.='<a href="'.$this->createLink($this->init_data[$r][$this->id]).'" >'.$this->init_data[$r][$this->title].'</a>';
                $content.=$this->create_sub_tree_ul($r,++$class_level);
                $content.="</li>".chr(13);

                $class_level=$_class_level;
            }
            $content.="</ul>".chr(13);
            return $content;
        }else{
            return $content;
        }
    }

    /**
     * 创建一级select格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_tree_select_bak()
    {
        $this->class_level=1;
        $this->view_tree='';
        foreach ($this->top_level as $zero_category)
        {
			//$currentClass = $zero_category==$this->currentId ? ' class="current"' : '';
            $this->class_level=1;
			if($this->currentId==$zero_category){
				$this->view_tree.='<optgroup label="'.$this->init_data[$zero_category][$this->title].'"></optgroup>';
			}else{
				$this->view_tree.='<option value="'.$zero_category.'">'.$this->init_data[$zero_category][$this->title].'</option>';
			}
            $this->view_tree.=$this->create_sub_tree_select($zero_category,$this->class_level);
        }
        $this->view_tree.="";
        return $this->view_tree;
    }


    public function create_tree_select()
    {
        $this->class_level=1;
        $this->view_tree='';
        foreach ($this->top_level as $zero_category)
        {
			$current = $zero_category==$this->currentId ? 'selected="selected"' : '';
			$this->class_level=1;
			$this->view_tree.='<option value="'.$zero_category.'" '.$current.'>'.$zero_category.'.'.$this->init_data[$zero_category][$this->title].'</option>';
			$this->view_tree.=$this->create_sub_tree_select($zero_category,$this->class_level);
        }
        $this->view_tree.="";
        return $this->view_tree;
    }

    /**
     * 创建子级select格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_sub_tree_select($tree_id,$class_level)
    {
        $content='';
        $_class_level=$class_level;
        if(isset($this->child[$tree_id]) && $this->child[$tree_id]!='' && is_array($this->child[$tree_id]))
        {
            foreach($this->child[$tree_id] as $r)
            {
                $space='';
                for($i=0;$i<$class_level;$i++)
                {
                    $space.='&nbsp;&nbsp;';
                }
				$space.='&nbsp;├&nbsp;';
				
				$current = $this->init_data[$r][$this->id]==$this->currentId ? 'selected="selected"' : '';
                $content.='<option value="'.$this->init_data[$r][$this->id].'" '.$current.'>'.$space.$this->init_data[$r][$this->id].'.'.$this->init_data[$r][$this->title].'</option>';
                $content.=$this->create_sub_tree_select($r,++$class_level);
                $class_level=$_class_level;
            }
            return $content;
        }else{
            return $content;
        }
    }
	

    /**
     * 创建一级select值是1_2_3格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_tree_select_()
    {
        $this->class_level=1;
        $this->view_tree='';
        foreach ($this->top_level as $zero_category)
        {
            $this->class_level=1;
            $this->view_tree.='<option value="'.$this->recursion_parent($zero_category).'">'.$this->init_data[$zero_category][$this->title].'</option>'.chr(13);
            $this->view_tree.=$this->create_sub_tree_select_($zero_category,$this->class_level);
        }
        $this->view_tree.="";
        return $this->view_tree;
    }


    /**
     * 创建子级select值是1_2_3格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_sub_tree_select_($tree_id,$class_level)
    {
        $content='';
        $_class_level=$class_level;
        if($this->child[$tree_id]!='' && is_array($this->child[$tree_id]))
        {
            foreach($this->child[$tree_id] as $r)
            {
                $space='';
                for($i=0;$i<$class_level;$i++)
                {
                    $space.='&nbsp;&nbsp;&nbsp;';
                }
                $content.='<option value="'.$this->recursion_parent($this->init_data[$r][$this->id]).'">'.$space.$this->init_data[$r][$this->title].'</option>'.chr(13);
                $content.=$this->create_sub_tree_select_($r,++$class_level);
                $class_level=$_class_level;
            }
            return $content;
        }else{
            return $content;
        }
    }


    /**
     * 创建一级XML格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_tree_xml()
    {
        $this->class_level=1;
        $this->view_tree='';
        foreach ($this->top_level as $zero_category)
        {
            $this->class_level=1;
			$this->view_tree.='<item><nodeValue>'.$zero_category.'</nodeValue><nodeText>'.$this->init_data[$zero_category][$this->title].'</nodeText></item>'.chr(13);
            $this->view_tree.=$this->create_sub_tree_xml($zero_category,$this->class_level);
        }
        $this->view_tree.="";
        return $this->view_tree;
    }


    /**
     * 创建子级select格式的分类列表
     *
     * @return $content Sub tree
     */
    public function create_sub_tree_xml($tree_id,$class_level)
    {
        $content='';
        $_class_level=$class_level;
        if($this->child[$tree_id]!='' && is_array($this->child[$tree_id]))
        {
            foreach($this->child[$tree_id] as $r)
            {
                $space='';
                for($i=0;$i<$class_level;$i++)
                {
                    $space.='=';
                }
				$content.='<item><nodeValue>'.$this->init_data[$r][$this->id].'</nodeValue><nodeText>'.$space.$this->init_data[$r][$this->title].'</nodeText></item>'.chr(13);
                $content.=$this->create_sub_tree_xml($r,++$class_level);
                $class_level=$_class_level;
            }
            return $content;
        }else{
            return $content;
        }
    }
	
	
	//获得父级ID
    function recursion_parent($current_id) {
        
        if($this->init_data[$current_id][$this->parent_category]!=0)
        {
            $content.=$this->recursion_parent($this->init_data[$current_id][$this->parent_category]);
			$content.='_';
        }
		$content.=$current_id;
        return $content;
    }
	
	//格式化URL	
    public function createLink($id) {
       // return $this->base_url."?".$this->param_str."=".$id;
		return formatModUrl($this->base_url,$this->param_str."=".$id);
    }
}
?>
