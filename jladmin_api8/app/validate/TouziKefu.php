<?php


namespace app\validate;


use think\Validate;

class TouziKefu extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'type' => 'require|max:100',
        'username' => 'require|max:100',
        'phone' => 'require|max:100',
        'id' => 'require|number',
        'url' => 'require|max:200',
        'market_uid' => 'require|number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'type' => '客服类型必选',
        'username' => '客服名称必填',
        'phone' => '手机号码必填',
        'address' => '客服链接必填',
        'id.number' => 'ID必须传递',
        'market_uid' => '业务员ID必须传递',

    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['phone','url','remarks'],
        'add' => ['phone', 'url','remarks'],
        'del' => ['id'],
    ];
}