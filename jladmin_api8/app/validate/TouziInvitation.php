<?php


namespace app\validate;


use think\Validate;

class TouziInvitation extends Validate
{

    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title' => 'require|max:100',
        'number' => 'require|number',
        'reward' => 'require|number',
        'grade' => 'require|number',
        'status' => 'require|number',
        'id' => 'require|number',
        'type' => 'require|number',
        'content' => 'max:200',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'title' => '奖励标题必须填写',
        'number' => '奖励邀请人数必填',
        'reward' => '奖励金额必填',
        'grade' => '奖励等级必填',
        'status' => '是否启用必填',
        'id.number' => 'ID必须传递',
        'type' => '奖励币种类型必选',
        'content' => '奖励描述必填',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['title','number','reward', 'grade', 'status','id','content'],
        'add' => ['title','number','reward', 'grade', 'status','type','content'],
        'del' => ['id'],
    ];
}