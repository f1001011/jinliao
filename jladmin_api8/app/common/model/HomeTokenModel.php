<?php


namespace app\common\model;


use think\Model;

class HomeTokenModel extends Model
{
    public $name='common_home_token';

    public static function today($today='today'){
        return self::whereTime('create_time',$today)->count();
    }

    public static function setUpdate($map=[],$data=[]){
        return self::where($map)->update($data);
    }

}