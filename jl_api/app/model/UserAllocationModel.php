<?php

namespace app\model;


class UserAllocationModel extends BaseModel
{
    public $name = 'common_user_allocation';

    public function ads()
    {
        return $this->belongsTo('AdsModel', 'class_id')->where('status',1)->field('id,img,url');
    }
}