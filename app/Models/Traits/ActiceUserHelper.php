<?php

namespace App\Models\Traits;

use App\Models\Reply;
use Cache;
use Carbon\Carbon;
use DB;

trait ActiceUserHelper{

    //用于存放临时用户数据
    protected $users = [];

    //话题权重
    protected  $topic_weight = 4;
    //回复权重
    protected  $reply_weight = 1;
    //时间期限
    protected $pass_days = 7;
    //活跃用户数量
    protected $user_number = 6;

    //缓存的键名
    protected $cache_key = 'larabbs_active_users';
    //缓存的有效时间
    protected $cache_expire_in_minutes = 65;


    //得到活跃用户
    public function getActiveUsers(){
        //先在缓存中找如果没有找到再调用函数生成并进行缓存
        return Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function (){
              return $this->calculateActiveUsers();
        });
    }

    //得到并缓存活跃用户
    public function calculateAndCacheActiveUsers(){
        //取得活跃用户列表
        $active_users = $this->calculateActiveUsers();
        //将活跃用户缓存
        $this->cacheActiveUsers($active_users);
    }

    //将活跃用户放入缓存
    public function cacheActiveUsers($active_users){
        //将数据放入缓存
        Cache::put($this->cache_key,$active_users,$this->cache_expire_in_minutes);
    }

    //计算活跃用户
    private function calculateActiveUsers(){
        //得到话题得分
        $this->calculateTopicScore();
        //得到回复得分
        $this->calculateReplyScore();

        //数组按照得分排序
        $users = array_sort($this->users,function ($user){
              return $user['score'];
        });

        //我们要的时倒序，第二个参数未保持数组的KEY不变
        $users = array_reverse($users,true);

        //截取需要的活跃用户数量
        $users = array_slice($users,0,$this->user_number,true);

        //新建一个空的集合
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 找寻下是否可以找到用户
            $user = $this->find($user_id);

            // 如果数据库里有该用户的话
            if ($user) {

                // 将此用户实体放入集合的末尾
                $active_users->push($user);
            }
        }

        //返回数据
        return $active_users;
    }

    //计算话题得分
    private function calculateTopicScore(){
        //从话题数据表里取出限定时间范围内，有发表过话题的用户
        //并且同时取出用户此段时间内发布话题的数量
        $topic_users = Reply::query()->select(DB::raw('user_id,count(*) as topic_count'))
                                     ->where('created_at','>=',Carbon::now()->subDay($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();

        //根据话题数量计算得分
        foreach ($topic_users as $value){
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    //计算回复得分
    private function calculateReplyScore(){
        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();

        //根据回复数量计算得分
        foreach ($reply_users as $value){
            $reply_score = $value->reply_count*$this->reply_weight;
            //如果这个用户发过话题的那用户数组就有他了，这件把分数加上去就行了
            //没有的话就新加进去
            if (isset($this->users[$value->user_id])){
                $this->users[$value->user_id]['score'] += $reply_score;
            }else{
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }
}