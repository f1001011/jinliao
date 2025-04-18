<?php

namespace app\model;


class GoodsModel extends BaseModel
{


    public $name = 'common_goods';




    public function getHeadImgAttr($value)
    {
        //1 赋值域名 ，2 数组序列化
        if (empty($value)) {
            return [];
        }
        $array = unserialize($value);
        $getArray = [];
        foreach ($array as $k => $v) {
            $getArray[$k] = TC('goods_image_url') . '/' . $v;
        }
        return $getArray;
    }

    // 关联商品价格表
    public function goodsLabel()
    {
        return $this->hasMany('GoodsLabelModel', 'gid')
            ->order('sort', 'desc')
            ->field('id, title, gid');
    }
    public function goodsType()
    {
        return $this->belongsTo('GoodsTypeModel', 'goods_type_id')
            ->field('id, type_name,image'); // 只获取需要的分类字段
    }

    // 关联商品价格表
    public function prices()
    {
        return $this->hasMany('GoodsMoneyModel', 'gid')
            ->order('sort', 'desc')
            ->field('id, money_name,money, gid');
    }

    //查询指定的数据
    public static function pageLimitSelectList($map, $limit, $order = ['buy_num' => 'desc'])
    {
        return self::where($map)->limit($limit)->order($order)->select();
    }
}