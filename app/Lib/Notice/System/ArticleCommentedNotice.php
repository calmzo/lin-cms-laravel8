<?php

namespace App\Lib\Notice\System;

use App\Enums\NotificationEnums;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Notification;
use App\Services\Logic\LogicService;

class ArticleCommentedNotice extends LogicService
{

    public function handle(Article $article, Comment $comment)
    {
        $commentContent = kg_substr($comment->content, 0, 36);

        $notification = new Notification();

        $notification->sender_id = $comment->user_id;
        $notification->receiver_id = $article->user_id;
        $notification->event_id = $comment->id;
        $notification->event_type = NotificationEnums::TYPE_ARTICLE_COMMENTED;
        $notification->event_info = [
            'article' => ['id' => $article->id, 'title' => $article->title],
            'comment' => ['id' => $comment->id, 'content' => $commentContent],
        ];

        $notification->save();
    }

}
