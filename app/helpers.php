<?php

//得到当前路由的名字
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

//生成简介
function make_excerpt($value,$length = 200){
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt,$length);
}