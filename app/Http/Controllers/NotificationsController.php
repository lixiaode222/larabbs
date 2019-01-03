<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class NotificationsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        //获取登陆用户的所有通知
        $notifications = Auth::user()->notifications()->paginate(20);
        //把消息标记为已读,未读数清零
        Auth::user()->markAsRead();

        return view('notifications.index',compact('notifications'));
    }

}
