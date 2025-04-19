<?php


namespace app\validate;


use think\Validate;

class TouziProductLev extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'goods_id' => 'require|number',
        'project_day' => 'require|number',
        'income' => 'require|float',
        'total_red_money' => 'require|float',
        'id' => 'require|number',
        'withdrawable_money'=>'require|float',
        'gid'=>'require',
        'money'=>'require|float',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [


    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['goods_id','project_day','income','total_red_money','id','withdrawable_money'],
        'add' => ['goods_id','project_day','income','total_red_money','withdrawable_money'],
        'money_add'=> ['gid','money'],
        'money_edit'=> ['id','gid','money'],
        'del' => ['id'],
    ];
}