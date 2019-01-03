<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{

    public function destroy(User $user, Reply $reply)
    {
        //只有文章作者和评论本人可以删除评论
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
