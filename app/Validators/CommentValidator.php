<?php

namespace App\Validators;

use App\Enums\CommentEnums;
use App\Enums\ReasonEnums;
use App\Exceptions\BadRequestException;
use App\Repositories\CommentRepository;
use App\Repositories\UserRepository;
use App\Utils\CodeResponse;

class CommentValidator extends BaseValidator
{

    public function checkComment($id)
    {
        $commentRepo = new CommentRepository();

        $comment = $commentRepo->findById($id);
        if (!$comment) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.not_found');
        }

        return $comment;
    }

    public function checkParent($id)
    {
        $commentRepo = new CommentRepository();

        $comment = $commentRepo->findById($id);
        if (!$comment) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.parent_not_found');
        }

        return $comment;
    }

    public function checkToUser($userId)
    {
        $userRepo = new UserRepository();

        $user = $userRepo->findById($userId);
        if (!$user) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.to_user_not_found');
        }

        return $user;
    }

    public function checkItem($itemId, $itemType)
    {
        if (!array_key_exists($itemType, CommentEnums::itemTypes())) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.invalid_item_type');
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
        $value = $content;

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.content_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.content_too_long');
        }

        return $value;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonEnums::commentRejectOptions())) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.invalid_reject_reason');
        }
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, CommentEnums::publishTypes())) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'comment.invalid_publish_status');
        }

        return $status;
    }

}
