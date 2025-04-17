<?php

namespace app\model;


class AdsModel extends BaseModel
{
    public $name = 'common_ads';
    public function getImgAttr($value)
    {
        //1 赋值域名 ，2 数组序列化
        if (empty($value)){
            return '';
        }
        return TC('goods_image_url').'/'.$value;
    }


}