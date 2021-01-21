<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

//админка и все что её касается
class AdminController extends Controller
{
    public function viewControlPanel(Request $request)
    {
        $check = "";

        if(Auth::check())
        {
            $check = "user ". Auth::user()->name ." is logged in";
        }
        else
        {
            $check = "user is NOT logged in";
        }


        return view('/admin/controlPanel', compact('check'));
    }
}
