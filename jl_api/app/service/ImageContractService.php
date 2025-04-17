<?php

namespace app\service;

use my\QRcode;

class ImageContractService extends BaseService
{

    public  function generate($uid,$url = 'http', $logo = '')
    {
        $logo = '';
        if (empty($url))
            return false;
        $value = $url; //二维码内容地址 地址一定要加http啥的
        $errorCorrectionLevel = 'H';  //容错级别
        $matrixPointSize = 8;      //生成图片大小
        //生成二维码图片
        $name = $uid.'.png';
        $filename = 'static/qrcode/' .$name ; //生成的二维码图片

        QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        //$logo = 'static/info_msg.png'; //准备好的logo图片 注意地址
        $QR = $filename;      //已经生成的原始二维码图

        if ($logo == ''){
            return $name;
        }

        if (file_exists($logo)) {
            $QR = imagecreatefromstring(file_get_contents($QR));      //目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));  //源图象连接资源。
            $QR_width = imagesx($QR);        //二维码图片宽度
            $QR_height = imagesy($QR);       //二维码图片高度
            $logo_width = imagesx($logo);    //logo图片宽度
            $logo_height = imagesy($logo);   //logo图片高度
            $logo_qr_width = $QR_width / 4;   //组合之后logo的宽度(占二维码的1/5)
            $scale = $logo_width / $logo_qr_width;  //logo的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height / $scale; //组合之后logo的高度
            $from_width = ($QR_width - $logo_qr_width) / 2;  //组合之后logo左上角所在坐标点
            //重新组合图片并调整大小
            /*
             * imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        //最新图片。拼接头像 和二维码的最新图片
        //        $i = time();
        $img_path = $filename;
        imagepng($QR, $img_path); //不改
        imagedestroy($QR);
        imagedestroy($logo);
        //图片

        return $img_path;
    }
    public function index()
    {
        // 创建图片资源
        $filename = app()->getRootPath() . '/public/static/thigh/thigh.png';
        $image = imagecreatefrompng($filename);

        // 设置字体颜色和字体文件
        $color = imagecolorallocate($image, 255, 255, 255); // 白色字体
        $font_file = app()->getRootPath() . '/extend/' . 'FZFenSTXJW.TTF';
        // $font_file = 'arial.ttf'; // 字体文件路径

        // 给图片添加文本
        $text = "Hello, world!";
        imagettftext($image, 123, 0, 123, 123, $color, $font_file, $text);

        // 将图像保存为PNG文件
        imagepng($image, 'static/thigh/new_image.png');

        // 清除图像资源
        imagedestroy($image);
        //return show(1, ['thigh' => TC('qrcode_url') . '/static/thigh/' . $UserInfo['id'] . '.png', 'name' => $UserInfo['id'] . '.png']);

    }

    public function indexx()
    {
        $UserInfo = $this->request->UserInfo;
        $username = $UserInfo['user_name'];
        $id_card = $UserInfo['sfz'];
        $time = date('Y-m-d H:i:s');
        $thigh = '股权：' . $UserInfo['money_thigh'];

        $filename = app()->getRootPath() . '/public/static/thigh/thigh.png';
        $image = imagecreatefrompng($filename);

        // 设置字体颜色和字体文件
        $color = imagecolorallocate($image, 255, 255, 255); // 白色字体
        $font_file = app()->getRootPath() . '/extend/' . 'FZFenSTXJW.TTF';

        imagettftext($image, 123, 0, 123, 123, $color, $font_file, $username);
        imagettftext($image, 123, 0, 123, 123, $color, $font_file, $id_card);
        imagettftext($image, 123, 0, 123, 123, $color, $font_file, $time);
        imagettftext($image, 123, 0, 123, 123, $color, $font_file, $thigh);

//        $bg_info = getimagesize($filename);
//        $width = $bg_info[0];
//        $height = $bg_info[1];
//        $bg_type = image_type_to_extension($bg_info[2], false);
//        $func = 'imagecreatefrom' . $bg_type;
//        $bg_image = $func($filename);
//
//        $dest = imagecreatetruecolor($width, $height);
//
//        imagecopy($dest, $bg_image, 0, 0, 0, 0, $width, $height);
//
//        $color = imagecolorallocate($dest, 0, 0, 0);
//        $font = app()->getRootPath() . '/extend/' . 'FZFenSTXJW.TTF';
//        imagettftext($dest, 50, 0, 200, 800, $color, $font, $username);
//        imagettftext($dest, 50, 0, 200, 900, $color, $font, $id_card);
//        imagettftext($dest, 50, 0, 200, 1000, $color, $font, $time);
//        imagettftext($dest, 50, 0, 200, 1100, $color, $font, $thigh);
        $newname = app()->getRootPath() . '/public/static/thigh/' . $UserInfo['id'] . '.png';
        // $image = imagecreatefrompng($newname);
//        imagepng($image, NULL, 9);
        // 将图像保存为PNG文件
        imagepng($image, 'static/thigh/new_image.png',9);
        // 清除图像资源
        imagedestroy($image);
//        imagedestroy($image);
        return show(1, ['thigh' => TC('qrcode_url') . '/static/thigh/' . $UserInfo['id'] . '.png', 'name' => $UserInfo['id'] . '.png']);
        //return download($newname);
    }

    public function ThighDownload()
    {
        $name = $this->request->get('name', '');
        if (empty($name)) {
            return show([], config('ToConfig.http_code.error'), 1045);
        }
        $path = 'public/static/qrcode/';
        return $this->download($name, $path);
    }
}