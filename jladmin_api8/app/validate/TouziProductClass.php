<?php


namespace app\validate;


use think\Validate;

class TouziProductClass extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'type_name' => 'require|max:30',
        'id' => 'require|number',
        'status' => 'require|number|max:1',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'type_name' => '产品分类名称必填',
        'id' => 'ID必须传递',
        'status' => '新用户是否启用购买产品类别必传',

    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['type_name','id','status'],
        'add' => ['type_name','status'],
        'del' => ['id'],
    ];

}