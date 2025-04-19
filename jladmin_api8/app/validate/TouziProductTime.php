<?php


namespace app\validate;


use think\Validate;

class TouziProductTime extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name' => 'require|number|max:10',
        'id' => 'require|number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'name' => '产品有效时间必选',
        'id.number' => 'ID必须传递',

    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['name','id'],
        'add' => ['name',],
        'del' => ['id'],
    ];

}