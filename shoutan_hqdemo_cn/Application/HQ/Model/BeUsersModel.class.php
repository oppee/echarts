<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: HeQI
 * Date: 13.11.13
 * Time: 0:33
 * 用户模型
 */
namespace HQ\Model;
use \Think\Model;
class BeUsersModel extends Model
{
	// array(field,rule,message,condition,type,when,params)
    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'),
        array('username', 'checkUsername', '帐号名称已经存在！', Model::EXISTS_VALIDATE, 'callback', Model:: MODEL_BOTH),
        array('password', 'require', '密码不能为空！', Model::EXISTS_VALIDATE, 'regex', Model:: MODEL_INSERT),
        array('password', '6,16', '密码长度为6-16位！', Model::VALUE_VALIDATE, 'length'),
        array('pwdconfirm', 'password', '两次输入的密码不一样！', Model::EXISTS_VALIDATE, 'confirm'),
        array('email', 'email', '邮箱地址有误！'),
        array('hidden', array(0, 1), '状态错误，状态只能是1或者0！', Model::VALUE_VALIDATE, 'in'),
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('crdate', 'time', Model::MODEL_INSERT, 'function'),
        array('tstamp', 'time', Model::MODEL_BOTH, 'function'),
    );
    
    protected function checkUsername($username){
    	$map["username"] = $username;
    	$map["deleted"] = 0;
    	if($this->where($map)->find()){
    		return false;
    	}else{
    		return true;
    	}
    }

}

?>