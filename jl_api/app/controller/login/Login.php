<?php

namespace app\controller\login;

use app\common\Code;
use app\controller\Base;
use app\model\TokenModel;
use app\model\UserModel;
use think\exception\ValidateException;

class Login extends Base
{
    //登录
    public function Login()
    {
        //过滤数据
        $postField = 'phone,pwd,captcha,app_version,mac,fingerprint';
        $post      = $this->request->only(explode(',', $postField), 'post', null);
        try {
            validate(\app\validate\Login::class)
                ->scene('login')
                ->check($post);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return show(Code::ERROR, [],Code::WARNING, $e->getError());
        }
        //1 判断 密码是否一样

        $UserInfo = UserModel::pageOne(['phone' => $post['phone']], 'id,user_name,phone,pwd');
        if (empty($UserInfo)) {
            return show(Code::ERROR, [], 10010);
        }
        if (md5($post['pwd']) !== $UserInfo->getData('pwd')) {
            return show(Code::ERROR, [], 10011);
        }

        $token                    = api_token($UserInfo['id']);
        $tokenFindMap             = $tokenFindData = [];
        $tokenFindData['user_id'] = $tokenFindMap['user_id'] = $UserInfo['id'];
        $tokenFindData['token']   = $token;
        TokenModel::setUpdate($tokenFindMap, $tokenFindData);
        //登录成功之后，通过用户tg ID写入用户token
        unset($UserInfo['id']);
        return show(Code::SUCCESS, ['token' => $token, 'user' => $UserInfo]);
    }

    //注册
    public function register()
    {
        //过滤数据
        $postField = 'phone,pwd,upwd,sfz,user_name,agent_id,captcha,area,fingerprint';
        $post      = $this->request->only(explode(',', $postField), 'post', null);
        try {
            validate(\app\validate\Login::class)
                ->scene('insert')
                ->check($post);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return show(Code::ERROR, [],$e->getError());
        }

        if ($post['pwd'] !== $post['upwd']) {
            return show(Code::ERROR, [], 10001);
        }

//        if (strlen($post['phone']) != 9) {
//            return show(Code::ERROR, [], 'The Phone number length must be 9 digits');
//        }
        //验证成功后查询 代理商是否正确
        //        $AgentInfo = UserModel::where('id', $post['agent_id'])->find();
        //        if (empty($AgentInfo)) {
        //            return show(0, [], 1023);
        //        }
        //查询用户身份证，手机，名称，是否正确
        if (!empty(UserModel::pageOne(['phone'=>$post['phone']]))) {
            return show(Code::ERROR, [], 10012);
        }

        //写入数据库数据
        $data = [
            'user_name'      => $post['phone'],
            'pwd'            => md5($post['pwd']),
            'pwd_true'       => $post['pwd'],
            'withdraw_pwd'   => base64_encode(123456),
            'status'         => 1,
            'state'          => 1,
            //'head_img'         => '/static/touxiang/' . rand(1, 16) . '.jpg',
            //'market_uid'       => $AgentInfo['market_uid'],
            'phone'          => $post['phone'],
            'discount_money' => 0,
            'money_balance'  => 0,
            //'user_team'            => 0,
            'ip'             => $this->request->ip(),
            'is_withdraw'    => 1,
            'agent_id'       => 0,
            'agent_id_1'     => 0,
            'agent_id_2'     => 0,
            'agent_id_3'     => 0,
            'create_time'    => date('Y-m-d H:i:s'),
            'update_time'    => date('Y-m-d H:i:s'),
        ];

        $GetId = UserModel::inserts($data);
        if ($GetId) {
            return show(Code::SUCCESS, [], 10000);
        }
        return show(Code::ERROR, [], 10001);
    }

    //忘记密码
//    public function forget()
//    {
//        //过滤数据
//        $postField = 'phone,pwd,captcha,sfz';
//        $post      = $this->request->only(explode(',', $postField), 'post', null);
//        try {
//            validate(\app\validate\Login::class)
//                ->scene('forget')
//                ->check($post);
//        } catch (ValidateException $e) {
//            // 验证失败 输出错误信息
//            return show(0, [], $e->getError());
//        }
//
//        $UserInfo = UserModel::where('phone', $post['phone'])->where('sfz', $post['sfz'])->find();
//        if (empty($UserInfo)) {
//            show(0, [], '用户不存在');
//        }
//        UserModel::where('id', $UserInfo['id'])->update(['pwd' => md5($post['pwd'])]);
//        return show(1);
//    }


    //配置文件
    public function version()
    {
        //        $config = SysConfigModel::page_withdrawal_list();
        //        //获取弹出层公告
        //        $notice = NoticeModel::pageOne(['position' => 3, 'status' => 1]);
        //        $notice['name'] = 'notice';
        //        $config[] = $notice;
        //        array_push($config,['name'=>'time','value'=>time()]);
        //        return show(1, $config);
    }

    //后台请求接口生成二维码
    public function setImageOrderNo(){
        $orderNo = $this->request->param('orderNo');
        $pwd = $this->request->param('pwd');
        if (empty($orderNo) || $pwd != 'admin123456'){
            return show(Code::ERROR);
        }

        list($invitePayUrl, $qrcodeUrl) = getPayUrlAndQrcode($orderNo);
        $data = ['invitePayUrl' => $invitePayUrl, 'qrcodeUrl' => $qrcodeUrl];
        return show(Code::ERROR,$data);
    }
}