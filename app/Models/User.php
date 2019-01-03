<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements  MustVerifyEmailContract
{
    use MustVerifyEmailTrait;

    use HasRoles;

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance) {
        //如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()){
            return;
        }

        //只有数据库类型通知才需提醒，直接发送Email或者其他的都Pass
        if (method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //模型关联 用户关联话题
    public function topics(){
        return $this->hasMany(Topic::class);
    }

    //模型关联 用户关联回复
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    //权限
    public function isAuthorOf($model){
        return $this->id == $model->user_id;
    }

    //消息已读
    public function markAsRead(){
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    //密码修改器
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    //头像修改器
    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/upload/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }
}
