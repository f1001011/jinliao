<?php

namespace app\model;


class TokenModel extends BaseModel
{
    public $name = 'common_home_token';

    public static function setUpdate($map=[],$data=[]){
        $token = self::where($map)->find();
        if (empty($token)){
          return self::inserts($data);
        }
        return self::where($map)->update($data);
    }
}