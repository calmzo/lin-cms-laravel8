<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RevokeVipTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revoke_vip_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '撤销会员任务';

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

        echo '------ start revoke vip task ------' . PHP_EOL;

        foreach ($users as $user) {
            $user->vip = 0;
            $user->update();
        }

        echo '------ end revoke vip task ------' . PHP_EOL;
    }

    /**
     * 查找待撤销会员
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findUsers($limit = 1000)
    {
        $time = time();

        return User::query()
            ->where('vip', 1)
            ->where('vip_expiry_time', '<', $time)
            ->limit($limit)
            ->get();
    }
}
