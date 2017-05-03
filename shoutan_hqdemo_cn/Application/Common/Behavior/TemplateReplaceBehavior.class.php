<?php
namespace Common\Behavior;
use \Think\Behavior;

defined('THINK_PATH') or exit();

class TemplateReplaceBehavior extends Behavior
{
    // 行为参数定义
    protected $options = array();

    // 行为扩展的执行入口必须是run
    public function run(&$content)
    {
        // 系统默认的特殊变量替换
        $replace = array(//'__TEMPLATE__'      =>  APP_TMPL_PATH,  // 项目模板目录
        );
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        return $content;
    }

}