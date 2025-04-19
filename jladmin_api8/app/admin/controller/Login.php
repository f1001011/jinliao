<?php


namespace app\admin\controller;


use app\BaseController;
use app\common\model\AdminModel;
use app\common\model\BankModel;
use app\common\model\TokenModel;

use app\common\model\UserModel;
use app\validate\Login as validates;
use think\exception\ValidateException;

class Login extends BaseController
{
    public function initialize()
    {
        //session('admin_user', '');
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * 登陆控制器
     */
    public function index()
    {
        $post = $this->request->post();
        //验证数据
        try {
            validate(validates::class)->scene('admin_login')->check($post);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return $this->failed($e->getError());
        }

        //查询数据库 查看账号密码是否匹配
        $res = (new AdminModel())->where(['pwd' => pwdEncryption($post['pwd']), 'user_name' => $post['user_name']])->find();

        if (empty($res)) return $this->failed('账号或者密码不匹配');
        $res = $res->toArray();
        //验证验证码是否正确
//        $arr = $post['captcha'] ==config('ToConfig.captcha') ? true: self::goods_code($post['captcha'],$res['invitation_code']);
//        if (!$arr) return $this->failed('验证码错误');
        //生成token 并存入session,并存入数据库
        $token = api_token($res['id']);
        //查询是否存在这条token的用户
        $update = (new TokenModel)->where('admin_uid', $res['id'])->where('type', 1)->update(['token' => $token, 'create_time' => date('Y-m-d H:i:s')]);

        //数据不存在时插入
        if ($update == 0) {
            (new TokenModel)->insert(['type' => 1, 'token' => $token, 'admin_uid' => $res['id'], 'create_time' => date('Y-m-d H:i:s')]);
        }

        $res['token'] = $token;
        session('admin_user', $res);
        (new \app\common\service\LoginLog())->login();//登陆日志
        //return $this->success(['token' => $token,'user'=>$res]);
        return $this->success(['token' => $token, 'user' => $res]);
    }


    //原有的 绑定了银行卡的账号，把用户绑定的手机号，转移过来
    public function setBankPhone(){
        $sel = BankModel::where('u_phone','=',null)->select();
        foreach ($sel as $Key=>$value){
            $phone = UserModel::where('id',$value['u_id'])->value('phone');
            BankModel::where('u_id','=',$value['u_id'])->update(['u_phone'=>$phone]);
        }
    }
}