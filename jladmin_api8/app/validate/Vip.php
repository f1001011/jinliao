<?php
declare (strict_types=1);

namespace app\validate;

use think\Validate;

class Vip extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'vip_name' => 'require|min:1|max:10',
        'vip_lv' => 'require|integer',
        'need_money' => 'require|float',
        'task_num' => 'require|integer',
        'task_hire' => 'require|float',
        'invite_hire' => 'require',
        'min_withdrawal' => 'require|float',
        'withdrawal_routine' => 'require|float',
        'vip_condition' => 'require|integer',
        'effect_date' => 'require|integer',
        'id' => 'require|integer',
        'task_image' => 'require',
        'task_comment' => 'require',
        'task_title' => 'require',
        'status' => 'require',
        'dayly_total_number' => 'require|integer',
    ];

    protected $message = [
        'vip_name.require' => 'vip名称必填',
        'vip_name.min' => 'vip名称最小1位',
        'vip_name.max' => 'vip名称最大10位',
        'vip_lv.require' => 'vip等级必填',
        'vip_lv.integer' => 'vip必须是整数',
        'need_money.integer' => 'vip需要金额必填',
        'need_money.float' => 'vip需要金额必须是数字',
        'task_num.integer' => '任务次数必须是数字',
        'task_hire.float' => '任务奖励必须是数字',
        'invite_hire.require' => '每个等级的返佣必填',
        'min_withdrawal.require' => '最小提现金额必填',
        'withdrawal_routine.require' => '手续费必填',
        'id.require' => 'ID必填',
        'dayly_total_number.integer' => 'dayly_total_number必须是数字',
        'dayly_total_number.require' => 'dayly_total_number必填',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'add' => [],
        'edit' => ['id','vip_name', 'vip_lv','need_money','task_num','task_hire','invite_hire','min_withdrawal','withdrawal_routine'],
        //task_title,task_comment,task_image,total_number,status
        'task_add' => ['task_title','task_comment','task_image','dayly_total_number','status'],
        'task_edit' => ['id','task_title','task_comment','task_image','dayly_total_number','status'],
    ];

}
