<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class QRController extends Controller
{
    public function show($code)
    {
        return $code;
    }
}
