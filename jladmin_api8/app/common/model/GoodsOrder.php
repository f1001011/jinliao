<?php


namespace app\common\model;
use app\common\traites\TraitModel;
use think\Model;

class GoodsOrder extends Model
{
    use TraitModel;
    public $name = 'common_goods_order';

    public static function page_list($where, $limit, $page, $date)
    {
        $map = self::whereMapUser();
        //时间查询存在
        if (empty($date)) {
            $res = self::alias('a')
                       ->join('common_user b', 'a.uid = b.id', 'left')
                       ->where($where)
                       ->where($map)
                //->where(['a.status' => 1])
                       ->order('a.id desc');
        } else {
            $res = self::alias('a')
                       ->join('common_user b', 'a.uid = b.id', 'left')
                       ->where($where)
                       ->where($map)
                       ->whereBetweenTime('a.create_time', $date['start'], $date['end'])
                       ->order('a.id desc');
        }
        return $res->field('a.*,b.phone')->paginate(['list_rows' => $limit, 'page' => $page], false)->each(function($item, $key){
            $item['status_text'] =  self::getStatus($item['status']);
            $item['red_way_text'] =  Goods::getRedWay($item['red_way']);
            return $item;
        });
    }

    public static function getStatus($status){
        $str= '';
        switch ($status){
            case 0:$str = '正常';break;
            case 1:$str = '返佣完成，正在分红中';break;
            case 2:$str = '分红完成利息返回完成';break;
            case 3:$str = '本金返回完成，全部完成';break;
        }
        return $str;
    }

    //获取今日分红金额 的总金额
    public static function getDayTimeDividend($map=[],$cache=5){
        return self::whereTime('next_red_date','today')
                   ->field('sum(income) sum_income,sum(user_vip_day_outside) sum_user_vip_day_outside')
                   ->where($map)
                   ->cache($cache)
                   ->find();
    }

    //获取今日到期的本金  和明天的到期本金
    public static function getDayTimePrincipal($today='today' ,$cache=3){
        return self::whereDay('expiration_date',$today)
            //->field('sum(order_money) as ')
                   ->cache($cache)
                   ->sum('order_money');
    }

    //获取指定时间范围内的分红金额
    public static function getDateTimeTotal($map=[],$date=[],$cache=3){
        $model = self::alias('a')->where($map)->cache($cache)->field('sum(income) sum_income,sum(user_vip_day_outside) sum_user_vip_day_outside');
        if (empty($date)){
            return $model->find();
        }
        return $model->whereBetweenTime('next_red_date',$date['start'],$date['end'])->find();
    }

}