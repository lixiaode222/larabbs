<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        //所有用户ID数组
        $user_ids = User::all()->pluck('id')->toArray();

        //所有话题ID数组
        $topic_ids = Topic::all()->pluck('id')->toArray();

        //获取Faker实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
                        ->times(1000)
                        ->make()
                        ->each(function ($reply,$index)use($user_ids,$topic_ids,$faker){
                            //随机选一个用户给用户ID赋值
                            $reply->user_id = $faker->randomElement($user_ids);

                            //随机选一个话题给话题ID赋值
                            $reply->topic_id = $faker->randomElement($topic_ids);
                        });

        //将数据转化为数组，并写入数据库
        Reply::insert($replys->toArray());
    }
}

