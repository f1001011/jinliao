<?php
// 应用公共文件
use think\facade\Cache;

function success(array $data = [], $message = 'message', int $code = 200)
{
    echo json_encode(['data' => $data, 'message' => $message, 'code' => $code]);
    die;
}

function fail(array $data = [], $message = 'message', int $code = 500)
{
    echo json_encode(['data' => $data, 'message' => $message, 'code' => $code]);
    die;
}

function language(string $name = '')
{
    return lang($name);
}

function api_token($id)
{
    return md5($id . 'api' . date('Y-m-d H:i:s', time()) . 'token');
}

function TC($value)
{
    return config("toConfig." . $value);
}

//返回方法
function show($code, $data = [], $msg = "", $remarks = '')
{
    empty($msg) && $msg = 'Success';

    if (intval($msg) >= 10000) {
        $msg = language($msg);
    }

    //msg 读取配置文件
    $result = array(
        'code'      => $code,
        'msg'       => $msg,
        'data'      => $data,
        'time'      => time(),
        'remarks'   => $remarks,
        'sub_token' => REQUEST_ID
    );
    //输出json
    return json($result);
    exit;
}

//$click 可填写 用户ID或者用户 IP
function CacheRepeatClick(string $click)
{
    //防止重复点击
    //用户不可重复点击
    $key = sprintf('CacheRepeatClick:%s', $click);
    if (Cache::get($key)) {
        throw new \think\Exception(show(0, [], 10050), 202);
    }
    Cache::set($key, $click, 10);
}

function orderCode($string = '')
{
    //    if (empty($string))
    //        return false;
    //生成订单 字符串 + 随机数 + 时间 +
    return $string . mt_rand(100000000, 999999999).'-' . date('YmdHis');
}

//生成二维码和付款连接
function getPayUrlAndQrcode($orderNo = '')
{
    //付款连接地址
    $invitePayUrl = TC('invite_pay_url') . '?orderNo=' . $orderNo;
    $qrcde        = app()->getRootPath() . '/public/static/qrcode/' . $orderNo . '.png';
    $qrcodeUrl       = TC('qrcode_url').'/';

    if (!file_exists($qrcde)) {
        $qrcodeUrl .= \app\service\ImageContractService::getInstance()->generate(
            $orderNo,
            $invitePayUrl
        );
    }
    return [$invitePayUrl, $qrcodeUrl];
}