<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: HeQI
 * Date: 13.11.13
 * Time: 0:33
 * 用户组模型
 */

namespace OA\Model;
use \Think\Model;
class BeGroupsModel extends Model
{

    protected $_validate = array(
        array('title', 'require', '组名不能为空！')
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('crdate', 'time', Model::MODEL_INSERT, 'function'),
        array('tstamp', 'time', Model::MODEL_BOTH, 'function'),
    );

}

?>