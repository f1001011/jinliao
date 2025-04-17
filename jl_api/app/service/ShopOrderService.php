<?php

namespace app\service;

use app\common\Code;
use app\model\GoodsModel;
use app\model\GoodsMoneyModel;
use app\model\GoodsOrderLogModel;
use app\model\GoodsOrderModel;
use app\model\GoodsTypeModel;
use app\model\UserConfigModel;
use think\facade\Db;

class ShopOrderService extends BaseService
{

    //用户下订单
//    public function setUserPlaceOrder(mixed $uid, mixed $orderMoney, mixed $goodsId, mixed $moneyId, mixed $number, mixed $addIdId, mixed $uname, $orderNo)
//    {
//         CacheRepeatClick('setUserPlaceOrder'.$uid);
//        //1 查询当前商品是否存在
//        $goodsData = GoodsModel::pageOne(['id' => $goodsId, 'status' => GoodsModel::STATUS_SHOW]);
//        if (empty($goodsData)) {
//            return [Code::ERROR, [], 10018, ''];//'商品不存在或者已经下架'
//        }
//        //2查询当前商品价格是否存在
//        $GoodsMoneyData = GoodsMoneyModel::pageOne(['id' => $moneyId]);
//        if (empty($GoodsMoneyData)) {
//            return [Code::ERROR, [], 10017, ''];//'商品价格不存在'
//        }
//
//        //计算出一个商品的价格，一个商品的价格，必须小于 商品属性的价格
//        if (($orderMoney / $number) > $GoodsMoneyData->money) {
//            return [Code::ERROR, [], 10019, ''];//'设定的价格大于了商品价格'
//        }
//
//        //查询用户获得的佣金比例
//        $UserConfig = UserConfigModel::pageOne(['user_id'=>$uid]);
//        if (empty($UserConfig)){
//            return [Code::ERROR, [], 10023, ''];//'用户没设置佣金比例'
//        }
//
//        $date    = date('Y-m-d H:i:s');
//
//        Db::startTrans();
//        try {
//            //3 给用户生成订单
//             $GoodsOrderId = GoodsOrderModel::insert(
//                [
//                    'user_id'         => $uid,
//                    'uname'           => $uname,
//                    'goods_name'      => $goodsData['goods_name'],
//                    'money_name'      => $GoodsMoneyData['money_name'],
//                    'money_id'        => $moneyId,
//                    'goods_id'        => $goodsId,
//                    'goods_type_id'   => $goodsData['goods_type_id'],
//                    'goods_type_name' => GoodsTypeModel::pageOneValue(['id'=>$goodsData['goods_type_id']],'type_name'),
//                    'order_money'     => $orderMoney,
//                    'order_number'    => $number,
//                    'create_time'     => $date,
//                    'update_time'     => $date,
//                    'coupon_money'    => abs($GoodsMoneyData['money'] * $number - $orderMoney),
//                    'coupon_rate'     => 0,
//                    'status'          => GoodsOrderModel::STATUS_0,
//                    'order_no'        => $orderNo,
//                    'one_money'       => $GoodsMoneyData['money'],
//                    'pay_status'      => GoodsOrderModel::PAY_0,
//                    'address_id'      => $addIdId,
//                    'commission'      => intval(abs($orderMoney - $UserConfig->commission_rate*$orderMoney)*100)/100,
//                    'commission_rate' => $UserConfig->commission_rate,
//                ],true);
//            //生成订单状态日志
//            GoodsOrderLogModel::inserts(
//                [
//                    'order_id'    => $GoodsOrderId,
//                    'status'      => GoodsOrderModel::STATUS_0,
//                    'create_time' => $date,
//                    'update_time' => $date,
//                ]);
//            Db::commit();
//        } catch (\Exception $e) {
//            Db::rollback();
//            return [Code::ERROR, [], Code::WARNING, $e->getError()];
//        }
//
//        //4并且生成付款连接 和 付款二维码
//        list($invitePayUrl, $qrcodeUrl) = getPayUrlAndQrcode($orderNo);
//
//        return [Code::SUCCESS, ['invitePayUrl' => $invitePayUrl, 'qrcodeUrl' => $qrcodeUrl],'',''];
//    }

    public function setUserPlaceOrder(mixed $uid, mixed $orderMoney, mixed $goodsId, mixed $moneyId, mixed $number, mixed $addIdId, mixed $uname, $orderNo)
    {

        //1 查询当前商品是否存在
        $goodsData = GoodsModel::pageOne(['id' => $goodsId, 'status' => GoodsModel::STATUS_SHOW]);
        if (empty($goodsData)) {
            return [Code::ERROR, [], 10018, ''];//'商品不存在或者已经下架'
        }
        //2查询当前商品价格是否存在
        $GoodsMoneyData = GoodsMoneyModel::pageOne(['id' => $moneyId]);
        if (empty($GoodsMoneyData)) {
            return [Code::ERROR, [], 10017, ''];//'商品价格不存在'
        }

        //计算出一个商品的价格，一个商品的价格，必须小于 商品属性的价格
        if (($orderMoney / $number) > $GoodsMoneyData->money) {
            return [Code::ERROR, [], 10019, ''];//'设定的价格大于了商品价格'
        }

        //查询用户获得的佣金比例
        $UserConfig = UserConfigModel::pageOne(['user_id'=>$uid]);
        if (empty($UserConfig)){
            return [Code::ERROR, [], 10023, ''];//'用户没设置佣金比例'
        }

        $date    = date('Y-m-d H:i:s');

        Db::startTrans();
        try {
            //3 给用户生成订单
            $GoodsOrderId = GoodsOrderModel::insert(
                [
                    'user_id'         => $uid,
                    'uname'           => $uname,
                    'goods_name'      => $goodsData['goods_name'],
                    'money_name'      => $GoodsMoneyData['money_name'],
                    'money_id'        => $moneyId,
                    'goods_id'        => $goodsId,
                    'goods_type_id'   => $goodsData['goods_type_id'],
                    'goods_type_name' => GoodsTypeModel::pageOneValue(['id'=>$goodsData['goods_type_id']],'type_name'),
                    'order_money'     => $orderMoney,
                    'order_number'    => $number,
                    'create_time'     => $date,
                    'update_time'     => $date,
                    'coupon_money'    => abs($GoodsMoneyData['money'] * $number - $orderMoney),
                    'coupon_rate'     => 0,
                    'status'          => GoodsOrderModel::STATUS_0,
                    'order_no'        => $orderNo,
                    'one_money'       => $GoodsMoneyData['money'],
                    'pay_status'      => GoodsOrderModel::PAY_0,
                    'address_id'      => $addIdId,
                    'commission'      => intval(abs($orderMoney - $UserConfig->commission_rate*$orderMoney)*100)/100,
                    'commission_rate' => $UserConfig->commission_rate,
                ],true);
            //生成订单状态日志
            GoodsOrderLogModel::inserts(
                [
                    'order_id'    => $GoodsOrderId,
                    'status'      => GoodsOrderModel::STATUS_0,
                    'create_time' => $date,
                    'update_time' => $date,
                ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return [Code::ERROR, [], Code::WARNING, $e->getError()];
        }

        //4并且生成付款连接 和 付款二维码
        list($invitePayUrl, $qrcodeUrl) = getPayUrlAndQrcode($orderNo);

        return [Code::SUCCESS, ['invitePayUrl' => $invitePayUrl, 'qrcodeUrl' => $qrcodeUrl],'',''];
    }
    //代付订单页面 //用户信息不存在，表示 用户邀请人代付，展示代付页面信息
    public function GetUserOrderDataDehalf(mixed $orderNo)
    {

        //1 查询该订单数据。
        $goodsOrder = GoodsOrderModel::pageList(['order_no'=>$orderNo])->toArray();
        //2 循环计算订单金额
        $totalMoney=0;
        $totalNum=0;
        if (!empty($goodsOrder)){
            foreach ($goodsOrder as $key=>$value){
                $totalNum ++;
                $totalMoney += $value['order_money'];
            }
        }
        return [
            'list'=>$goodsOrder,
            'total_money'=>$totalMoney,//订单金额
            'total_num'=>$totalNum,//订单数
            'order_no'=>$orderNo,//订单号
        ];
    }

    //用户信息存在，表示自己查看订单，展示订单信息
    public function GetUserOrderData(mixed $uid ,mixed $orderNo)
    {
        //1 查询该订单数据。
        $goodsOrder = GoodsOrderModel::pageList(['user_id'=>$uid,'order_no'=>$orderNo])->toArray();
        //2 循环计算订单金额
        $totalMoney=0;
        $totalNum=0;
        if (!empty($goodsOrder)){
            foreach ($goodsOrder as $key=>$value){
                $totalNum ++;
                $totalMoney += $value['order_money'];
            }
        }
        return [
            'list'=>$goodsOrder,
            'total_money'=>$totalMoney,//订单金额
            'total_num'=>$totalNum,//订单数
            'order_no'=>$orderNo,//订单号
        ];
    }

    //用户信息存在，表示自己查看订单，展示订单信息 获取订单列表页面
    public function GetUserOrderDataList(mixed $uid ,mixed $page,mixed $limit)
    {
        $goodsOrder = GoodsOrderModel::pageLimitList(['user_id'=>$uid],$page,$limit,['id'=>'desc']);
        return $goodsOrder;
    }
}