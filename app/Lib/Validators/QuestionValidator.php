<?php

namespace App\Lib\Validators;

use App\Caches\MaxQuestionIdCache;
use App\Caches\Question as QuestionCache;
use App\Exceptions\BadRequestException;
use App\Models\Question;
use App\Models\Question as QuestionModel;
use App\Models\Reason as ReasonModel;
use App\Repos\Question as QuestionRepo;
use App\Utils\CodeResponse;

class QuestionValidator extends BaseValidator
{

    /**
     * @param int $id
     * @return QuestionModel
     * @throws BadRequestException
     */
    public function checkQuestionCache($id)
    {
        $this->checkId($id);

        $questionCache = new QuestionCache();

        $question = $questionCache->get($id);

        if (!$question) {
            throw new BadRequestException('question.not_found');
        }

        return $question;
    }

    public function checkQuestion($id)
    {
        $this->checkId($id);

        $question = Question::query()->find($id);
        if (!$question) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION,'question.not_found');
        }

        return $question;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxQuestionIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'question.not_found');
        }
    }

    public function checkCategory($id)
    {
        $validator = new CategoryValidator();

        return $validator->checkCategory($id);
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 5) {
            throw new BadRequestException('question.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('question.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length > 30000) {
            throw new BadRequestException('question.content_too_long');
        }

        return kg_clean_html($value);
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, QuestionModel::publishTypes())) {
            throw new BadRequestException('question.invalid_publish_status');
        }

        return $status;
    }

    public function checkAnonymousStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('question.invalid_anonymous_status');
        }

        return $status;
    }

    public function checkCloseStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('question.invalid_close_status');
        }

        return $status;
    }

    public function checkRejectReason($reason)
    {
        if (!array_key_exists($reason, ReasonModel::questionRejectOptions())) {
            throw new BadRequestException('question.invalid_reject_reason');
        }
    }

    public function checkIfAllowEdit(QuestionModel $question)
    {
        $approved = $question->published == QuestionModel::PUBLISH_APPROVED;

        $answered = $question->answer_count > 0;

        if ($approved || $answered) {
            throw new BadRequestException('question.edit_not_allowed');
        }
    }

    public function checkIfAllowDelete(QuestionModel $question)
    {
        $approved = $question->published == QuestionModel::PUBLISH_APPROVED;

        $answered = $question->answer_count > 0;

        if ($approved && $answered) {
            throw new BadRequestException('question.delete_not_allowed');
        }
    }

}
