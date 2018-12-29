<?php

namespace App\Handlers;
use Image;

class ImageUploadHandler{

    //允许上传的图片类型
    protected $allowed_ext = ["png","jpg","gif","jpeg"];

    //将图片保存的对应文件夹中
    //$file是文件名,$folder是要保存的文件夹名字,$file_prefix是保存后文件的前缀名字
    public function save($file,$folder,$file_prefix,$max_width = false){

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

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url')."/$folder_name/$filename"
        ];
    }

    //图片裁剪
    public function reduceSize($file_path,$max_width){
        //先实例化图片
        $image = Image::make($file_path);

        //进行大小调整的操作
        $image->resize($max_width,null,function ($constraint){

            //设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();
            //防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        //对图片修改后保存
        $image->save();
    }
}