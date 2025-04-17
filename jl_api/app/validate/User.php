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
        'phone'     => 'require|min:9|max:11',
        'username'  => 'require|alphaNum|min:2|max:25',
        'province'  => 'require|alphaNum|min:2|max:25',
        'city'      => 'require|min:2|max:25',
        'county'    => 'require|alphaNum|min:2|max:25',
        'address'   => 'require|alphaNum|min:2|max:25',
        'addressId' => 'integer',

        'orderMoney' => 'require|float|between:1,1000',
        'uname'      => 'require|string|min:2|max:25',
        'goodsId'    => 'require|integer',
        'moneyId'    => 'require|integer',
        'number'     => 'require|integer|between:1,100',
        'addId'     => 'require|integer',
        'orderNo'     => 'require|min:10|max:30',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'phone'     => '1005',//'手机号错误'
        'phone.min' => '1010',
        'phone.max' => '1010',
        'username'  => '1008',//密码错误
        'province'  => '1011',
        'city'      => '1012',
        'county'    => '1013',
        'address'   => '1014',
        'addressId' => '1015',

        'orderMoney' => '10014',
        'uname'      => '10015',
        'goodsId'    => '10016',
        'moneyId'    => '10017',
        'number'     => '10020',
        'addId'=>'10021',
        'orderNo'=>'10022'

    ];

    protected $scene = [
        'addressAdd'     => ['addressId', 'phone', 'username', 'province', 'city', 'county', 'address'],//用户添加定制
        'userPlaceOrder' => ['order_money', 'uname', 'goodsId', 'moneyId','number'],//用户下订单
        'getUserOrderData' => ['order_money', 'uname', 'goodsId', 'moneyId','number'],//用户订单号查询信息，代付页面
    ];
}
