<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index() : \Illuminate\View\View
    {
        return view('dashboard.index', []);
    }
}
