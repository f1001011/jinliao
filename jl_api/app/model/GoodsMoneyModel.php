<?php

namespace app\model;


class GoodsMoneyModel extends BaseModel
{
    public $name = 'common_goods_money';

    public function goodsType()
    {
        return $this->belongsTo('GoodsTypeModel', 'goods_type_id')
            ->field('id, type_name,image'); // 只获取需要的分类字段
    }
}