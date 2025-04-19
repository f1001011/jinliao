<?php


namespace app\admin\controller;

use app\admin\ApiBaseController;//不需要验证
use app\admin\UserBaseController;
use app\common\google\GoogleAuth;

//需要验证

class Base extends UserBaseController
{

    public function initialize()
    {
       // var_dump(generateCode());die;
//        $res = (new GoogleAuth())->model()->google_qrcode('aaaa');
//        var_dump($res);die;
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    //生成google二维码地址
    public function captcha_url()
    {
        $secret = $this->request->param('secret');
        if (empty($secret)) return [];
       return  captchaUrl($secret);
    }

    //生成google $secret
    public function generate_code()
    {
        return generateCode();
    }
}