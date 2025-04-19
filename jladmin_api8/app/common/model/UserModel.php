<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\facade\Db;
use think\Model;

class UserModel extends Model
{
    use TraitModel;

    public $name = 'common_user';

    const VipGuaranteeArr = [
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
    ];
    //获取用户总的邀请人数
    public static function getUserAgentCount($map=[]){
        return self::where($map)->count();
    }

    public static function page_list($where, $limit, $page)
    {

    }

//    public static function ip_check($map = [] , $page , $limit){
//        $result = UserModel::field('
//        ip,
//        COUNT(id) as num,
//        SUM(CASE WHEN is_frozen = 1 THEN 1 ELSE 0 END) as frozen_count,
//        SUM(CASE WHEN vip_lv !=0 THEN 1 ELSE 0 END) as vip_lv_count
//        ')
//            ->where($map)
//            ->group('ip')
//            ->order('num desc')
//            ->paginate(['list_rows' => $limit, 'page' => $page], false);
//        return $result;
//    }
    //代理商个人信息
//    public static function page_one($limit,$page)
//    {
////        $map = self::whereMap();
//        //if (empty($map)) return false;
//        $map = [];
//        if (session('admin_user.agent')){
//            $map['a.id'] = session('admin_user.id');
//        }else{
//            $map['a.type'] =1;
//        }
//
//        return self::alias('a')
//            ->where($map)
//            ->join('common_admin b', 'a.market_uid = b.id', 'left')
//            ->field('a.*,b.user_name admin')
//            ->paginate(['list_rows' => $limit, 'page' => $page], false)
//            ->each(function ($item, $key) {
//                $item->tg_url_txt = $_SERVER['REQUEST_SCHEME'] . '://' . randomkey(5) .'.'. config('ToConfig.app_tg.tg_url') . '?code=' . $item->invitation_code;
//            });
//    }

    public function info($id){
        return $this->find($id);
    }


//    public function refuse_money($uid, $money){
//        $this->where('id', $uid)->dec('money_freeze',$money)->dec('total_withdraw',$money)->inc('money_approve', $money)->update();
////        $this->where('id', $uid)->inc('total_withdraw', $money * -1);
//    }

//    public function refuse_team_money($uid, $money){
//        $this->where('id', $uid)->inc('money_hire', $money)->update();
//    }
    public function add_total_withdraw($uid, $money){
       return  $this->where('id', $uid)->inc('total_withdraw', $money)->update();
    }

    //直接删除
    public function del($id)
    {
        $find = $this->find($id);
        if (empty($find))
            return false;
        return $find->delete();
    }
}