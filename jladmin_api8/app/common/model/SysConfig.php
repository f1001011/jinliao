<?php


namespace app\common\model;


use app\common\traites\TraitModel;
use think\Model;

class SysConfig extends Model
{
    use TraitModel;
    public $name = 'common_sys_config';
    
    //推广分成完成任务，可获得 奖励
    public function tgFengcheng($uid,$agent_id){
        
        if($agent_id <= 0){
            return true;
        }
        
        //1 查询用户是否是第一次购买
         $TouziProductOrder = new TouziProductOrder();
         $ucount = $TouziProductOrder->where('user_id',$uid)->count();
        
        if($ucount >0){ //用户购买过了，不计算到任务
            return true;
        }
        
         //查询上一级 用户 今日完成任务情况  上一级用户 邀请的新用户，今日购买的情况
         $UserModel = new UserModel();
         //$is_day_red_count = $UserModel->where('id',$agent_id)->inc('is_day_red_count',1)->update();//该用户每日邀请有效购买 次数
         
         //查询代理用户信息
         $agentInfo = $UserModel->where('id',$agent_id)->find();
          
         //查询用户应该获得查询的等级
        
         $is_day_red_lv = $agentInfo['is_day_red_lv']+1;
        if($is_day_red_lv <=0){
            $is_day_red_lv = 1;
        }
         //获取本次 购买任务应该的到的奖励
         $tg_fengcheng_find =  $this->where('name','tg_fengcheng_v'.$is_day_red_lv)->find();
         
         if(empty($tg_fengcheng_find)){
              return true;
         }
        
         $huodejiangliarray = explode("|",$tg_fengcheng_find['value']);
        
        
         if($agentInfo['is_day_red_count']+1 >= $huodejiangliarray[0]){
             
             
             //获得本次分成
             $a=$UserModel->where('id',$agent_id)->inc('is_day_red_lv',1)->inc('is_day_red_count',1)->inc('money_balance',$huodejiangliarray[1])->update();
             
             //写入资金记录
             (new \app\common\model\MoneyLog())->insert([
                            'create_time' => date('Y-m-d H:i:s'),
                            'type' => 1,
                            'class' => 1,
                            'status' => 106,
                            'money_before' => $agentInfo['money_balance'],
                            'money_end' =>$agentInfo['money_balance']+$huodejiangliarray[1],
                            'money' => $huodejiangliarray[1],
                            'uid' => $agent_id,
                            'market_uid' => $agent['market_uid'] ?? 0,
                            'source_id' => $uid,
                            'mark' => '用户任务完成LV'.$is_day_red_lv,
                            'phone' => $agentInfo['phone'],
            ]);
       
         }else{
             $UserModel->where('id',$agent_id)->inc('is_day_red_count',1)->update();
         }
         //任务没达到等级
         return true;
        
    }
}