<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use App\Services\Sync\UserIndexSyncService;
use App\Traits\ServiceTrait;
use Illuminate\Console\Command;

class SyncUserIndexTaskCommand extends Command
{

    use ServiceTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_user_index_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $userIds = $redis->sRandMember($key, 1000);

        if (!$userIds) return;

        $userRepo = new UserRepository();

        $users = $userRepo->findByIds($userIds);

        if ($users->count() == 0) return;

//        $document = new UserDocument();
//
//        $handler = new UserSearcher();
//
//        $index = $handler->getXS()->getIndex();
//
//        $index->openBuffer();
//
//        foreach ($users as $user) {
//
//            $doc = $document->setDocument($user);
//
//            if ($user->deleted == 0) {
//                $index->update($doc);
//            } else {
//                $index->del($user->id);
//            }
//        }
//
//        $index->closeBuffer();

        $redis->sRem($key, ...$userIds);
    }

    protected function getSyncKey()
    {
        $sync = new UserIndexSyncService();

        return $sync->getSyncKey();
    }
}
