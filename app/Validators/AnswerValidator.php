<?php

namespace App\Validators;

use App\Caches\MaxAnswerIdCache;
use App\Enums\AnswerEnums;
use App\Enums\ReasonEnums;
use App\Exceptions\BadRequestException;
use App\Repositories\AnswerRepository;
use App\Repositories\QuestionRepository;
use App\Utils\CodeResponse;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;

class AnswerValidator extends BaseValidator
{

    public function checkAnswer($id)
    {
        $this->checkId($id);

        $answerRepo = new AnswerRepository();

        $answer = $answerRepo->findById($id);

        if (!$answer) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.not_found');
        }

        return $answer;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxAnswerIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('answer.not_found');
        }
    }

    public function checkQuestion($id)
    {
        $validator = new QuestionValidator();

        return $validator->checkQuestion($id);
    }

    public function checkContent($value)
    {
        $length = kg_strlen($value);
        if ($length < 10) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, AnswerEnums::publishTypes())) {
            throw new BadRequestException('answer.invalid_publish_status');
        }

        return $status;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonEnums::answerRejectOptions())) {
            throw new BadRequestException('answer.invalid_reject_reason');
        }
    }

    public function checkIfAllowAnswer(Question $question, User $user)
    {
        $allowed = true;

        $questionRepo = new QuestionRepository();

        $answers = $questionRepo->findUserAnswers($question->id, $user->id);

//        if ($answers->count() > 0) {
//            $allowed = false;
//        }
//
//        if ($question->closed == 1 || $question->solved == 1) {
//            $allowed = false;
//        }

        if (!$allowed) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.post_not_allowed');
        }
    }

    public function checkIfAllowEdit(Answer $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.edit_not_allowed');
        }

        $case1 = $answer->published == AnswerEnums::PUBLISH_APPROVED;
        $case2 = time() - strtotime($answer->create_time) > 3600;

        if ($case1 && $case2) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.edit_not_allowed');
        }
    }

    public function checkIfAllowDelete(Answer $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'answer.delete_not_allowed');
        }
    }

}
