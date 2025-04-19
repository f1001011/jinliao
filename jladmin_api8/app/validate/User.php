<?php
declare (strict_types=1);

namespace app\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'user_name' => 'require|max:200',
        'pwd' => 'require|alphaNum',
        'withdraw_pwd' => 'require|alphaNum',
        'nickname' => 'max:200',
        'type' => 'integer',
        'status' => 'integer',
        'is_real_name' => 'integer',
        'is_fictitious' => 'integer',
        'id' => 'require|integer',
        'money_balance'=>'integer',
        'agent_rate' => 'float',
        'money_freeze' => 'require|float',
        'invitation_code' => 'alphaNum|max:200',
//        'admin'=>'integer',
        'market_uid'=>'integer',
        'state'=>'require|integer',
        'change_money'=>'require|integer',
        'uid' => 'require|integer',
        'money_status' => 'require|integer',
        'money_change_type' => 'require|integer',
        'product_class' => 'max:200',
        'sfz'=>'require|alphaNum|max:100',
        'phone'=>'require|max:50',
        'agent_id'=>'integer',
        'operate_pwd'=>'require',
        'agent_uid'=>'integer|require',
        'agent_pid'=>'integer|require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        //'user_name.require' => '名称必填',
        'user_name.max' => '名称最多不能超过200个字符',
        //'pwd.require' => '密码必填',
        //'pwd.require' => '密码必填',
        'pwd.alphaNum' => '密码必须是字母和数字',
        'withdraw_pwd.integer' => '提现密码必须是数字',
        'nickname.max' => '昵称最多不能超过200个字符',
        'type.integer' => '类型必须是数字',
        'status.integer' => '状态必须是数字',
        'is_real_name.integer' => '实名必须是数字',
        'is_fictitious.integer' => '虚拟账号必须是数字',
        'agent_rate.integer' => '分销必须是数字',
        'invitation_code.max' => '邀请码最多不能超过200个字符',
        'id.require' => 'ID必填',
        'id.integer' => 'ID必须是整数',
//        'admin.require' => '业务员ID必须是整数',
        'state.integer' => '修改状态必填必须是整数',
        'state.require' => '修改状态必填',
        'product_class' => '可购买产品类别必填',
        'money_change_type.require' => '变更类型不能为空',
        'money_change_type.integer' => '变更类型只能是整数',
        'money_status.require' => '金额变更类型不能为空',
        'money_status.integer' => '金额变更类型只能是整数',
        'change_money.require' => '变更金额不能为空',
        'change_money.integer' => '变更金额类型错误',
        'uid.require' => '用户ID不能为空',
        'uid.integer' => '用户ID类型错误',
        'operate_pwd.require' => '操作密码不能为空',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'edit' => ['id','user_name','phone', 'market_uid', 'agent_id', 'is_real_name'],
        'add' => ['is_fictitious','user_name','phone','pwd', 'withdraw_pwd', 'market_uid', 'agent_id'],
        'detail' => ['id'],
        'status'=> ['id','status'],
        'money'=>['uid','change_money','money_status','money_change_type','operate_pwd'],
        'operate'=>['operate_pwd'],
        'agent_transfer'=>['agent_uid','agent_pid','operate_pwd'],
    ];

}
