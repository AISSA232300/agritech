<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function indexe()
    {
        return view('frontend.home');
    }
}
