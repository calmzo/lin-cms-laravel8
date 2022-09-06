<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Cms\BaseController;

class TestController extends BaseController
{

    protected $only = ['testLogin'];

    public function test()
    {
        $ip = '115.236.35.202';
        $res = kg_ip2region($ip);


        return $res;
    }


    public function testLogin()
    {
        $res = true;
        return $res;
    }

}
