<?php

namespace App\Lib\Validators;

use App\Caches\MaxAnswerIdCache;
use App\Exceptions\BadRequestException;
use App\Models\Answer;
use App\Utils\CodeResponse;

class AnswerValidator extends BaseValidator
{

    public function checkAnswer($id)
    {
        $this->checkId($id);

        $answer = Answer::query()->find($id);

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

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('answer.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('answer.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, AnswerModel::publishTypes())) {
            throw new BadRequestException('answer.invalid_publish_status');
        }

        return $status;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonModel::answerRejectOptions())) {
            throw new BadRequestException('answer.invalid_reject_reason');
        }
    }

    public function checkIfAllowAnswer(QuestionModel $question, UserModel $user)
    {
        $allowed = true;

        $questionRepo = new QuestionRepo();

        $answers = $questionRepo->findUserAnswers($question->id, $user->id);

        if ($answers->count() > 0) {
            $allowed = false;
        }

        if ($question->closed == 1 || $question->solved == 1) {
            $allowed = false;
        }

        if (!$allowed) {
            throw new BadRequestException('answer.post_not_allowed');
        }
    }

    public function checkIfAllowEdit(AnswerModel $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException('answer.edit_not_allowed');
        }

        $case1 = $answer->published == AnswerModel::PUBLISH_APPROVED;
        $case2 = time() - $answer->create_time > 3600;

        if ($case1 && $case2) {
            throw new BadRequestException('answer.edit_not_allowed');
        }
    }

    public function checkIfAllowDelete(AnswerModel $answer)
    {
        if ($answer->accepted == 1) {
            throw new BadRequestException('answer.delete_not_allowed');
        }
    }

}
