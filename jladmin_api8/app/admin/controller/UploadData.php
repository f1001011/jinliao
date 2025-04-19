<?php


namespace app\admin\controller;


use app\BaseController;
use \think\facade\Filesystem;
class UploadData extends BaseController
{

    /**
     * 上传控制器
     * @return mixed
     */

    //通用该接口
    public function video()
    {
        $files = request()->file();
        if (empty($files))
            return $this->failed('未检测到上传文件');
        // fileSize	上传文件的最大字节
        // fileExt	文件后缀，多个用逗号分割或者数组
        // fileMime	文件MIME类型，多个用逗号分割或者数组
        // image	验证图像文件的尺寸和类型
        $savename = [];
        try {
            validate(['image' => 'filesize:100000|fileExt:,mp4'])->check($files);
            foreach ($files as $file) {
                $savename[] = Filesystem::disk('public')->putFile('topic', $file);
                //$savename[] = '/public/video' . date('Ymd') . '/' . $file;
            }
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
        }
        $savename_text = $savename;
        if (empty($savename))
            return $this->failed('上传失败');
        foreach ($savename as $key => &$value) {
            $value = $this->get_http_type().$_SERVER['HTTP_HOST'] . '/storage/' . $value;
            $value = str_replace('\\',"/",$value);
        }
        return $this->success([$savename,$savename_text]);
    }

    public function image()
    {
        $files = request()->file();
        if (empty($files))
            return $this->failed('未检测到上传文件');
        // fileSize	上传文件的最大字节
        // fileExt	文件后缀，多个用逗号分割或者数组
        // fileMime	文件MIME类型，多个用逗号分割或者数组
        // image	验证图像文件的尺寸和类型
        $savename = [];
        try {
            validate(['image' => 'filesize:100000|fileExt:,jpg,gpg,png'])->check($files);
            foreach ($files as $file) {
                $savename[] = \think\facade\Filesystem::putFile('topic', $file);
            }
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
        }

        if (empty($savename))
            return $this->failed('上传失败');
        return $this->success($savename);
    }


    private function get_http_type()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type;
    }
}