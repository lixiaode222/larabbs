<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        //所有用户ID数组
        $user_ids = User::all()->pluck('id')->toArray();

        //所有分类ID数组
        $category_ids = Category::all()->pluck('id')->toArray();

        //获取Faker实例
        $faker = app(Faker\Generator::class);

        //生成的话题假数据
        $topics = factory(Topic::class)
                        ->times(100)
                        ->make()
                        ->each(function ($topic,$index)use($user_ids,$category_ids,$faker){

                            //从用户ID数组中随机取一个作为话题的用户ID
                            $topic->user_id = $faker->randomElement($user_ids);

                            //从分类ID数组中随机取一个作为话题的分类
                            $topic->category_id = $faker->randomElement($category_ids);
                        });

        //将数据转为数组格式，并插入到数据库中
        Topic::insert($topics->toArray());
    }

}

