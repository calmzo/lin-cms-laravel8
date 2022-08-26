<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinUserGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lin_user_group')->insert([
            [
                'user_id' => 1,
                'group_id' => 1,
            ], [
                'user_id' => 2,
                'group_id' => 2,
            ]
        ]);
    }
}
