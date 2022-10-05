<?php

namespace App\Services\Logic\Review;

use App\Events\ReviewAfterLikeEvent;
use App\Events\ReviewAfterUndoLikeEvent;
use App\Events\UserDailyCounterIncrReviewLikeCountEvent;
use App\Models\Review;
use App\Models\User;
use App\Repositories\ReviewLikeRepository;
use App\Repositories\UserRepository;
use App\Lib\Notice\ReviewLiked;
use App\Services\Logic\LogicService;
use App\Services\Token\AccountLoginTokenService;
use App\Traits\ReviewTrait;
use App\Traits\UserTrait;
use App\Validators\UserLimitValidator;
use App\Models\ReviewLike;

class ReviewLikeService extends LogicService
{

    use ReviewTrait;
    use UserTrait;

    public function handle($id)
    {
        $review = $this->checkReview($id);

        $uid = AccountLoginTokenService::userId();
        $userRepo = new UserRepository();
        $user = $userRepo->findById($uid);

        $validator = new UserLimitValidator();

        $validator->checkDailyReviewLikeLimit($user);

        $likeRepo = new ReviewLikeRepository();

        $reviewLike = $likeRepo->findReviewLike($review->id, $uid);

        $isFirstTime = true;
        if (!$reviewLike) {

            $reviewLike = ReviewLike::query()->create(['review_id' => $review->id, 'user_id' => $uid]);

        } else {
            $isFirstTime = false;

            if ($reviewLike->trashed()) {
                $reviewLike->restore();
            } else {
                $reviewLike->delete();
            }
        }

        $this->incrUserDailyReviewLikeCount($user);

        if ($reviewLike->deleted == 0) {

            $action = 'do';

            $this->incrReviewLikeCount($review);

            $this->handleLikeNotice($review, $user);

            ReviewAfterLikeEvent::dispatch($review);

        } else {

            $action = 'undo';

            $this->decrReviewLikeCount($review);

            ReviewAfterUndoLikeEvent::dispatch($review);
        }

        $isOwner = $user->id == $review->owner_id;

        /**
         * 仅首次点赞发送通知
         */
        if ($isFirstTime && !$isOwner) {
            $this->handleLikeNotice($review, $user);
        }

        return [
            'action' => $action,
            'count' => $review->like_count,
        ];
    }

    protected function incrReviewLikeCount(Review $review)
    {
        $review->like_count += 1;

        $review->save();
    }

    protected function decrReviewLikeCount(Review $review)
    {
        if ($review->like_count > 0) {
            $review->like_count -= 1;
            $review->save();
        }
    }

    protected function incrUserDailyReviewLikeCount(User $user)
    {
        UserDailyCounterIncrReviewLikeCountEvent::dispatch($user);
    }

    protected function handleLikeNotice(Review $review, User $sender)
    {
        $notice = new ReviewLiked();

        $notice->handle($review, $sender);
    }

}
