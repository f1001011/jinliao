<?php


namespace app\common\model;
use app\common\traites\TraitModel;
use think\Model;

class Goods extends Model
{
    use TraitModel;
    public $name = 'common_goods';

    public static function page_list($where,$limit,$page,$order)
    {
       // $map=self::whereMap();
        return self::where($where)
            //->where($map)
            ->order($order)
            ->paginate(['list_rows'=>$limit,'page'=>$page], false)->each(function($item, $key){
                $item['red_way_text'] =  self::getRedWay($item['red_way']);
                $item['goods_type_name'] =  GoodsType::page_one_value($item['goods_type_id']);
                $item['head_img_text'] =  config_image($item['head_img']);
                $item['head_img'] =  config_image($item['head_img']);
                $item['bottom_img'] =  config_image($item['bottom_img']);
                $item['bottom_img_text'] =  config_image($item['bottom_img']);
                $item['goods_day_red'] = GoodsDay::page_column($item['id']);

                return $item;
            });
    }

    public static function page_sel_list($map,$order='a.id asc'){
        return self::where($map)->alias('a')->order($order)->leftJoin((new GoodsType())->name .' b','a.goods_type_id=b.id')->field('a.*,b.type_name')->select();
    }


    //获取投注类型
    public static function getRedWay($RedWay){
        $str = '';
        switch ($RedWay){
            case 1:$str = '到期还本还息';break;
            case 2:$str = '到期返本按周付息';break;
            case 3:$str = '按天付息';break;
        }
        return $str;
    }

    public static function getGoodsName($id){
        if (empty($id) || $id <=0){
            return '';
        }
        return self::where('id',$id)->value('goods_name');
    }

    public static function getGoodsMoney($id){
        if (empty($id) || $id <=0){
            return '';
        }
        return self::where('id',$id)->value('goods_money');
    }
}