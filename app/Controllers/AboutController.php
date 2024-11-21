<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AboutController extends Controller
{
    public function aboutUs()
    {
        return view('about_us');  
    }
}
