<?php


namespace app\validate;


use think\Validate;

class TouziAds extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'=>  'require|integer',
        'img'=>'require|max:200',
        'sort'=>'require|number',
        'status'=>'require|number',
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
        'img' => '轮播图必传',
        'sort' => '轮播图排序必传',
        'status' => '是否显示必传',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene  = [
        'add'=>['status','img','sort'],
        'edit'=>['status','img','sort','id'],
        'del'=>['id'],
    ];

}