<?php

namespace app\agent\controller\auth;

use app\agent\controller\Base;
use app\common\model\CallBackModel;
use app\common\model\HomeTokenModel;
use app\common\model\PayCash;
use app\common\model\TaskLogModel;
use app\common\model\UserModel;
use app\common\model\VipLogModel;
use think\db\Where;
use think\facade\Db;
use think\Log;

class Statistics extends Base
{


    public function checkCallback(){
        $tfCode = $this->request->post('ft_code', 1);
        $info = CallBackModel::where('trade_on' , $tfCode)->where('is_audit' , 0)->find();
        if(!$info){
            return $this->failed('未找到tf码对应的支付信息');
        }
        return $this->success($info->toArray());
    }
    //统计信息
    public function statistics ()
    {
        $user_team_map[] = ['user_team','=',session('admin_agent_user.user_team')];
        $userId = session('admin_agent_user.id');
        //总注册量
        $UserModel = new UserModel();
        $array['total_register'] = $UserModel->where($user_team_map)->count('id');
        //今日注册
        $array['today_register'] = $UserModel->where($user_team_map)->whereTime('create_time', 'today')->count('id');
        //昨天注册
        $array['yesterday_register'] = $UserModel->where($user_team_map)->cache(60 * 3)->whereTime('create_time', 'yesterday')->count('id');


        $user_team_list = $UserModel->where($user_team_map)->column('id');


        $firstChange = Db::table('ntp_common_vip_log')
                        ->whereIn('user_id',$user_team_list)
                         ->alias('a')
                         ->field('user_id, MIN(create_time) AS first_recharge_time')
                         ->where('action', 1) // 首次充值条件
                         ->group('user_id')                                // 按用户分组
                         ->having('DATE(first_recharge_time) = CURDATE()') // 今天第一次充值
                         ->count();
        //总完成完成任务数
        $countMap = TaskLogModel::countMap([
            ['money', '>=', 0,],
            ['user_id','in',$user_team_list]
        ]);
        \think\facade\Log::channel('command')->info('get info ' . json_encode($countMap->toArray()) );
        $array['first_charge_vip_today'] = $firstChange;
        $array['task_log_total_smoney'] = $countMap['smoney'] ?? 0;
        $array['task_log_total_tcount'] = $countMap['tcount'] ?? 0;
        //今日完成任务数
        $countMap = TaskLogModel::countMap([
            ['money', '>=', 0],
            ['user_id','in',$user_team_list]
        ], 'today');
        $array['task_log_today_smoney'] = $countMap['smoney'] ?? 0;
        $array['task_log_today_tcount'] = $countMap['tcount'] ?? 0;
        //昨日完成任务数
        $countMap = TaskLogModel::countMap([
            ['money', '>=', 0],
            ['user_id','in',$user_team_list]
        ], 'yesterday');
//        $countMap  = TaskLogModel::countMap([],'today');
        $array['task_log_yesterday_smoney'] = $countMap['smoney'] ?? 0;
        $array['task_log_yesterday_tcount'] = $countMap['tcount'] ?? 0;

        //总vip人数
        //VIP总数
        $countMap = VipLogModel::countMap([['user_id','in',$user_team_list]]);
        $array['vip_total_num'] = $countMap['tcount'] ?? 0;
        $array['vip_charge_total'] = $countMap['smoney'] ?? 0;

        $array['vip_charge_tital'] = UserModel::where('id' ,'in' , $user_team_list )->sum('deposit_money') ?? 0;
        $array['vip_tital_num'] = UserModel::where('id' ,'in' , $user_team_list )->where('vip_lv' , '>' , 0)->where('is_frozen' , 0)->count('id') ?? 0;

        //今日充值vip数
        $countMap = VipLogModel::countMap([['user_id','in',$user_team_list]], 'today');
        $array['vip_today_tcount'] = $countMap['tcount'] ?? 0;
        $array['vip_today_smoney'] = $countMap['smoney'] ?? 0;
        //昨日新增vip数
        $countMap = VipLogModel::countMap([['user_id','in',$user_team_list]], 'yesterday');
        $array['vip_yesterday_tcount'] = $countMap['tcount'] ?? 0;
        $array['vip_today_smoney_smoney'] = $countMap['smoney'] ?? 0;
        //一级会员数量
        $array['level_1_vip_num'] = UserModel::where([
            ['agent_id_1' ,'=' , $userId],
//            ['agent_id_2' ,'=' , 0],
//            ['agent_id_3' ,'=' , 0],
            ['vip_lv' ,'>' , 0],
            ['id' ,'in' , $user_team_list]
        ])->count('id') ?? 0;
        $array['level_1_vip_deposit_money'] = UserModel::where([
            ['agent_id_1' ,'=' , $userId],
//            ['agent_id_2' ,'=' , 0],
//            ['agent_id_3' ,'=' , 0],
            ['vip_lv' ,'>' , 0],
            ['id' ,'in' , $user_team_list]
        ])->sum('deposit_money') ?? 0;
        //会员余额  //会员保证金
        $array['user_ubalance'] = UserModel::where($user_team_map)->where('is_frozen' , 0)->sum('money_balance');
        $array['user_udeposit_money'] = UserModel::where($user_team_map)->where('is_frozen' , 0)->sum('deposit_money');

        //今日提现笔数

        //今日提现人数

        //昨日提现人数
        $PayCash = new PayCash();
        $PayCashMap[] = ['status', '=', 1];
//        if (!empty($str_daili_uid)){
//            $PayCashMap[] = ['u_id','not in',$str_daili_uid];
//        }
        $array['total_cash_num'] = $PayCash->whereIn('u_id',$user_team_list)->where($PayCashMap)->count('id');
        $array['today_cash_num'] = $PayCash->whereIn('u_id',$user_team_list)->where($PayCashMap)->whereTime('success_time', 'today')->count();
        $array['total_cash_ben_money'] = $PayCash->whereIn('u_id',$user_team_list)->where($PayCashMap)->sum('money');
        $array['today_cash_ben_money'] = $PayCash->whereIn('u_id',$user_team_list)->where($PayCashMap)->whereTime('success_time', 'today')->sum('money');

        $token = new HomeTokenModel();
        $array['today_user'] = $token->today();
        return $this->success($array);
    }
}