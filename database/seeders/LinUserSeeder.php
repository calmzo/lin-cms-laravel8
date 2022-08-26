<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LinUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lin_user')->insert([
            [
                'username' => 'root',
                'nickname' => 'root',
                'password' => Hash::make('123456'),
                'email' => 'chenzo0220@163.com',
                'avatar' => 'https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64',
                'create_time' => now(),
                'update_time' => now(),
            ], [
                'username' => 'guest',
                'nickname' => 'guest',
                'password' => Hash::make('123456'),
                'email' => 'chenzo0220@163.com',
                'avatar' => 'https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64',
                'create_time' => now(),
                'update_time' => now(),
            ]
        ]);

    }
}
