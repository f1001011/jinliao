<?php
declare (strict_types=1);

namespace app\validate;

use think\Validate;

class WaresV extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'wares_name' => 'require',
        'wares_money' => 'require|float',
        'wares_spec' => 'require',
        'head_img' => 'require',
        'status' => 'require|integer',
        'id' => 'require|integer',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'wares_name' => '名称错误',
        'wares_money' => '金额错误',
        'wares_spec' => '规格错误',
        'head_img' => '图片错误',
        'status' => '状态错误',
        'id' => 'ID错误',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene = [
        'add' => ['wares_name','wares_money','wares_spec','head_img','status','content'],
        'edit' => ['wares_name','wares_money','wares_spec','head_img','status','id','content'],
    ];

}
