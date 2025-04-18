<?php

namespace app\model;


class UserAllocationModel extends BaseModel
{
    public $name = 'common_user_allocation';
    CONST TYPE_CLASS_GOODS = 2;
    CONST TYPE_CLASS_BANNER = 1;
    public function ads()
    {
        return $this->belongsTo('AdsModel', 'class_id')->where('status',1)->field('id,img,url');
    }
}