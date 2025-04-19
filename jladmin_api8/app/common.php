<?php

use app\common\model\TokenModel;

function UserTeam(){
    return mt_rand(1000,9999)+mt_rand(10000,99999)+mt_rand(100000,999999)+mt_rand(1000000,9999999)+mt_rand(10000000,90000000);
}
/**
 * 前台用户密码加密
 * @param $pwd
 */
function pwdEncryption($pwd)
{
    if (empty($pwd))
        return false;
    return base64_encode($pwd);
}

//默认密码
function admin_Initial_pwd()
{
    return base64_encode('aa123456');
}
//用户默认密码
function home_Initial_pwd()
{
    return base64_encode('aa123456');
}

//用户提现默认密码
function home_tx_pwd()
{
    return 'aa123456';
}

function api_token($id)
{
    return md5($id . 'api' . date('Y-m-d H:i:s', time()) . 'token') . randomkey(mt_rand(10, 30));
}

function home_api_token($id)
{
    return md5($id . 'home' . date('Y-m-d H:i:s', time()) . 'token') . randomkey(mt_rand(10, 30));
}

function url_code()
{
    return $_SERVER['REQUEST_SCHEME'] . '://';
}

function tg_url()
{
    return $_SERVER['REQUEST_SCHEME'] . '://' . randomkey(5) .'.'. config('ToConfig.app_tg.tg_url') . '?codes=';
//  return  $_SERVER['REQUEST_SCHEME'] . '://'.'www'. config('ToConfig.app_tg.tg_url') . '?codes=';
}
/**
 * 生成邀请码 代理掩码
 * @return string
 */
function generateCode($start=32,$end=50)
{
    return (new \app\common\google\GoogleAuth())->model()->generate_code();
    //return randomkey(rand($start, $start));
}

/**
 * 生成用户 google验证码二维码地址
 * @param $secret
 * @return mixed
 */
function captchaUrl($secret)
{
    return (new \app\common\google\GoogleAuth())->model()->google_qrcode($secret);
}

//图片上传处理
function image_update($string)
{
    //return explode('/storage',$string)[1];
    $column = array_column($string, 'url');

    foreach ($column as $key => &$value) {

        if(is_array($value)){

            $value = explode('/storage', $value[0])[1];;
        }else{
            $value = explode('/storage', $value)[1];;
        }


    }

    return implode(',', $column);
}

//购买商品生成订单号
function orderCode($string = 'video')
{
    if (empty($string))
        return false;
    //生成订单 字符串 + 随机数 + 时间 +
    return $string .mt_rand(1000, 9999). date('YmdHis');
}

//订单错误时生成日志，可查看
function buildHtml($value, $type = 'o')
{
    $cachename = 'order_log/' . $type . date("Y-m-d") . ".html";
    $value = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) . PHP_EOL : $value;
    file_put_contents($cachename, date("Y-m-d H:i:s") . '--' . $value . PHP_EOL, FILE_APPEND);
}

//地址掩码 20—40位
function randomkey($length)
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .=  substr($pattern, mt_rand(0, 35), 1); //生成php随机数
    }
    return $key;
}

//生成用户账号 10 - 30
function userkey($length)
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .=  substr($pattern, mt_rand(0, 35), 1); //生成php随机数
    }
    return 'user' . $key . date('Ymd');
}

//加密 rsa
function rsa_encrypt($data)
{
    openssl_public_encrypt($data, $encrypted, config('ToConfig.public_key'));
    return base64_encode($encrypted);
}

//解密 rsa
function rsa_decrypt($encrypted)
{
    $encrypted = base64_decode($encrypted);
    openssl_private_decrypt($encrypted, $decrypted, config('ToConfig.private_key'));
    return $decrypted;
}

/**
 * 忽略订单计算方法
 * @param $count /订单数量
 * @return bool
 */
function orderIgnore($count)
{
    //大于 设定的订单数。，并且取莫  每5单抽取一单
    if ($count > config('ToConfig.order_ignore') && rand(1,5) == 3) {
        return true;
    }
    return false;
}

/**
 * 获取配置文件
 * @param null $name
 * @return \think\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 */
function get_config($name=null)
{
    if ($name == null){
        return \app\common\model\SysConfig::select();
    }
    return \app\common\model\SysConfig::where('name',$name)->find();

}

function account_vip($post)
{

}

/**
 * @param array $data
 * @param int $code
 * @param string $message
 * @param int $httpStatus
 * @return \think\response\Json return会出现重复请求的问题
 */

function show($data = [],int $code = 1,string $message = 'success！',int $httpStatus = 0){
    $result=[
        'code'=>$code,
        'message'=>$message,
        'data'=>$data,
    ];
    header('Access-Control-Allow-Origin:*');
    if ($httpStatus != 0){
        return json($result,$httpStatus);
    }
    // return json($result);
    echo json_encode($result);
    exit();
}

/*
 * 富文本存储，需要把域名替换下
 */
function saveEditor($content){

    return str_replace(config('ToConfig.app_update.image_url'),'',$content);
}

/*
 * 富文本返回，需要把域名加上
 */
function returnEditor($content){
    return str_replace('/topic/',config('ToConfig.app_update.image_url').'/topic/',$content);
}
/*
 * 定义返佣类型
 * */
function fan_yong_type($type){
    $arr=array(
        1=>'first level commission rebate',
        2=>'second level commission rebate',
        3=>'third level commission rebate',
    );
    return isset($arr[$type]) ? $arr[$type] :'';
}
/**
 * 两个一维数组键值互换
 * */
function transIndex($index, $Data) {
    $return = array();
    foreach ($index as $key => $value) {
        $return[$value] = $Data[$key];
    }
    return $return;
}
/**
 *
 * 屏蔽数据
 * */
function ysPhone($num){
    $res=substr_replace($num,'****',3,4);
    return $res;
}
/*

 * 函数说明：富文本数据进行转换成文本

 *

 * @access  public

 * @param   $content  string  富文本数据

 * @return  string    不包含标签的文本

 */

function contentFormat($content = ''){
    $data = $content;

    $formatData_01 = htmlspecialchars_decode($data);//把一些预定义的 HTML 实体转换为字符

    $formatData_02 = strip_tags($formatData_01);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容

    return $formatData_02;
}
/**
 * Name [[验签]]
 * User:
 */
function verifySignApplet($input)
{
    $key = 'b0a7ff4003a6f48ab85557ca4b8619ee';
    $param = $input;
    unset($param['sign']);
    unset($param['s']);
    ksort($param);
    $paramStr = '';
    foreach ($param as $paramKey => $paramVal)
    {
        if(is_array($paramVal)) continue;
        if($paramVal === null || $paramVal === '')continue;
        $paramStr.=$paramKey.'='.$paramVal.'&';
    }
    $paramStr.='key='.$key;
    $newSign = md5($paramStr);
    return $newSign;
}

/*
 * post请求
 * */
function curl_post($url,$body,$way='POST')
{
    $headers = array();
    $headers[] = "Content-Type:application/json";
    $postBody    = json_encode($body);
    $curl = curl_init();
    $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
    if ($ssl) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//设置请求头
    if(!empty($postBody)){
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postBody);//设置请求体
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $way);//使用一个自定义的请求信息来代替"GET"或"HEAD"作为HTTP请求。(这个加不加没啥影响)
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
/**
 * 开始并发控制
 * @param $name
 * @param int $time
 * @return bool
 */
function start_concurrent($name,$time = 60)
{
    $name .= 'QTX_**#_';
    if(\think\facade\Cache::store('redis')->get($name)){
        return false;
    }
    \think\facade\Cache::store('redis')->set($name,1,$time);
    return true;
}

/**
 * 结束并发控制
 * @param $name
 */
function end_concurrent($name)
{
    $name .= 'QTX_**#_';
    \think\facade\Cache::store('redis')->delete($name);
}

//字母
/**
 * @param $length
 * */
function userzm($length)
{
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .=  substr($pattern, mt_rand(0, 35), 1); //生成php随机数
    }
    return $key;
}

function config_image($str = ''){
    if (empty($str)){
        return '';
    }
    return config('ToConfig.app_update.image_url') . '/' .$str;
}

//$click 可填写 用户ID或者用户 IP
function CacheRepeatClick(string $click ,$ttl = 3){
    //防止重复点击
    //用户不可重复点击
    $key = sprintf('CacheRepeatClick:%s',$click);
    if ($a= \think\facade\Cache::get($key)){
        show([$click,$a],0,'不可重复点击');
    }
    \think\facade\Cache::set($key,$click,$ttl);
}

function traceLogs($message, $lv = 'info', $channel = 'job')
{
    \think\facade\Log::channel($channel)->$lv($message);
}

function traceLog($message, $lv = '')
{
    trace($message, $lv . '-RESPONSE_ID:' . REQUEST_ID);
}

// 自定义回调函数
function custom_filter($value) {
    // 只移除 null, false, 空字符串, 和空数组
    return !($value === null || $value === false || $value === '' || is_array($value) && empty($value));
}