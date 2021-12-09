<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        return Inertia::render('Home', [
            'user' => [
                'name' => 'BinhTQ',
            ],
        ]);
    }

    public function generateQRCode()
    {
        return view('test');
    }

    public function generateBarCode()
    {
        return view('test');
    }

}
