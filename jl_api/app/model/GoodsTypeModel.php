<?php

namespace app\model;


class GoodsTypeModel extends BaseModel
{
    public $name = 'common_goods_type';

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;
    public function children()
    {
        return $this->hasMany('GoodsTypeModel', 'pid')
            ->where('status', 1)
            ->order('sort', 'desc')
            ->field('id,type_name,image,pid'); // 只获取需要的字段
    }
}