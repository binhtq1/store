<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BarController extends Controller
{
    public function show($code)
    {
        return $code;
    }
}
