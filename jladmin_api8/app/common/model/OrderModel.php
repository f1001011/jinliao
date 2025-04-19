<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class OrderModel extends Model
{
    use TraitModel;

    CONST PLACE_AN_ORDER = 0; //已下单 与下面 $status对应
    CONST DELIVER_GOODS = 3; //已发货 与下面 $status对应
    CONST SIGN_FOR = 6; //已签收 与下面 $status对应

    CONST PAY_NOT = 0;  // 未支付  与下面 $pay_status
    CONST PAY_OK = 1;  // 已支付  与下面 $pay_status

    public $name = 'common_goods_order';


//    public $status = [
//        0 => '已下单', 3 => '已发货', 6 => '已签收',404 => '忽略订单'
//    ];
//
//    public $pay_status = [
//        '未支付', '已支付'
//    ];
//
//    public function getStatusAttr($value)
//    {
//        return isset($this->status[$value]) ? $this->status[$value] : $value;
//    }
//
//    public function getPayStatusAttr($value)
//    {
//        return isset($this->pay_status[$value]) ? $this->pay_status[$value] : $value;
//    }

    public static function page_list($where, $limit, $page)
    {
        $map=self::whereMap();
        if (session('admin_user.agent')) $where[]= ['a.status', '<', 404];
        return self::alias('a')
            ->where($where)
            ->where($map)
            ->join('common_admin b', 'a.market_uid = b.id', 'left')
            ->join('common_admin c', 'a.admin_uid = c.id', 'left')
            ->join('common_user d', 'a.uid = d.id', 'left')
            ->field('a.*,b.user_name admin_name,c.user_name market_name,d.user_name')
            ->order('id desc')->paginate(['list_rows' => $limit, 'page' => $page]);
    }
}