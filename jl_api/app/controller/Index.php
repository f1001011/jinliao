<?php

namespace app\controller;

use app\BaseController;
use app\common\Code;

class Index extends BaseController
{

    //上传图片接口
    public function uploadImage()
    {
        // 获取表单上传文件
        $files = request()->file();
        $type = request()->post('type/d',1);
        switch ($type){
            case 1:$catalogue = 'goods';
            break;
            case 2:$catalogue = 'banner';
                break;
        }
        $image = request()->file('image');
        try {
            validate(['image' => 'fileSize:300000|fileExt:jpg,png,jpeg,webp'])->check($files);
            $savename = \think\facade\Filesystem::disk('goods')->putFile($catalogue, $image);
        } catch (\think\exception\ValidateException $e) {
            return show(Code::ERROR, [], $e->getMessage());
        }
        return show(Code::SUCCESS,['upload'=>$savename,'urlupload'=>TC('goods_image_url').'/'.$savename]);
    }


}
