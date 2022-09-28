<?php

namespace App\Lib\Validators;

use App\Enums\CommentEnums;
use App\Exceptions\BadRequestException;
use App\Models\Comment;
use App\Models\User;
use App\Utils\CodeResponse;

class CommentValidator extends BaseValidator
{

    public function checkComment($id)
    {
        $comment = Comment::query()->find($id);
        if (!$comment) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.not_found');
        }

        return $comment;
    }

    public function checkParent($id)
    {
        $comment = Comment::query()->find($id);
        if (!$comment) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.parent_not_found');
        }

        return $comment;
    }

    public function checkToUser($userId)
    {
        $user = User::query()->find($userId);
        if (!$user) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.to_user_not_found');
        }

        return $user;
    }

    public function checkItem($itemId, $itemType)
    {
        if (!array_key_exists($itemType, CommentModel::itemTypes())) {
            throw new BadRequestException('comment.invalid_item_type');
        }

        $result = null;

        switch ($itemType) {
            case CommentEnums::ITEM_CHAPTER:
                $validator = new ChapterValidator();
                $result = $validator->checkChapter($itemId);
                break;
            case CommentEnums::ITEM_ARTICLE:
                $validator = new ArticleValidator();
                $result = $validator->checkArticle($itemId);
                break;
            case CommentEnums::ITEM_QUESTION:
                $validator = new QuestionValidator();
                $result = $validator->checkQuestion($itemId);
                break;
            case CommentEnums::ITEM_ANSWER:
                $validator = new AnswerValidator();
                $result = $validator->checkAnswer($itemId);
                break;
        }

        return $result;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('comment.content_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('comment.content_too_long');
        }

        return $value;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonModel::commentRejectOptions())) {
            throw new BadRequestException('comment.invalid_reject_reason');
        }
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, CommentModel::publishTypes())) {
            throw new BadRequestException('comment.invalid_publish_status');
        }

        return $status;
    }

}
