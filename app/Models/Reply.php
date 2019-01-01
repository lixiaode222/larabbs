<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    //模型关联 回复关联话题
    public function topic(){
        return $this->belongsTo(Topic::class);
    }

    //模型关联 回复关联用户
    public function user(){
        return $this->belongsTo(User::class);
    }
}
