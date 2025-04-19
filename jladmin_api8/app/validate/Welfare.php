<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Welfare extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id'=>  'require|integer',
        'type'=>  'require|integer',
        'record_no'=>'require',
        'collection_agency'=>'require',
        'executing_agency'=>'require',
        'content'=>'require',
        'title'=>'require|max:200',
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
        'type.require' => '上下架状态是必填',
        'type.integer' => '上下架状态必须是整数',
        'title.require' => '标题必填',
        'title.max' => '标题最多200字',
        'content.require' => '文章内容必填',
        'record_no.require' => '民政编号必填',
        'collection_agency.require' => '收款机构必填',
        'executing_agency.require' => '执行机构必填',
    ];

    /**
     * 验证场景
     * @var \string[][]
     */
    protected $scene  = [
        'add'=>['title','content','record_no','collection_agency','executing_agency'],
        'edit'=>['id','title','content','record_no','collection_agency','executing_agency'],
        'detail'=>['id'],
        'grounding'=>['id','type'],
        'del'=>['id'],
    ];

}
