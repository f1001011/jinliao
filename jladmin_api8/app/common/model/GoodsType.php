<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class GoodsType extends Model
{
    use TraitModel;
    public $name = 'common_goods_type';

    public static function page_list($where,$limit,$page,$order)
    {
        // $map=self::whereMap();
        return self::where($where)
            //->where($map)
            ->order($order)
            ->paginate(['list_rows'=>$limit,'page'=>$page], false);
    }


    public static function page_one_value($id){
        if ($id <= 0){
            return '';
        }
       return  self::where('id',$id)->value('type_name');
    }


}