<?php
/**
 * Author: HeQI
 * Date: 22.12.13
 * Time: 23:42
 */
namespace Common\Behavior;
use \Think\Behavior;
class BaseBehavior extends Behavior
{
    //行为参数定义
    protected $options = array();

    //行为扩展的执行入口必须是run
    public function run(&$params)
    {
        $configArray = M('Config')->select();
        if (!empty($configArray)) {
            $tempArray = array();
            foreach ($configArray as $config) {
                $tempArray[$config['key']] = $config['value'];
            }
            C('Config', $tempArray);
        }
    }
}

?> 