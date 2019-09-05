<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Avatar\Avatar;

class TestController extends Controller
{

    public function index()
    {
        $config = config('laravolt.avatar');
        $avatar = new Avatar($config);
        $avatar->create('scbaa')->save(storage_path('app/public/sample.png'), 100);

        return view('web.test');
    }
}
