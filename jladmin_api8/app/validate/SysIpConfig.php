<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class SysIpConfig extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'=>  'require|integer',
        'ip'=>'require|ip',
        'status'=>'integer',
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
        'ip.require' => 'ip名称必填',
        'ip.ip' => '不是有效ip',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene  = [
        'add'=>['ip'],
        'edit'=>['id','ip'],
        'detail'=>['id'],
    ];

}
