<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\CodeName;
use app\model\TokenModel;
use app\model\UserModel;

class TokenMiddleware
{
    /**
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //获取请求信息
        $token = $request->header('token');
        if (empty($token)) {
            return response(show(CodeName::TOKEN_EXPIRE, [], 10001));
        }

        $sign = $request->header('sign');
        if (empty($sign)) {
            return response(show(CodeName::TOKEN_EXPIRE, [], 10002));
        }

        $find = TokenModel::pageOne([['token', '=', $token]], '*',3);//token 后期可保存到 redis
        if (empty($find) || empty($find->create_time)) {
            return response(show(204, [], 1032));
        }

        $create_time = strtotime($find->create_time);
        if (time() > ($create_time + env('TOKEN.HOME_TOKEN_TIME', 60 * 60 * 12))) {
            return response(show(204, [], 1032));
        }

        $UserInfo          = UserModel::pageOne([['id', '=', $find['user_id']]], '*',5);
        $request->UserInfo = $UserInfo;

        if ($UserInfo->status != 1) {
            return response(show(206, [], 1048));
        }

        return $next($request);
    }
    public function end(\think\Response $response)
    {
        // 回调行为
    }
}
