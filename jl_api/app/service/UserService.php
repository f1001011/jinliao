<?php

namespace app\service;

use app\model\AdsModel;
use app\model\GoodsCommentModel;
use app\model\GoodsLabelModel;
use app\model\GoodsModel;
use app\model\GoodsTypeModel;
use app\model\UserAddressModel;
use app\model\UserAllocationModel;
use app\model\UserConfigModel;
use think\console\output\Ask;
use think\facade\Db;

class UserService extends BaseService
{
    //获取用户的下单地址
    public static function getUserAddress($uid)
    {
        return UserAddressModel::pageList(['uid'=>$uid]);
    }

    //用户地址添加
    public function getUserAddressAdd(mixed $uid,array $post)
    {
        CacheRepeatClick('getUserAddressAdd'.$uid);
        //给用户修改数据。不存在就是添加
        if (isset($post['addressId']) && $post['addressId'] >0){
            $map=[
                ['uid','=',$uid],
                ['id','=',$post['addressId']],
            ];
            unset($post['addressId']);

            return UserAddressModel::pageUpdate($map,$post);
        }

        $post['uid'] = $uid;
        return UserAddressModel::inserts($post);
    }

}