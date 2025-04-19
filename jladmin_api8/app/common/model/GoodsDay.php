<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class GoodsDay extends Model
{
    use TraitModel;
    public $name = 'common_goods_day';

    public static function page_list($where,$limit,$page,$order)
    {
        return self::where($where)
            ->order($order)
            ->paginate(['list_rows'=>$limit,'page'=>$page], false)->each(function($item, $key){
                $item['goods_name'] =  Goods::getGoodsName($item['goods_id']);
                $item['goods_money'] =  Goods::getGoodsMoney($item['goods_id']);
                return $item;
            });
    }

    public static function page_column($gid){
        if (empty($gid)){
            return '<span style="color: blue">商品错误</span>';
        }

        //查询对应的商品价格
        $gsel = GoodsMoneyModel::page_sel([['gid','=',$gid]],'sort desc')->toArray();
        if (empty($gsel)){
            return '<span style="color: red">请去添加项目对应价格</span>';
        }

        $sel = self::where('goods_id',$gid)->select()->toArray();
        if (empty($sel)){
            return '<span style="color: red">请去添加项目对应天数</span>';
        }

        $str = '';
        foreach ($gsel as $key=>$value){

            $sel = self::where('goods_id',$gid)->where('mid',$value['id'])->select()->toArray();
            foreach ($sel as $k=>$v){
                if ($value['id'] == $v['mid']){
                    $str .= '价格:'.$value['money'].'天数:'.$v['project_day'].'  每日收益:'.$v['income'].'  总收益:'.$v['total_red_money'].'<br/>';
                }
            }
        }

        return $str;
    }
}