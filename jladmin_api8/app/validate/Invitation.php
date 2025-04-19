<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Invitation extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'=>  'require|integer',
        'title'=>  'require',
        'number'=>  'require|integer',
        'reward'=>'require',
        'status'=>'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message  =   [
        'id.require' => 'ID必填',
        'id.integer' => 'ID必须是整数',
        'title.require' => '标题必填',
        'number.require' => '邀请会员数量必填',
        'number.integer' => '邀请会员数量必须是整数',
        'bet_money.require' => '邀请会员达标奖励必填',
        'bet_money.float'  => '邀请会员达标奖励必须是金额',
        'status.require' => '状态必填',
        'status.integer' => '状态必须是整数',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene  = [
        'add'=>['title','number'],
        'edit'=>['id','title','number'],
        'detail'=>['id'],
        'status'=>['id','status'],
        'del'=>['id'],
    ];

}
