<?php

namespace App\Http\Controllers\Backend;

use Log;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MainController extends Controller
{
    public function getMain()
    {
        return view('backend.pages.main');
    }
}