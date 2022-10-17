<?php

namespace App\Console\Commands;

use App\Enums\UserEnums;
use App\Models\User;
use Illuminate\Console\Command;

class UnlockUserTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock_user_task';

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
        $users = $this->findUsers();

        echo sprintf('pending users: %s', $users->count()) . PHP_EOL;

        if ($users->count() == 0) return;

        echo '------ start unlock user task ------' . PHP_EOL;
        foreach ($users as $user) {
            $user->update(['locked' => 0]);
        }
//        $userIds = $users->pluck('id');
//        User::query()->whereIn('id', $userIds)->update(['locked' => 0]);
        echo '------ end unlock user task ------' . PHP_EOL;
    }

    /**
     * 查找待解锁用户
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findUsers($limit = 1000)
    {
        $time = date('Y-m-d H:i:s', time() - 6 * 3600);

        return User::query()
            ->where('locked', 1)
            ->where('lock_expiry_time', '<', $time)
            ->limit($limit)
            ->get();
    }
}
