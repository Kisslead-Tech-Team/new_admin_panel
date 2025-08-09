<?php

namespace App\Controllers;

class BaseAction extends BaseController
{



    
    public function index()
    {
        if (sessionCheck()) {
            return view('dashboard');
        }
        return view('login');
        // return view('dashboard');
    }

    public function dashboard()
    {
        return view('dashboard');
    }


    public function toolsBrand()
    {
        return view('tools-brand');
    }

    public function breed()
    {
        return view('breed');
    }

    
}