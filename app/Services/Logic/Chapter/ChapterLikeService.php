<?php

namespace App\Services\Logic\Chapter;

use App\Events\UserDailyCounterIncrChapterLikeCountEvent;
use App\Models\Chapter;
use App\Models\User;
use App\Models\ChapterLike;
use App\Repositories\ChapterLikeRepository;
use App\Repositories\UserRepository;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ChapterTrait;
use App\Validators\UserLimitValidator;

class ChapterLikeService extends LogicService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);
        $uid = AccountLoginTokenService::userId();
        $user = (new UserRepository())->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkDailyChapterLikeLimit($user);

        $likeRepo = new ChapterLikeRepository();

        $chapterLike = $likeRepo->findChapterLike($chapter->id, $user->id);

        if (!$chapterLike) {
            $chapterLike = ChapterLike::query()->create(['chapter_id' => $chapter->id, 'user_id' => $user->id]);

        } else {

            if ($chapterLike->trashed()) {
                $chapterLike->restore();
            } else {
                $chapterLike->delete();
            }
        }

        $this->incrUserDailyChapterLikeCount($user);

        if ($chapterLike->deleted == 0) {

            $action = 'do';

            $this->incrChapterLikeCount($chapter);

        } else {

            $action = 'undo';

            $this->decrChapterLikeCount($chapter);
        }

        return [
            'action' => $action,
            'count' => $chapter->like_count,
        ];
    }

    protected function incrChapterLikeCount(Chapter $chapter)
    {
        $chapter->like_count += 1;

        $chapter->save();
        if ($chapter->parent_id > 0) {
            $parent = $this->checkChapter();

            $parent->like_count += 1;

            $parent->save();
        }

    }

    protected function decrChapterLikeCount(Chapter $chapter)
    {
        if ($chapter->like_count > 0) {
            $chapter->like_count -= 1;
            $chapter->save();
        }
        if ($chapter->parent_id > 0) {
            $parent = $this->checkChapter($chapter->parent_id);

            if ($parent->like_count > 0) {
                $parent->like_count -= 1;
                $parent->save();
            }
        }

    }

    protected function incrUserDailyChapterLikeCount(User $user)
    {
        UserDailyCounterIncrChapterLikeCountEvent::dispatch($user);
    }

}
