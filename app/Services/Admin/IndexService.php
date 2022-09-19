<?php

namespace App\Services\Admin;


use App\Caches\AppInfoCache;
use App\Caches\SiteGlobalStatCache;
use App\Caches\SiteTodayStatCache;
use App\Lib\AppInfo;
use App\Utils\ServerInfo;
use App\Services\StatService;


class IndexService
{

    public function getMain()
    {
        $data = [
            'global_stat' => $this->getGlobalStat(),
            'today_stat' => $this->getTodayStat(),
            'report_stat' => $this->getReportStat(),
            'mod_stat' => $this->getModerationStat(),
            'app_info' => $this->getAppInfo(),
            'server_info' => $this->getServerInfo(),
        ];
        return $data;
    }

    public function getTopMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getTopMenus();
    }

    public function getLeftMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getLeftMenus();
    }

    public function getAppInfo()
    {
        $cache = new AppInfoCache();

        $content = $cache->get();
        $appInfo = new AppInfo();

        if (empty($content) || $appInfo->get('version') != $content['version']) {
            $cache->rebuild();
        }
        return $content;
    }

    public function getSiteInfo()
    {
        return $this->getSettings('site');
    }

    public function getServerInfo()
    {
        return [
            'cpu' => ServerInfo::cpu(),
            'memory' => ServerInfo::memory(),
            'disk' => ServerInfo::disk(),
        ];
    }

    public function getGlobalStat()
    {
        $cache = new SiteGlobalStatCache();

        return $cache->get();
    }

    public function getTodayStat()
    {
        $cache = new SiteTodayStatCache();

        return $cache->get();
    }

    public function getReportStat()
    {
        $statService = new StatService();

        $articleCount = $statService->countReportedArticles();
        $questionCount = $statService->countReportedQuestions();
        $answerCount = $statService->countReportedAnswers();
        $commentCount = $statService->countReportedComments();

        return [
            'article_count' => $articleCount,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'comment_count' => $commentCount,
        ];
    }

    public function getModerationStat()
    {
        $statService = new StatService();
        $reviewCount = $statService->countPendingReviews();
        $consultCount = $statService->countPendingConsults();
        $articleCount = $statService->countPendingArticles();
        $questionCount = $statService->countPendingQuestions();
        $answerCount = $statService->countPendingAnswers();
        $commentCount = $statService->countPendingComments();

        return [
            'review_count' => $reviewCount,
            'consult_count' => $consultCount,
            'article_count' => $articleCount,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'comment_count' => $commentCount,
        ];
    }


}
