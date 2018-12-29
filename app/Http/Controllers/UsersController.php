<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //用户个人中心页面
    public function show(User $user){
        return view('users.show',compact('user'));
    }
}
