<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\model\TokenModel;
use app\common\model\UserModel;
use app\common\traites\ApiResponseTrait;

class Agent
{
    use ApiResponseTrait;

    //查询代理商的所有下一级 ID
    public function handle($request, \Closure $next)
    {
////        校验token
//        $token = $request->post('token');
//        if(empty($token)){
//            $token = $request->param('token');
//        }
//        if (empty($token)) return $this->failed('token不存在');
//        //校验是否过期的token
//        //$type 1后台用户 2代理商后台
//        $map['type'] = 2;
//        $map['token']= $token;
//        $res = (new TokenModel())->where($map)->find();
//        if (empty($res)) return $this->failed('无效token');
//
//        $expiration_time = time() - strtotime($res['create_time']);
//        if ($expiration_time >= env('token.token_time', 10)) return $this->failed('token过期');
//        $array = [];
        //代理的下一级用户
//        $array['uid'] = $res['admin_uid'];
//        $array['agent_one'] = UserModel::where('agent_id','=',$res['admin_uid'])->column('id');
//        $array['agent_two'] = UserModel::where('agent_id_2','=',$res['admin_uid'])->column('id');
//        $array['agent_three'] = UserModel::where('agent_id_3','=',$res['admin_uid'])->column('id');
//        $array['agent'] = array_merge($array['agent_one'],$array['agent_two'],$array['agent_three']);
//        session('agent_info', $array);
//        return $next($request);

    }
}
