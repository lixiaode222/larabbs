<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body','category_id','excerpt', 'slug'];

    //模型关联 话题关联用户
    public function user(){
        return $this->belongsTo(User::class);
    }

    //模型关联 话题关联分类
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //模型关联 话题关联回复
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    //按条件排序
    public function scopeWithOrder($query,$order){
        //不同的排序，使用不同的数据读取逻辑
        switch ($order){
            //最新发布
            case 'recent':
                $query->recent();
                break;

            //最新回复
            default:
                $query->recentReplied();
                break;
        }
        //预加载防止N+1问题
        return $query->with('user','category');
    }

    public function scopeRecentReplied($query){
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query){
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
