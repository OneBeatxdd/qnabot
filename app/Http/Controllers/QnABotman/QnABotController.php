<?php

namespace App\Http\Controllers\QnABotman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QnABotController extends Controller
{
    // basic welcome page first
    public function index()
    {
        return view('botman.homebot');
    }
}
