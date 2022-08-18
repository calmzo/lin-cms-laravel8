<?php

namespace App\Http\Controllers;


use phpspider\core\phpspider;
use VDB\Spider\Spider;

class TestController extends Controller
{

    public function index()
    {
//        $spider = new Spider('http://www.dmoz.org');
        $spider = new phpspider('http://www.dmoz.org');



        return 2222;
    }

}
