<?php

namespace app\controller\goods;

use app\common\Code;
use app\controller\Base;
use app\service\ShopOrderService;
use think\Exception;
use think\exception\ValidateException;

class ShopOrder extends Base
{
    //此下单方法，争对 分销商，店铺

    public function AuthUser()
    {
        //$this->user = $this->request->UserInfo;
        //         if (empty($this->user)) {
        //         return false;
        //        }
        //1 判断用户是否是 分销商，不是分销商返回 配送距离不对

        if (empty($this->user) || !isset($this->user['is_fictitious']) || (isset($this->user['is_fictitious']) && $this->user['is_fictitious'] != 2)) {
            return false;
            //throw new \think\Exception(show(Code::DISTANCE, [], 10051), 202);
        }
        return true;
    }

    //用户下订单

    /**
     * @throws Exception
     */
//    public function SetUserPlaceOrders()
//    {
//        //实际需要支付金额
//        $orderMoney = $this->request->post('orderMoney/f', 0);
//        $uname      = $this->request->post('uname/s', '');
//        $goodsId    = $this->request->post('goodsId/d', 0);
//        $moneyId    = $this->request->post('moneyId/d', 0);
//        $number     = $this->request->post('number/d', 1);
//        $addIdId    = $this->request->post('addId/d', 0);
//
//        $postField = 'orderMoney,uname,goodsId,moneyId,numberId,number,addId';
//        $post      = $this->request->only(explode(',', $postField), 'post', null);
//        try {
//            validate(\app\validate\User::class)
//                ->scene('userPlaceOrder')
//                ->check($post);
//        } catch (ValidateException $e) {
//            // 验证失败 输出错误信息
//            return show(Code::ERROR, [], $e->getError());
//        }
//
//        //验证用户信息
//        if (!$this->AuthUser()) {
//            throw new \think\Exception(show(Code::DISTANCE, [], 10051), 202);
//        }
//
//        $orderNo = orderCode('NO' . $this->user['id'] . '-');
//        list($code, $data, $meg, $remarks) = ShopOrderService::getInstance()->setUserPlaceOrder($this->user['id'], $orderMoney, $goodsId, $moneyId, $number, $addIdId, $uname, $orderNo);
//        if ($code == 0) {
//            return show(Code::ERROR, $data, $meg, $remarks);
//        }
//        return show(Code::SUCCESS, $data, $meg);
//    }

    //用户下单改正，改为 一次可下多个单子
    public function SetUserPlaceOrder()
    {
        //json字符串，字符串为数组，包含 orderMoney 设置的订单金额，uname 设置的用户名称，goodsId 商品名称， moneyId 价格id，number 数量，addId 地址ID
        $messageJson = $this->request->post('message/s', '');

        $message = json_decode($messageJson, true);
        if (empty($message)) {
            return show(Code::ERROR, [], 10024);
        }
        //验证用户信息
        if (!$this->AuthUser()) {
            throw new \think\Exception(show(Code::DISTANCE, [], 10051), 202);
        }

        foreach ($message as $key => $value) {
            try {
                validate(\app\validate\User::class)
                    ->scene('userPlaceOrder')
                    ->check($value);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return show(Code::ERROR, [], $e->getError());
            }
        }
        CacheRepeatClick('setUserPlaceOrder'.$this->user['id']);
        //数据问题，插入数据
        $data = [];
        $meg = '';

        $orderNo = orderCode('NO' . $this->user['id'] . '-');
        foreach ($message as $key => $value) {
            list($code, $data, $meg, $remarks) = ShopOrderService::getInstance()->setUserPlaceOrder(
                $this->user['id'],
                $value['orderMoney'],
                $value['goodsId'],
                $value['moneyId'],
                $value['number'],
                $value['addIdId'],
                $value['uname'],
                $orderNo
            );
            if ($code == Code::ERROR){
                return show(Code::ERROR, $data, $meg, $remarks);
            }
        }
        return show(Code::SUCCESS, $data, $meg);
    }


    //用户订单页面
    public function GetUserOrderData()
    {
        $postField = 'orderNo';
        $post      = $this->request->only(explode(',', $postField), 'post', null);
        try {
            validate(\app\validate\User::class)
                ->scene('getUserOrderData')
                ->check($post);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return show(Code::ERROR, [], $e->getError());
        }

        //验证用户信息
        if (!$this->AuthUser()) {
            //用户信息不存在，表示 用户邀请人代付，展示代付页面信息
            $data = ShopOrderService::getInstance()->GetUserOrderDataDehalf($post['orderNo']);
        } else {
            //用户信息存在，表示自己查看订单，展示订单信息
            $data = ShopOrderService::getInstance()->GetUserOrderData($this->user['id'], $post['orderNo']);
        }
        return show(Code::SUCCESS, $data);
    }

    //获取订单列表页面
    public function GetUserOrderDataList()
    {
        $page  = $this->request->param('page/d', 1);
        $limit = $this->request->param('limit/d', 10);
        //验证用户信息
        if (!$this->AuthUser()) {
            //用户信息不存在，表示 用户邀请人代付，展示代付页面信息
            return show(Code::ERROR, [], 10013);
        }
        //用户信息存在，表示自己查看订单，展示订单信息
        $data = ShopOrderService::getInstance()->GetUserOrderDataList($this->user['id'], $page, $limit);
        return show(Code::SUCCESS, $data);
    }
}