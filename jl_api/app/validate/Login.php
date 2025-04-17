<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Login extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'phone'  => 'require|min:9|max:11',
        'pwd'   => 'require|alphaNum|min:6|max:25',
        'upwd' => 'require|alphaNum|min:6|max:25',
        'user_name'=>'require|min:2|max:25',
        'sfz'=>'require|alphaNum|min:15|max:25',
        'agent_id'=>'require|integer',
        'captcha'=>'require|alphaNum|min:3|max:10',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'phone' => '1005',//'手机号错误'
        'phone.min'=>'1010',
        'phone.max'=>'1010',
        'pwd'     => '1006',//密码错误
        'upwd'     => '1007',//重复密码错误
        'user_name'     => '1008',//用户名错误
        'sfz'     => '',//身份证错误
        'agent_id'     => '1023',//推荐人ID错误
        'captcha'     => '1009',//验证码错误
    ];

    protected $scene = [
        'add'  =>  ['phone','pwd','upwd','user_name','agent_id','captcha'],
        'login'=>  ['phone','pwd'],
        'forget'=>  ['phone','pwd','captcha','sfz'],
        'insert'=>  ['phone','pwd','upwd','captcha'],
    ];
}
