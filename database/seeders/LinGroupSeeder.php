<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lin_group')->insert([
            [
                'name' => 'root',
                'info' => '超级用户组',
                'level' => 1,
                'create_time' => now(),
                'update_time' => now(),
            ],
            [
                'name' => 'guest',
                'info' => '游客组',
                'level' => 2,
                'create_time' => now(),
                'update_time' => now(),
            ]
        ]);
    }
}
