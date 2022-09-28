<?php

namespace App\Lib\Validators;

use App\Enums\ReasonEnums;
use App\Enums\ReportEnums;
use App\Exceptions\BadRequestException;
use App\Models\Report;
use App\Services\ReportService;
use App\Utils\CodeResponse;

class ReportValidator extends BaseValidator
{

    public function checkReport($id)
    {
        $report = Report::query()->find($id);

        if (!$report) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'report.not_found');
        }

        return $report;
    }

    public function checkItem($itemId, $itemType)
    {

        $result = null;

        switch ($itemType) {
            case ReportEnums::ITEM_ARTICLE:
                $validator = new ArticleValidator();
                $result = $validator->checkArticle($itemId);
                break;
            case ReportEnums::ITEM_QUESTION:
                $validator = new QuestionValidator();
                $result = $validator->checkQuestion($itemId);
                break;
            case ReportEnums::ITEM_ANSWER:
                $validator = new AnswerValidator();
                $result = $validator->checkAnswer($itemId);
                break;
            case ReportEnums::ITEM_COMMENT:
                $validator = new CommentValidator();
                $result = $validator->checkComment($itemId);
                break;
        }

        return $result;
    }

    public function checkReason($reason, $remark)
    {
        $options = ReasonEnums::reportOptions();

        $value = $options[$reason];

        if ($reason == '105') {
            if (empty($remark)) {
                throw new BadRequestException('report.remark_required');
            }
            $value = $remark;
        }

        return $value;
    }

    public function checkIfReported($userId, $itemId, $itemType)
    {
        $reportService = new ReportService();

        $report = $reportService->findUserReport($userId, $itemId, $itemType);


        if ($report) {
            throw new BadRequestException(CodeResponse::NOT_FOUND_EXCEPTION, 'report.has_reported');
        }
    }

}
