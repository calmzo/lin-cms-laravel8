<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnums;
use App\Enums\ReviewEnums;
use App\Enums\TaskEnums;
use App\Http\Controllers\Cms\BaseController;
use App\Models\Order;
use App\Models\Task;
use Illuminate\Support\Facades\Artisan;

class TestController extends BaseController
{

    protected $only = ['testLogin'];

    public function test()
    {

        $res = ReviewEnums::getValues();

        return $res;
    }


    public function testLogin()
    {
        $res = true;
        return $res;
    }

}
