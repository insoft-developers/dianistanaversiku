<?php

use Illuminate\Support\Facades\Auth;

function adminAuth() 
{
    $getData = Auth::guard("webadmin")->user();
    if (Auth::guard("webadmin")->check()) {
        $getData->avatar_src = 'https://ui-avatars.com/api/?name='.$getData->name.'&background=random';
    }
    return $getData;
}

function userAuth() 
{
    $getData = Auth::guard("web")->user();
    if (Auth::guard("web")->check()) {
        $getData->avatar_src = 'https://ui-avatars.com/api/?name='.$getData->name.'&background=random';
    }
    return $getData;
}

function numberFormat($val) 
{
    return number_format($val,0,',','.');
}