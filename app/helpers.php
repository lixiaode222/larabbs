<?php

//得到当前路由的名字
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}