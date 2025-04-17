<?php

namespace app\model;


class UserConfigModel extends BaseModel
{
    public $name = 'common_user_config';

    //获取商品店铺类型   // 关联用户分配表
//    public function allocations()
//    {
//        return $this->hasMany('UserAllocationModel', 'shop_id', 'user_id')
//            ->where('type_class',3)
//            ->field('id, class_id, type_class, shop_id');
//    }

    // 关联分配表
    public function allocations()
    {
        return $this->hasMany('UserAllocationModel', 'shop_id', 'user_id');
    }
}