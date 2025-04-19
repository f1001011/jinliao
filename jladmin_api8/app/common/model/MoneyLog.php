<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class MoneyLog extends Model
{
    public $name = 'common_money_log';
    use TraitModel;
    const FAN_AGENT_1 = 106;//1级首充会员反水
    const FAN_AGENT_2 = 108;//2级首充会员反水
    const FAN_AGENT_3 = 109;//3级首充会员反水
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'uid');
    }

    public function getTypeAttr($value)
    {
         $type = [1 => '收入', 2 => '支出', 3 => '官方', 4 => '提现退款'];
        return isset($type[$value]) ? $type[$value] : $value;
    }



public function getPayTypeAttr($value)
    {
        $type = [5 => '余额钱包', 6 => '充值钱包'];
        return isset($type[$value]) ? $type[$value] : $value;
    }

    public function getStatusAttr($value)
    {

        $type = [
            101=>'用户充值',
            102=>'用户提现',
            201=>'佣金',
        ];

         return isset($type[$value]) ? $type[$value] : '管理调整';
    }
    public static function page_list($where,$limit,$page,$order, $date=[])
    {
        $map=self::whereMap();
        $model = self::alias('a');
        if (!empty($date)){
            $model= $model->whereBetweenTime('a.create_time',$date['start'],$date['end']);
        }
        return $model->where($where)
            ->where($map)
            ->join('common_user b', 'a.uid = b.id','left')
            ->join('common_admin c', 'a.market_uid = c.id','left')
            ->field('a.*,b.user_name,b.phone,b.nickname,c.user_name admin_name')
            ->order($order)
            ->paginate(['list_rows'=>$limit,'page'=>$page], false)->each(function($item, $key){
               if($item['money_before'] > $item['money_end']){
                   $item['money'] = '-'.$item['money'];
               }else{
                   $item['money'] =  '+'.$item['money'];
               }
                return $item;
            });
    }


    public static function inserts($type,$status,$money,$money_before,$money_end,$uid,$market_uid,$mark,$source_id,$payType=0){
        return self:: insertGetId([
            'create_time' => date('Y-m-d H:i:s'),
            'type' => $type,
            'status' => $status,
            'money_before' => $money_before,
            'money_end' => $money_end,
            'money' => $money,
            'uid' => $uid,
            'market_uid' => $market_uid,
            'source_id' => $source_id,
            'mark' => $mark,
            'pay_type'=>$payType,
        ]);
    }

}