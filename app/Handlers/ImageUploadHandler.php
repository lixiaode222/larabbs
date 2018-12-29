<?php

namespace App\Handlers;

class ImageUploadHandler{

    //允许上传的图片类型
    protected $allowed_ext = ["png","jpg","gif","jpeg"];

    //将图片保存的对应文件夹中
    //$file是文件名,$folder是要保存的文件夹名字,$file_prefix是保存后文件的前缀名字
    public function save($file,$folder,$file_prefix){

        //构建储存的文件夹规则，值如：uploads/images/avatars/201812/29/
        //文件夹切割能让查找效率更高
        $folder_name = "upload/images/$folder/".date("Ym/d",time());

        //文件具体储存的物理路径
        //先取除public的物理路径然后拼接上我们的文件夹路径就可以
        $upload_path = public_path().'/'.$folder_name;

        //得到文件的后缀名也就是文件类型
        $extension = strtolower($file->getClientOriginalExtension()) ?:'png';

        //拼接保存后的文件名
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$extension;

        //如果上传的图片不是允许上传的类型就终止
        if( !in_array($extension,$this->allowed_ext)){
            return false;
        }

        //将图片移动到我们的目标储存路径中
        $file->move($upload_path,$filename);

        return [
            'path' => config('app.url')."/$folder_name/$filename"
        ];
    }
}