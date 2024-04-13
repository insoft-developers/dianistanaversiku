<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View 
    {
        $view = "dashboard";
        return view("admins.dashboard.index", compact('view'));
    }
}
