<?php

namespace App\Lib\Notice;

use App\Enums\NotificationEnums;
use App\Models\Article;
use App\Models\Notification;
use App\Models\User;

class ArticleFavorited
{

    public function handle(Article $article, User $sender)
    {
        $notification = new Notification();

        $notification->sender_id = $sender->id;
        $notification->receiver_id = $article->user_id;
        $notification->event_id = $article->id;
        $notification->event_type = NotificationEnums::TYPE_ARTICLE_FAVORITED;
        $notification->event_info = [
            'article' => ['id' => $article->id, 'title' => $article->title],
        ];

        $notification->save();
    }

}
