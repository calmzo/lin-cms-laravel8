<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Cms\BaseController;
use Illuminate\Support\Facades\Artisan;

class TestController extends BaseController
{

    protected $only = ['testLogin'];

    public function test()
    {
//        $ip = '115.236.35.202';
//        $res = kg_ip2region($ip);
        $res = Artisan::call('command:unlock_user_task');

        return $res;
    }


    public function testLogin()
    {
        $res = true;
        return $res;
    }

}
